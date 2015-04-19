<?php
/**
 * User: Cristian Bitoi
 * Date: 4/17/15
 */
namespace FileAdaptor;

use FileAdaptor\Classes\Entities\Item;
use FileAdaptor\Classes\WritterFactory;
use FileAdaptor\Classes\CsvItemParser;
use FileAdaptor\Classes\WritterAbstract;
use FileAdaptor\Benchmark\Benchmark;

class FileAdaptor
{
    /**
     * @var WritterAbstract
     */
    protected $writter;

    /**
     * @var \SplFileObject
     */
    private $csvFileHandle;

    /**
     * @var int - number of total lines for csvFile
     */
    protected static $csvTotalLines;

    /**
     * show progress every n lines
     */
    const PROGRESS_SHOW_LINES = 2000;

    public function __construct($csvFile, $outputType, $options = array())
    {
        // set the writter
        $this->setWritter($outputType, $options);

        // set csv file from where to read the items
        $this->setCsvFileHandle($csvFile);
    }

    /**
     * Process in a read->write algorithm for less memory consumption (view: author note)
     * Algo:
     * 1. writter start
     * 2. while reading item from csv
     *      3. parse item to an reliable entity
     *      4. write item to csv through writter
     * 5. writter end
     */
    public function process()
    {
        // writter start
        $this->writter->writeStart();

        $csvLineNo = 2; // variable o track csvLine
        $itemValid = 0; // variable to track items valid
        $itemInvalid = 0; // variable to track items invalid

        // while reading items from csv is not finished
        while($data = $this->readItemsFromCsv()) {
            $itemEntity = CsvItemParser::parse($data, $csvLineNo); // parse item to an reliable entity (defensive programming)

            if(!($itemEntity instanceof Item)) // if we don't have an instance of Item just continue. (the error has been logged)
                continue;

            $writted = $this->writter->writeItem($itemEntity); // writter write item

            (TRUE === $writted) ? $itemValid += 1 : $itemInvalid +=1; // track no item valid and invalid

            if($csvLineNo % self::PROGRESS_SHOW_LINES == 0) { // print progress every 1000 items
                Benchmark::printProgress($csvLineNo, $itemValid, $itemInvalid);
            }

            $csvLineNo += 1;
        }

        // writter end
        $this->writter->writeEnd();

        // benchmark print final report
        Benchmark::printReport(array(
            'itemsValid' => $itemValid,
            'itemsInvalid' => $itemInvalid,
        ));
    }

    /**
     * Method for reading items from csv file,
     *
     * @return array|bool - False if EOF is reached, data array of current line otherwise
     */
    protected function readItemsFromCsv()
    {
        Benchmark::markStart(Benchmark::MODULE_READING_CSV); // benchmark start

        // if end of file is reached return False
        if(TRUE === $this->csvFileHandle->eof()) {
            return FALSE;
        }

        // if end of file is not reached will return the data array
        $data =  $this->csvFileHandle->fgetcsv();

        Benchmark::markEnd(Benchmark::MODULE_READING_CSV); // benchmark end

        return $data;
    }


    /**
     * Method for setting the writter from WritterFactory based on output type option
     *
     * @param string $outputType
     * @param array $options
     */
    private function setWritter($outputType, $options = array())
    {
        $this->writter = WritterFactory::build($outputType, $options);
    }

    /**
     * Method for setting csv file handle based on csvFile
     *
     * @param string $csvFile
     */
    private function setCsvFileHandle($csvFile)
    {
        // computing csvFilePath
        $csvFilePath = BASE_PATH . '/../data/' . $csvFile;
        // creating SplFileObject handle
        $this->csvFileHandle = new \SplFileObject($csvFilePath, 'r');
        $this->csvFileHandle->setFlags(\SplFileObject::READ_CSV);

        // compute number of total lines for tracking progress
        $this->csvFileHandle->seek($this->csvFileHandle->getSize());
        self::$csvTotalLines = $this->csvFileHandle->key();
        $this->csvFileHandle->seek(0);

        // just a small logic to skip first line (next() will not work without the current())
        $this->csvFileHandle->current(); $this->csvFileHandle->next();
    }

    public static function getCsvTotalLines()
    {
        return self::$csvTotalLines;
    }
}