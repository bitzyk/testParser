<?php
/**
 * User: Cristian Bitoi
 * Date: 4/17/15
 */

require_once 'Autoloader.php';
define('BASE_PATH', realpath(dirname(__FILE__)));
set_error_handler(array('FileAdaptor\Classes\ErrorHandler', 'handler'));