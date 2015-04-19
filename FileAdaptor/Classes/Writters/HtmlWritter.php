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

class HtmlWritter extends WritterAbstract
{

    /**
     * Method for parsing item and returning correct format output
     *
     * @param Item $item
     * @return stringl
     */
    protected function itemToFormat(Item $item)
    {
        Benchmark::markStart(Benchmark::MODULE_CREATE_OUTPUT); // benchmark start

        $output = '<tr>';

        $output .= '<td>' . $item->getSku() . '</td>';
        $output .= '<td>' . $item->getWmsSku() . '</td>';
        $output .= '<td>' . $item->getEanCode() . '</td>';
        $output .= '<td>' . $item->getUpcCode() . '</td>';
        $output .= '<td>' . $item->getProductName() . '</td>';

        $output .= '<td>' . $item->getWarehouseData()->getWeight() . '</td>';
        $output .= '<td>' . $item->getWarehouseData()->getHeight() . '</td>';
        $output .= '<td>' . $item->getWarehouseData()->getLength() . '</td>';
        $output .= '<td>' . $item->getWarehouseData()->getProfiledDate() . '</td>';

        $output .= '</tr>';

        Benchmark::markEnd(Benchmark::MODULE_CREATE_OUTPUT); // benchmark start

        return $output;
    }

    /**
     * Method for business logic on write start
     */
    public function writeStart()
    {
        $headerOutput = '<table>';

        $headerOutput .= '<tr>';

        $headerOutput .= '<th>' . 'SKU' . '</th>';
        $headerOutput .= '<th>' . 'WMS SKU' . '</th>';
        $headerOutput .= '<th>' . 'EAN CODE'. '</th>';
        $headerOutput .= '<th>' . 'UPC CODE' . '</th>';
        $headerOutput .= '<th>' . 'PRODUCT NAME' . '</th>';

        $headerOutput .= '<th>' . 'WAREHOUSE WEIGHT'. '</th>';
        $headerOutput .= '<th>' . 'WAREHOUSE HEIGHT' . '</th>';
        $headerOutput .= '<th>' . 'WAREHOUSE LENGTH'. '</th>';
        $headerOutput .= '<th>' . 'WAREHOUSE PROFILED DATE' . '</th>';

        $headerOutput .= '</tr>';

        fwrite($this->getWritterFileHandle(), $headerOutput);
    }

    /**
     * Method for business logic on write end -> Write closing table tag
     */
    public function writeEnd()
    {
        $footerOutput = '</table>';

        fwrite($this->getWritterFileHandle(), $footerOutput);
    }

    /**
     * @return string - Writter format type
     */
    protected function getWritterFormatType()
    {
        return "HTML";
    }
}