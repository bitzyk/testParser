<?php
/**
 * User: Cristian Bitoi
 * Date: 4/17/15
 */
namespace FileAdaptor\Classes;

use FileAdaptor\Classes\Entities\Item;

class ErrorHandler
{
    /**
     * @var null|resource
     */
    private static $logXsdErrorFileHandler = null;

    /**
     * @var null|resource
     */
    private static $logErrorFileHandler = null;

    /**
     * Method for logging xsd error
     *
     * @param Item $item
     */
    public static function logXsdError(Item $item)
    {
        $errors = libxml_get_errors();

        if(!empty($errors)) {
            $errorMessage = "Error validating elemen with wmsSku ".$item->getWmsSku().". Errors:\n" ;

            foreach($errors as $e) {
                $errorMessage .=  '    '. $e->message;
            }

            fwrite(self::getLogXsdErrorFileHandler(), $errorMessage);
        }

        libxml_clear_errors();
    }

    /**
     * Error handler for treating E_USER_WARNING, E_USER_NOTICE types of errors
     */
    public static function handler($errno ,  $errstr, $errfile, $errline, $errcontext)
    {
        $errorMessage = "Error level: " . $errno . "\nError message:" . $errstr . "\n\n";

        switch ($errno) {
            case $errno == E_USER_WARNING || $errno == E_USER_NOTICE:
                fwrite(self::getLogErrorFileHandler(), $errorMessage);
                return TRUE;
            case E_USER_ERROR:
                fwrite(self::getLogErrorFileHandler(), $errorMessage);
                die($errstr ."\n");
        }


        return FALSE; // for other type of errors mantain the normal php error handler
    }

    /**
     * Singleton method for creating log xsd error file (Just creating once, when first error occurs)
     *
     * @return resource
     */
    private static function getLogXsdErrorFileHandler()
    {
        if(!self::$logXsdErrorFileHandler) {
            self::$logXsdErrorFileHandler = fopen(BASE_PATH . '/../log/xsdValidateErrors.log','w');
        }

        return self::$logXsdErrorFileHandler;
    }

    /**
     * Singleton method for creating user defined log error file (Just creating once, when first error occurs)
     *
     * @return resource
     */
    private static function getLogErrorFileHandler()
    {
        if(!self::$logErrorFileHandler) {
            self::$logErrorFileHandler = fopen(BASE_PATH . '/../log/error.log','w');
        }

        return self::$logErrorFileHandler;
    }
}
