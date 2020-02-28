<?php
error_reporting(E_ALL|E_STRICT);
ini_set("display_errors", 1);
date_default_timezone_set("Europe/Lisbon");

ini_set('soap.wsdl_cache_enabled', 0);
ini_set('soap.wsdl_cache_ttl', 0);
ini_set("display_errors", -1);
ini_set('default_socket_timeout', 0);

//directory setup and class loading
set_include_path('.' . PATH_SEPARATOR . '../library/'
. PATH_SEPARATOR . '../application/models'
. PATH_SEPARATOR . get_include_path());

include "Zend/Loader.php";
Zend_Loader::registerAutoload();

$config = new Zend_Config_Ini("../application/config.ini", "general");
$registry = Zend_Registry::getInstance();
$registry->set("config", $config);

$db = Zend_Db::factory($config->db);
Zend_Db_Table::setDefaultAdapter($db);
Zend_Layout::startMvc(array("layoutPath" => "../application/layouts"));

// Setup controller
$frontController = Zend_Controller_Front::getInstance();
$frontController->throwExceptions(true);//testing only, don't display errors to users
$frontController->setControllerDirectory("../application/controllers");
 
// run!
$frontController->dispatch();
