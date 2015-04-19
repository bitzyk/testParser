<?php
/**
 * User: Cristian Bitoi
 * Date: 4/17/15
 */

//require_once __DIR__ . '/../Autoloader.php';
require_once 'Autoloader.php';
define('BASE_PATH', realpath(dirname(__FILE__)));
set_error_handler(array('FileAdaptor\Classes\ErrorHandler', 'handler'));

if(php_sapi_name() == 'cli') {
    if(!isset($argv)) {
        die("Please enable argv, argc from php.ini (register_argc_argv) \n");
    }
    if(!isset($argv[1])) {
        die("You must supply output type. \n");
    }
    $outputType =  $argv[1];
} else {
    // maybe another logic - it's not the purpose of the test
    $outputType = 'xml';
}

$fileAdaptor = new \FileAdaptor\FileAdaptor('ITEMS_2015-03-02.csv', $outputType);
$fileAdaptor->process();