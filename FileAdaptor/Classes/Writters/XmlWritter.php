<?php
/**
 * User: Cristian Bitoi
 * Date: 4/17/15
 */
namespace FileAdaptor\Classes\Writters;

use FileAdaptor\Classes\WritterAbstract;
use FileAdaptor\Classes\Entities\Item;
use FileAdaptor\Classes\ErrorHandler;
use FileAdaptor\Benchmark\Benchmark;

class XmlWritter extends WritterAbstract
{
    /**
     * @var string - store xsd schema in string
     */
    private $xsdSchemaValidateSource = '';

    /**
     * Method for parsing item and returning correct format output
     *
     * @param Item $item
     * @return stringl
     */
    protected function itemToFormat(Item $item)
    {
        Benchmark::markStart(Benchmark::MODULE_CREATE_OUTPUT); // benchmark start

        /**
         * I will use \XmlWritter php build in class and make
         * use of flush to generate content for every Item.
         *
         * DOMDocument or SimpleXmlElement has the main disadvantage
         * that store all the xml tree in memory and can generate scalability issues for large documents
         *
         */
        $writer = new \XMLWriter();
        $writer->openMemory();
        $writer->setIndent(4);

        $writer->startElement('item');
            $writer->writeAttribute('sku', $item->getSku());

            $writer->writeElement('wmsSku',      $item->getWmsSku());
            if($item->getEanCode()) { // ean optional element (if exist provide it)
                $writer->writeElement('eanCode', $item->getEanCode());
            }
            if($item->getUpcCode()) { // upc optional element (if exist provide it)
                $writer->writeElement('upcCode', $item->getUpcCode());
            }
            $writer->writeElement('productName', $item->getProductName());

            $writer->startElement('warehouseData');
                $writer->writeElement('weight',       $item->getWarehouseData()->getWeight());
                $writer->writeElement('height',       $item->getWarehouseData()->getHeight());
                if($item->getWarehouseData()->getLength()) { // length is also optional element
                    $writer->writeElement('length', $item->getWarehouseData()->getLength());
                }
                $writer->writeElement('profiledDate', $item->getWarehouseData()->getProfiledDate());
            $writer->endElement();
        $writer->endElement();

        $writer->endElement();

        $output = $writer->flush(true);

        Benchmark::markEnd(Benchmark::MODULE_CREATE_OUTPUT); // benchmark end

        // we only return content if content pass xsd validator
        if(TRUE === $this->xsdValidate($output)) {
            return $output;
        }
        else {
            ErrorHandler::logXsdError($item);
            return '';
        }

    }

    /**
     * Method for business logic on write start -> start xml header and parent items element
     * (e.g. write xml header in case of xml or make anothers initializations)
     */
    public function writeStart()
    {
        $headerOutput = '<?xml version="1.0" encoding="utf-8"?>' . "\n";
        $headerOutput .= '<items>' . "\n";

        fwrite($this->getWritterFileHandle(), $headerOutput);

        // set xsd schema validate source for xml writter only once -> at writter start
        $this->setXsdSchemaValidateSource();

    }

    /**
     * Method for business logic on write end -> Write closing items tag
     */
    public function writeEnd()
    {
        $footerOutput = '</items>';

        fwrite($this->getWritterFileHandle(), $footerOutput);
    }

    /**
     * @return string - Writter format type
     */
    protected function getWritterFormatType()
    {
        return "XML";
    }

    /**
     * @return string - xsd schema validate source
     */
    private function getXsdSchemaValidateSource()
    {
        return $this->xsdSchemaValidateSource;
    }

    /**
     * Method for setting xsd schema validate source
     */
    private function setXsdSchemaValidateSource()
    {
        $this->xsdSchemaValidateSource = file_get_contents(BASE_PATH . '/../xsd/items.xsd');
    }

    /**
     * Method for validating xml througuh xsd source
     *
     * @param $output string
     * @return bool - True if is valid, false otherwise
     */
    protected function xsdValidate($output)
    {
        Benchmark::markStart(Benchmark::MODULE_VALIDATING_ITEMS); // benchmark start for validating

        // we encapsulate item xml output in a valid header and footer to validate through DomDocument::schemaValidate()
        $writer = new \XMLWriter();
        $writer->openMemory();

        $writer->startDocument('1.0','UTF-8');
        $writer->setIndent(4);
        $writer->startElement('items');
        $writer->writeElement('dateTime', date("c"));
        $writer->writeRaw($output);

        $writer->endElement();

        libxml_use_internal_errors(TRUE); // stop error from beeing displayed

        $dom = new \DomDocument();
        $dom->loadXML($writer->flush(true));
        $valid = $dom->schemaValidateSource($this->getXsdSchemaValidateSource());

        Benchmark::markEnd(Benchmark::MODULE_VALIDATING_ITEMS); // benchmark end for validating

        return (bool)$valid;
    }


}