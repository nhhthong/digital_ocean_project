<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1); 
error_reporting(E_ALL);
// require_once(__DIR__ . '/../library/PhpConsole/__autoload.php');
// PhpConsole\Helper::register();
// $handler = PhpConsole\Handler::getInstance();
// $handler->start();
defined('APPLICATION_PATH')
    || define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/../application'));

defined('APPLICATION_ENV')
    || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'development'));

set_include_path(implode(PATH_SEPARATOR, array(
    realpath(APPLICATION_PATH . '/../library'),
    get_include_path(),
)));
require_once 'Zend/Application.php';
include_once APPLICATION_PATH . '/configs/common.conf.php';
date_default_timezone_set('Asia/Saigon');
try {   
    $application = new Zend_Application(
        APPLICATION_ENV,
        APPLICATION_PATH . '/configs/application.ini'
    );              
    $application->bootstrap()->run();
} catch (\Throwable  $e){
    echo '<pre>';var_dump($e); exit;
}