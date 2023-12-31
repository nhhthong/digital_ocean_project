<?php

namespace PhpConsole;

/**
 * PHP Console client connector that encapsulates client-server protocol implementation
 *
 * You will need to install Google Chrome extension "PHP Console"
 * https://chrome.google.com/webstore/detail/php-console/nfhmhhlpfleoednkpnnnkolmclajemef
 *
 * @package PhpConsole
 * @version 3.0
 * @link http://php-console.com
 * @author Sergey Barbushin http://linkedin.com/in/barbushin
 * @copyright © Sergey Barbushin, 2011-2013. All rights reserved.
 * @license http://www.opensource.org/licenses/BSD-3-Clause "The BSD 3-Clause License"
 */
class Connector {

	const SERVER_PROTOCOL = 5;
	const SERVER_COOKIE = 'php-console-server';
	const CLIENT_INFO_COOKIE = 'php-console-client';
	const CLIENT_ENCODING = 'utf-8';
	const HEADER_NAME = 'PHP-Console';
	const POST_VAR_NAME = '__PHP_Console';
	const SESSION_KEY = '__PHP_Console';
	const POSTPONE_REQUESTS_LIMIT = 10;
	const PHP_HEADERS_SIZE = 1000; // maximum PHP response headers size
	const CLIENT_HEADERS_LIMIT = 200000;

	/** @var Connector */
	protected static $instance;

	/** @var  Dumper|null */
	protected $dumper;
	/** @var  Dispatcher\Debug|null */
	protected $debugDispatcher;
	/** @var  Dispatcher\Errors|null */
	protected $errorsDispatcher;
	/** @var  Dispatcher\Evaluate|null */
	protected $evalDispatcher;
	/** @var  string|null */
	protected $serverEncoding;
	protected $sourcesBasePath;
	protected $headersLimit;

	/** @var Client|null */
	private $client;
	/** @var Auth|null */
	private $auth;
	/** @var Message[] */
	private $messages = array();
	private $isSslOnlyMode = false;
	private $isActiveClient = false;
	private $isAuthorized = false;
	private $isEvalListenerStarted = false;
	private $registeredShutDowns = 0;

	/**
	 * @return static
	 */
	public static function getInstance() {
		if(!self::$instance) {
			self::$instance = new static();
		}
		return self::$instance;
	}

	protected function __construct() {
		$this->initConnection();
		$this->setServerEncoding(ini_get('mbstring.internal_encoding') ? : self::CLIENT_ENCODING);
	}

	private function __clone() {
	}

	/**
	 * Detect script is running in CGI mode
	 * @return int
	 */
	protected function isCgiMode() {
		return preg_match('~^(cgi|cli)~i', php_sapi_name());
	}

	/**
	 * Notify clients that there is active PHP Console on server & check if there is request from client with active PHP Console
	 * @throws \Exception
	 */
	private function initConnection() {
		if($this->isCgiMode()) {
			return;
		}

		$this->initServerCookie();

		$this->client = $this->initClient();
		if($this->client) {
			ob_start(); // required to prevent untimely headers sending
			$this->isActiveClient = true;
			$this->registerFlushOnShutDown();
			$this->setHeadersLimit(isset($_SERVER['SERVER_SOFTWARE']) && stripos($_SERVER['SERVER_SOFTWARE'], 'nginx') !== false
				? 4096 // headers limit for Nginx
				: 8192 // headers limit for all other web-servers
			);

			$this->listenGetPostponedResponse();
		}
	}

	/**
	 * Get connected client data(
	 * @return Client|null
	 * @throws \Exception
	 */
	private function initClient() {
		if(isset($_COOKIE[self::CLIENT_INFO_COOKIE])) {
			$clientData = @json_decode(base64_decode($_COOKIE[self::CLIENT_INFO_COOKIE], true), true);
			if(!$clientData) {
				throw new \Exception('Wrong format of response cookie data: ' . $_COOKIE[self::CLIENT_INFO_COOKIE]);
			}

			$client = new Client($clientData);
			if(isset($clientData['auth'])) {
				$client->auth = new ClientAuth($clientData['auth']);
			}
			return $client;
		}
	}

	/**
	 * Notify clients that there is active PHP Console on server
	 * @throws \Exception
	 */
	private function initServerCookie() {
		if(!isset($_COOKIE[self::SERVER_COOKIE]) || $_COOKIE[self::SERVER_COOKIE] != self::SERVER_PROTOCOL) {
			$isSuccess = setcookie(self::SERVER_COOKIE, self::SERVER_PROTOCOL, 0, '/');
			if(!$isSuccess) {
				throw new \Exception('Unable to set PHP Console server cookie');
			}
		}
	}

	/**
	 * Check if there is client is installed PHP Console extension
	 * @return bool
	 */
	public function isActiveClient() {
		return $this->isActiveClient;
	}

	/**
	 * Set client connection as not active
	 */
	protected function breakClientConnection() {
		$this->isActiveClient = false;
	}

	/**
	 * Check if client with valid auth credentials is connected
	 * @return bool
	 */
	public function isAuthorized() {
		return $this->isAuthorized;
	}

	/**
	 * Set IP masks of clients that will be allowed to connect to PHP Console
	 * @param array $ipMasks Use *(star character) for "any numbers" placeholder array('192.168.*.*', '10.2.12*.*', '127.0.0.1')
	 */
	public function setAllowedIpMasks(array $ipMasks) {
		if($this->isActiveClient()) {
			if(isset($_SERVER['REMOTE_ADDR'])) {
				$ip = $_SERVER['REMOTE_ADDR'];
				foreach($ipMasks as $ipMask) {
					if(preg_match('~^' . str_replace(array('.', '*'), array('\.', '\w+'), $ipMask) . '$~i', $ip)) {
						return;
					}
				}
			}
			$this->breakClientConnection();
		}
	}

	/**
	 * @return Dumper
	 */
	public function getDumper() {
		if(!$this->dumper) {
			$this->dumper = new Dumper();
		}
		return $this->dumper;
	}

	/**
	 * Override default errors dispatcher
	 * @param Dispatcher\Errors $dispatcher
	 */
	public function setErrorsDispatcher(Dispatcher\Errors $dispatcher) {
		$this->errorsDispatcher = $dispatcher;
	}

	/**
	 * Get dispatcher responsible for sending errors/exceptions messages
	 * @return Dispatcher\Errors
	 */
	public function getErrorsDispatcher() {
		if(!$this->errorsDispatcher) {
			$this->errorsDispatcher = new Dispatcher\Errors($this, $this->getDumper());
		}
		return $this->errorsDispatcher;
	}

	/**
	 * Override default debug dispatcher
	 * @param Dispatcher\Debug $dispatcher
	 */
	public function setDebugDispatcher(Dispatcher\Debug $dispatcher) {
		$this->debugDispatcher = $dispatcher;
	}

	/**
	 * Get dispatcher responsible for sending debug messages
	 * @return Dispatcher\Debug
	 */
	public function getDebugDispatcher() {
		if(!$this->debugDispatcher) {
			$this->debugDispatcher = new Dispatcher\Debug($this, $this->getDumper());
		}
		return $this->debugDispatcher;
	}

	/**
	 * Override default eval requests dispatcher
	 * @param Dispatcher\Evaluate $dispatcher
	 */
	public function setEvalDispatcher(Dispatcher\Evaluate $dispatcher) {
		$this->evalDispatcher = $dispatcher;
	}

	/**
	 * Get dispatcher responsible for handling eval requests
	 * @return Dispatcher\Evaluate
	 */
	public function getEvalDispatcher() {
		if(!$this->evalDispatcher) {
			$this->evalDispatcher = new Dispatcher\Evaluate($this, new EvalProvider(), $this->getDumper());
		}
		return $this->evalDispatcher;
	}

	/**
	 * Enable eval request to be handled by eval dispatcher. Must be called after all Connector configurations.
	 * Connector::getInstance()->setPassword() is required to be called before this method
	 * Use Connector::getInstance()->setAllowedIpMasks() for additional access protection
	 * Check Connector::getInstance()->getEvalDispatcher()->getEvalProvider() to customize eval accessibility & security options
	 * @param bool $exitOnEval
	 * @throws \Exception
	 */
	public final function startEvalRequestsListener($exitOnEval = true) {
		if(!$this->auth) {
			throw new \Exception('Eval dispatcher is allowed only in password protected mode. See PhpConsole\Connector::getInstance()->setPassword(...)');
		}
		if($this->isEvalListenerStarted) {
			throw new \Exception('Eval requests listener already started');
		}
		$this->isEvalListenerStarted = true;

		if($this->isActiveClient() && $this->isAuthorized() && isset($_POST[Connector::POST_VAR_NAME]['eval'])) {
			$request = $_POST[Connector::POST_VAR_NAME]['eval'];
			if(!isset($request['data']) || !isset($request['signature'])) {
				throw new \Exception('Wrong PHP Console eval request');
			}
			if($this->auth->getSignature($request['data']) !== $request['signature']) {
				throw new \Exception('Wrong PHP Console eval request signature');
			}
			if($this->serverEncoding) {
				$this->convertEncoding($request['data'], $this->serverEncoding, self::CLIENT_ENCODING);
			}
			$this->getEvalDispatcher()->dispatchCode($request['data']);
			if($exitOnEval) {
				exit;
			}
		}
	}

	/**
	 * Set bath to base dir of project source code(so it will be stripped in paths displaying on client)
	 * @param $sourcesBasePath
	 * @throws \Exception
	 */
	public final function setSourcesBasePath($sourcesBasePath) {
		$sourcesBasePath = realpath($sourcesBasePath);
		if(!$sourcesBasePath) {
			throw new \Exception('Path "' . $sourcesBasePath . '" not found');
		}
		$this->sourcesBasePath = $sourcesBasePath;
	}

	/**
	 * Protect PHP Console connection by password
	 *
	 * Use Connector::getInstance()->setAllowedIpMasks() for additional secure
	 * @param string $password
	 * @param bool $publicKeyByIp Set authorization token depending on client IP
	 * @throws \Exception
	 */
	public final function setPassword($password, $publicKeyByIp = true) {
		if($this->auth) {
			throw new \Exception('Password already defined');
		}
		$this->convertEncoding($password, self::CLIENT_ENCODING, $this->serverEncoding);
		$this->auth = new Auth($password, $publicKeyByIp);
		if($this->client) {
			$this->isAuthorized = $this->client->auth && $this->auth->isValidAuth($this->client->auth);
		}
	}

	/**
	 * Encode var to JSON with errors & encoding handling
	 * @param $var
	 * @return string
	 * @throws \Exception
	 */
	protected function jsonEncode($var) {
		return json_encode($var, defined('JSON_UNESCAPED_UNICODE') ? JSON_UNESCAPED_UNICODE : null);
	}

	/**
	 * Recursive var data encoding conversion
	 * @param $data
	 * @param $fromEncoding
	 * @param $toEncoding
	 */
	protected function convertArrayEncoding(&$data, $toEncoding, $fromEncoding) {
		array_walk_recursive($data, array($this, 'convertWalkRecursiveItemEncoding'), array($toEncoding, $fromEncoding));
	}

	/**
	 * Encoding conversion callback for array_walk_recursive()
	 * @param string $string
	 * @param null $key
	 * @param array $args
	 */
	protected function convertWalkRecursiveItemEncoding(&$string, string $key = null, array $args) {
		$this->convertEncoding($string, $args[0], $args[1]);
	}

	/**
	 * Convert string encoding
	 * @param string $string
	 * @param string $toEncoding
	 * @param string|null $fromEncoding
	 * @throws \Exception
	 */
	protected function convertEncoding(&$string, $toEncoding, $fromEncoding) {
		if($string && is_string($string)) {
			static $isMbString;
			if($isMbString === null) {
				$isMbString = extension_loaded('mbstring');
			}
			if($isMbString) {
				$string = @mb_convert_encoding($string, $toEncoding, $fromEncoding) ? : $string;
			}
			else {
				$string = @iconv($fromEncoding, $toEncoding . '//IGNORE', $string) ? : $string;
			}
			if(!$string && $toEncoding == 'utf-8') {
				$string = utf8_encode($string);
			}
		}
	}

	/**
	 * Set headers size limit for your web-server. You can auto-detect headers size limit by /examples/utils/detect_headers_limit.php
	 * @param $bytes
	 * @throws \Exception
	 */
	public final function setHeadersLimit($bytes) {
		if($bytes < static::PHP_HEADERS_SIZE) {
			throw new \Exception('Headers limit cannot be less then ' . __CLASS__ . '::PHP_HEADERS_SIZE. You need to reconfigure your web server');
		}
		$bytes -= static::PHP_HEADERS_SIZE;
		$this->headersLimit = $bytes < static::CLIENT_HEADERS_LIMIT ? $bytes : static::CLIENT_HEADERS_LIMIT;
	}

	/**
	 * Set your server PHP internal encoding, if it's different from "mbstring.internal_encoding" or utf-8
	 * @param $encoding
	 */
	public final function setServerEncoding($encoding) {
		$encoding = strtolower($encoding);
		if($encoding == 'utf8') {
			$encoding = 'utf-8';
		}
		$this->serverEncoding = $encoding == self::CLIENT_ENCODING ? null : $encoding;
	}

	/**
	 * Send data message to PHP Console client(if it's connected)
	 * @param Message $message
	 */
	public function sendMessage(Message $message) {
		if($this->isActiveClient()) {
			$this->messages[] = $message;
		}
	}

	/**
	 * Register shut down callback handler. Must be called after all errors handlers register_shutdown_function()
	 */
	public final function registerFlushOnShutDown() {
		$this->registeredShutDowns++;
		register_shutdown_function(array($this, 'onShutDown'));
	}

	/**
	 * This method must be called only by register_shutdown_function(). Never call it manually!
	 */
	public function onShutDown() {
		$this->registeredShutDowns--;
		if(!$this->registeredShutDowns) {
			$this->proceedResponsePackage();
		}
	}

	/**
	 * Force connection by SSL for clients with PHP Console installed
	 */
	public function enableSslOnlyMode() {
		$this->isSslOnlyMode = true;
	}

	/**
	 * Check if client is connected by SSL
	 * @return bool
	 */
	protected function isSsl() {
		return (isset($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) == 'on') || (isset($_SERVER['SERVER_PORT']) && ($_SERVER['SERVER_PORT']) == 443);
	}

	/**
	 * Send response data to client
	 * @throws \Exception
	 */
	private function proceedResponsePackage() {
		if($this->isActiveClient()) {
			$response = new Response();
			$response->isSslOnlyMode = $this->isSslOnlyMode;

			if(isset($_POST[self::POST_VAR_NAME]['getBackData'])) {
				$response->getBackData = $_POST[self::POST_VAR_NAME]['getBackData'];
			}

			if(!$this->isSslOnlyMode || $this->isSsl()) {
				if($this->auth) {
					$response->auth = $this->auth->getServerAuthStatus($this->client->auth);
				}
				if(!$this->auth || $this->isAuthorized()) {
					$response->isLocal = isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] == '127.0.0.1';
					$response->docRoot = isset($_SERVER['DOCUMENT_ROOT']) ? $_SERVER['DOCUMENT_ROOT'] : null;
					$response->sourcesBasePath = $this->sourcesBasePath;
					$response->isEvalEnabled = $this->isEvalListenerStarted;
					$response->messages = $this->messages;
				}
			}

			$responseData = $this->serializeResponse($response);

			if(strlen($responseData) > $this->headersLimit) {
				$responseData = $this->serializeResponse(new PostponedResponse(array(
					'id' => $this->postponeResponse($responseData)
				)));
			}

			if(headers_sent($file, $line)) {
				throw new \Exception('Unable to process response data, headers already sent in ' . $file . ':' . $line . '. Try to use ob_start()');
			}
			header(self::HEADER_NAME . ': ' . $responseData);
		}
	}

	protected function objectToArray(&$var) {
		if(is_object($var)) {
			$var = get_object_vars($var);
			array_walk_recursive($var, array($this, 'objectToArray'));
		}
	}

	protected function serializeResponse(DataObject $response) {
		if($this->serverEncoding) {
			$this->objectToArray($response);
			$this->convertArrayEncoding($response, self::CLIENT_ENCODING, $this->serverEncoding);
		}
		return $this->jsonEncode($response);
	}

	/**
	 * Check if there is postponed response request and dispatch it
	 */
	private function listenGetPostponedResponse() {
		if(isset($_POST[self::POST_VAR_NAME]['getPostponedResponse'])) {
			header('Content-Type: application/json; charset=' . self::CLIENT_ENCODING);
			echo $this->getPostponedResponse($_POST[self::POST_VAR_NAME]['getPostponedResponse']);
			$this->breakClientConnection();
			exit;
		}
	}

	/**
	 * Store postponed response data
	 * @param string $responseData
	 * @return string id
	 */
	protected function postponeResponse($responseData) {
		$responses =& $this->getSessionPostponedResponses();
		while(count($responses) >= static::POSTPONE_REQUESTS_LIMIT) {
			array_shift($responses);
		}
		$id = mt_rand() . mt_rand();
		$responses[$id] = $responseData;
		return $id;
	}

	/**
	 * Get postponed response data by id
	 * @param string $responseId
	 * @return string|null
	 */
	protected function getPostponedResponse($responseId) {
		$responses =& $this->getSessionPostponedResponses();
		if(isset($responses[$responseId])) {
			$responseData = $responses[$responseId];
			unset($responses[$responseId]);
			return $responseData;
		}
	}

	/**
	 * Returns reference to postponed responses array in $_SESSION
	 * @return array
	 */
	protected function &getSessionPostponedResponses() {
		if(!session_id()) {
			session_start();
		}
		if(!isset($_SESSION[static::SESSION_KEY]['postpone'])) {
			$_SESSION[static::SESSION_KEY]['postpone'] = array();
		}
		return $_SESSION[static::SESSION_KEY]['postpone'];
	}
}

abstract class DataObject {

	public function __construct(array $properties = array()) {
		foreach($properties as $property => $value) {
			$this->$property = $value;
		}
	}
}

final class Client extends DataObject {

	public $protocol;
	/** @var ClientAuth|null */
	public $auth;
}

final class ClientAuth extends DataObject {

	public $publicKey;
	public $token;
}

final class ServerAuthStatus extends DataObject {

	public $publicKey;
	public $isSuccess;
}

final class Response extends DataObject {

	public $protocol = Connector::SERVER_PROTOCOL;
	/** @var  ServerAuthStatus */
	public $auth;
	public $docRoot;
	public $sourcesBasePath;
	public $getBackData;
	public $isLocal;
	public $isSslOnlyMode;
	public $isEvalEnabled;
	public $messages = array();
}

final class PostponedResponse extends DataObject {

	public $protocol = Connector::SERVER_PROTOCOL;
	public $isPostponed = true;
	public $id;
}

abstract class Message extends DataObject {

	public $type;
}

abstract class EventMessage extends Message {

	public $data;
	public $file;
	public $line;
	/** @var  null|TraceCall[] */
	public $trace;
}

final class TraceCall extends DataObject {

	public $file;
	public $line;
	public $call;
}

final class DebugMessage extends EventMessage {

	public $type = 'debug';
	public $tags;
}

final class ErrorMessage extends EventMessage {

	public $type = 'error';
	public $code;
	public $class;
}

final class EvalResultMessage extends Message {

	public $type = 'eval_result';
	public $return;
	public $output;
	public $time;
}

