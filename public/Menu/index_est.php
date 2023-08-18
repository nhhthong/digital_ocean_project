<?php
error_reporting(E_ALL | E_STRICT);

define('APPLICATION_PATH' , realpath(dirname(__FILE__)) .'/../application' );

set_include_path(
    APPLICATION_PATH . '/../library'
    . PATH_SEPARATOR . get_include_path()
);

require_once 'Zend/Loader.php';
Zend_Loader::registerAutoload();


try
{
    require '../application/bootstrap.php';
}
catch(Exception $exception)
{
    echo "<html><body> an exception occured while bootstrapping the application";

    if (defined('APPLICATION_ENVIRONMENT') && APPLICATION_ENVIRONMENT != 'production')
    {
        echo "<br/><br/>" . $excepion->getMessage() . "<br/>"
        . "<div align='left'>Stack Trace: "
        . "<pre> " . $exception->getTraceAsString() . "</pre></div>";
    }
    echo "</body></html";
    exit(1);
}

Zend_Controller_Front::getInstance()->dispatch();