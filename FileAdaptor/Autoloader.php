<?php
/**
 * User: Cristian Bitoi
 * Date: 4/17/15
 */
namespace FileAdaptor;

class Autoloader {

    /**
     * psr0 autoloader for simplicity
     *
     * @param string $className
     */
    public static function classLoader($className)
    {
        $className = ltrim($className, '\\');
        $fileName  = '';
        $namespace = '';

        // normalize same namespace autoloader
        if(FALSE !== strpos($className, __NAMESPACE__ .'\\')) {
            $className = str_replace( __NAMESPACE__ .'\\', '', $className);
        }

        if ($lastNsPos = strrpos($className, '\\')) {
            $namespace = substr($className, 0, $lastNsPos);
            $className = substr($className, $lastNsPos + 1);
            $fileName  = str_replace('\\', DIRECTORY_SEPARATOR, $namespace) . DIRECTORY_SEPARATOR;
        }
        $fileName .= str_replace('_', DIRECTORY_SEPARATOR, $className) . '.php';

        require_once $fileName;
    }
}

spl_autoload_register('FileAdaptor\Autoloader::classLoader');