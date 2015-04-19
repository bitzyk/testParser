<?php
/**
 * User: Cristian Bitoi
 * Date: 4/17/15
 */
namespace FileAdaptor\Classes;

use FileAdaptor\Classes\Entities\Item;
use FileAdaptor\Classes\Entities\ItemWarehouseData;
use FileAdaptor\Benchmark\Benchmark;

/**
 * Class CsvItemParser
 * @package FileAdaptor\Classes
 */
class CsvItemParser
{
    /**
     * Method for parsing data row array from csv and construct an reliable entity (defensive programming).
     *
     * @param array $data
     * @param int $csvLine - csvLine from which the $data array is constructed and Item created
     * @return Item|null - it may return null when data array is not valid, an error is logged in this case
     */
    public static function parse(array $data, $csvLine = 0)
    {
        Benchmark::markStart(Benchmark::MODULE_PARSING_CSV); // benchmark start

        // in case of failing validating csv row data -> log the error -> return null
        if(FALSE === self::validate($data)) {
            $errorMessage = "Could not build data form line $csvLine into an reliable Item entity.";
            trigger_error($errorMessage, E_USER_NOTICE);
            return null;
        }

        // build item entity based on data array
        $itemEntity = new Item();
        $itemEntity->setProductName($data[5]);
        $itemEntity->setWmsSku($data[1]);
        $itemEntity->setSku($data[2]);
        $itemEntity->setEanCode($data[3]);
        $itemEntity->setUpcCode($data[4]);

        // build item warehouse data to set up on item entitiy
        $itemWarehouseData = new ItemWarehouseData();
        $itemWarehouseData->setHeight($data[7]);
        $itemWarehouseData->setLength($data[9]);
        $itemWarehouseData->setWeight($data[6]);
        $itemWarehouseData->setProfiledDate($data[10]);
        // set item warehouse data to item
        $itemEntity->setWarehouseData($itemWarehouseData);

        Benchmark::markEnd(Benchmark::MODULE_PARSING_CSV); // benchmark end

        return $itemEntity;
    }


    /**
     * Method for validation $data row array -> just validate to have 11 columns per row
     *
     * @param array $data
     * @return bool - True if data is valid, False otherwise
     */
    protected static function validate(array $data)
    {
        $valid = FALSE;

        if(count($data) == 11) {
            $valid = TRUE;
        }

        return $valid;
    }
}