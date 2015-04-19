<?php
/**
 * User: Cristian Bitoi
 * Date: 4/17/15
 */
namespace FileAdaptor\Benchmark;
use FileAdaptor\FileAdaptor;

class Benchmark
{
    const MODULE_VALIDATING_ITEMS = 2;

    const MODULE_READING_CSV = 4;

    const MODULE_PARSING_CSV = 8;

    const MODULE_CREATE_OUTPUT  = 16;

    const MODULE_WRITING = 32;

    /**
     * Store times for every modules
     *
     * @var array
     */
    protected static $modulesTimes = array(
        self::MODULE_VALIDATING_ITEMS => 0,
        self::MODULE_PARSING_CSV => 0,
        self::MODULE_READING_CSV => 0,
        self::MODULE_CREATE_OUTPUT => 0,
        self::MODULE_WRITING => 0,
    );


    /**
     * Mark start to store microtime start for every module
     * It's possible multiple modules to be nestes so only one variable store isn't enough
     *
     * @var array
     */
    protected static $markStart = array(
        self::MODULE_VALIDATING_ITEMS => 0,
        self::MODULE_PARSING_CSV => 0,
        self::MODULE_READING_CSV => 0,
        self::MODULE_CREATE_OUTPUT => 0,
        self::MODULE_WRITING => 0,
    );

    public static function printReport($data)
    {
        $message = "\n\n----------------Report Benchmark-\n";

        $message .= "Progress percent: 100%\n";
        $message .= "No items valid: $data[itemsValid]\n";
        $message .= "No items invalid: $data[itemsInvalid]\n";

        $memoryUsageMb = number_format(memory_get_peak_usage()/(1024*1024), 4, '.', '');
        $message .= "Memory peak usage:" . $memoryUsageMb . " Mb\n";

        $message .= "------\n";

        // print execution times for every module
        foreach(self::$modulesTimes as $moduleKey => $mT) {
            $mT = number_format($mT, 6, '.', '');
            $message .= "Execution time for module: " . self::getModuleName($moduleKey) . ": " . $mT . " seconds \n";
        }

        $message .= "---------------------------------\n";

        if(defined("STDOUT")) {
            fwrite(STDOUT, $message);
        }
        else {
            // for sapi different than cli another logic - it's not the purpose of the test
        }
    }

    /**
     * Method for printing progress of parsing csvFile
     *
     * @param $currCsvLine
     */
    public static function printProgress($currCsvLine, $itemValid, $itemInvalid)
    {
        $progress = number_format(($currCsvLine / FileAdaptor::getCsvTotalLines()) * 100, 2, '.', ''); // compute progress percent
        $remaining = number_format(100 - $progress, 2, '.', ''); // compute remaining percent

        $message = "Progress: $progress%. Remaining: $remaining%. ($currCsvLine parsed). ";
        $message .= "Valid items: $itemValid. Invalid items: $itemInvalid. \n";

        if(defined("STDOUT")) {
            fwrite(STDOUT, $message);
        }
        else {
            // for sapi different than cli another logic - it's not the purpose of the test
        }
    }

    /**
     * Mark start execution time for a module
     *
     * @param $module
     */
    public static function markStart($module)
    {
        self::$markStart[$module] = microtime(true);
    }

    /**
     * Mark end execution time for a module, compute execution time and store
     *
     * @param $module
     */
    public static function markEnd($module)
    {
        $execTime = microtime(true) - self::$markStart[$module];

        $execTime = (float)number_format($execTime, 10, '.', '');

        self::$modulesTimes[$module] += $execTime;
    }

    /**
     * Get Module Name
     *
     * @param $moduleKey
     * @return string
     */
    private static function getModuleName($moduleKey)
    {
        switch($moduleKey) {
            case self::MODULE_VALIDATING_ITEMS:
                return 'Module Validating Items';
            case self::MODULE_READING_CSV:
                return 'Module Reading Csv';
            case self::MODULE_PARSING_CSV:
                return 'Module Parsing Csv';
            case self::MODULE_CREATE_OUTPUT:
                return 'Module Create Output';
            case self::MODULE_WRITING:
                return 'Module Writting';
        }
    }
}