<?php
/**
 **
 * User: Cristian Bitoi
 * Date: 4/17/15
 */
namespace FileAdaptor\Classes;

use FileAdaptor\Classes\Entities\Item;
use FileAdaptor\Benchmark\Benchmark;

abstract class WritterAbstract
{
    /**
     * @var resourse - file handle
     */
    protected $fh;

    /**
     * @var array - writter specific options
     */
    protected $options = array();

    public function __construct(array $options = array())
    {
        // set file handle to write the output
        $this->fh = fopen($this->getOutputFilePath(),  'w');

        // store the options if any
        if(FALSE === empty($options)) {
            $this->setOptions($options);
        }
    }

    /**
     * Abstract method for parsing item and returning correct format output
     *
     * @param Item $item
     * @return string
     */
    abstract protected function itemToFormat(Item $item);

    /**
     * Method for bussiness logic on write start
     * (e.g. write xml header in case of xml or make anothers initializations)
     */
    abstract public function writeStart();

    /**
     * Method for business logic on write end
     */
    abstract public function writeEnd();

    /**
     * Get writter format type based in writter class
     *
     * @return string
     */
    abstract protected function getWritterFormatType();

    /**
     * Method for writing item output to file handle according with every writter specification.
     *
     * @param Item $item
     * @return bool - True if item has been written to file handle, False otherwise
     */
    public function writeItem(Item $item)
    {
        $output = $this->itemToFormat($item);

        // if we receive output from writter object, we save in the file handle
        if($output) {
            Benchmark::markStart(Benchmark::MODULE_WRITING); // benchmark start
            fwrite($this->fh, $output);
            Benchmark::markEnd(Benchmark::MODULE_WRITING); // benchmark start
            return TRUE;
        }

        return FALSE;
    }

    /**
     * Method for getting file handle of the writter
     *
     * @return resourse
     */
    public function getWritterFileHandle()
    {
        return $this->fh;
    }

    /**
     * Method for getting writter specific options
     * @return array
     */
    protected function getOptions()
    {
        return $this->options;
    }

    /**
     * Method for setting writter specific options
     *
     * @param array $options
     */
    protected function setOptions(array $options)
    {
        $this->options = $options;
    }

    /**
     * Method for releasing file handle from memory when it's not used anymore
     */
    public function __destruct()
    {
        fclose($this->fh);
    }

    /**
     * Get output file path for the writter based on writter format
     */
    private function getOutputFilePath()
    {
        // get writter format type based on what writter we use
        $writterFormatType = $this->getWritterFormatType();

        // variable to store filename based on writter format type
        $fileName = '';

        switch($writterFormatType) {
            case 'XML':
                $fileName = 'output.xml';
                break;
            case 'HTML':
                $fileName = 'output.html';
                break;
        }

        $filePath = BASE_PATH . '/../output/' . $fileName;

        return $filePath;
    }
}