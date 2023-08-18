<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
    public function __construct($application)
    {
        parent::__construct($application);

        $autoloader = Zend_Loader_Autoloader::getInstance();
        $autoloader->registerNamespace('My_');        
$frontendOptions = array
        (
            'lifetime' 					=> null,
            'automatic_serialization' 	=> true
        );
        $strRootDir 	= APPLICATION_PATH.'/../data/cache' ;
        $backendOptions = array
        (
            'cache_dir'	=> $strRootDir
        );
        $cache = Zend_Cache::factory('Core', 'File', $frontendOptions, $backendOptions);
        Zend_Registry::set('cache', $cache);
    }

    protected function _initDB() {
        // exit("1");
        $config = new Zend_Config_Ini(APPLICATION_PATH . '/configs/application.ini');
        $db = Zend_Db::factory('PDO_MYSQL', array(
            'host'     => $config->resources->db->params->host,
            'dbname'   => $config->resources->db->params->dbname,
            'username' => $config->resources->db->params->username,
            'password' => $config->resources->db->params->password,
            'port'     => $config->resources->db->params->port,
            'charset'  => 'utf8'
        ));
        $db->query("SET NAMES utf8;");
        Zend_Db_Table::setDefaultAdapter($db);
        Zend_Registry::set('db', $db);
    }

    protected function _initPlugins()
    {
        $frontController = Zend_Controller_Front::getInstance();
        $frontController->registerPlugin(new My_Controller_Plugin_ACL(), 1);
        $loader = new Zend_Loader_PluginLoader();
        $loader->addPrefixPath('My_', 'My/Controller/Plugin');        
        return $frontController;
    }

    protected function _initTranlate() {
        require_once 'Zend/Loader.php';
        Zend_Loader::loadClass('Zend_Translate');
        $tr = new Zend_Translate('array', APPLICATION_PATH . '/../lang/vi.php', 'vi');
        $tr->addTranslation(APPLICATION_PATH . '/../lang/en.php', 'en');
        Zend_Registry::set('translate', $tr);
    }
}

