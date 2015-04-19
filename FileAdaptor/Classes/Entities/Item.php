<?php
/**
 * User: Cristian Bitoi
 * Date: 4/17/15
 */
namespace FileAdaptor\Classes\Entities;

use FileAdaptor\Classes\Entities\ItemWarehouseData;

class Item
{
    /**
     * @var string
     */
    protected $wmsSku;

    /**
     * @var string
     */
    protected $sku;

    /**
     * @var string
     */
    protected $eanCode;

    /**
     * @var string
     */
    protected $upcCode;

    /**
     * @var string
     */
    protected $productName;

    /**
     * @var ItemWarehouseData
     */
    protected $warehouseData;

    /**
     * @return string
     */
    public function getWmsSku()
    {
        return $this->wmsSku;
    }

    /**
     * @param string $wmsSku
     */
    public function setWmsSku($wmsSku)
    {
        $this->wmsSku = $wmsSku;
    }

    /**
     * @return string
     */
    public function getSku()
    {
        return $this->sku;
    }

    /**
     * @param string $sku
     */
    public function setSku($sku)
    {
        $this->sku = $sku;
    }

    /**
     * @return string
     */
    public function getEanCode()
    {
        return $this->eanCode;
    }

    /**
     * @param string $eanCode
     */
    public function setEanCode($eanCode)
    {
        $this->eanCode = $eanCode;
    }

    /**
     * @return string
     */
    public function getUpcCode()
    {
        return $this->upcCode;
    }

    /**
     * @param string $upcCode
     */
    public function setUpcCode($upcCode)
    {
        $this->upcCode = $upcCode;
    }

    /**
     * @return string
     */
    public function getProductName()
    {
        return $this->productName;
    }

    /**
     * @param string $productName
     */
    public function setProductName($productName)
    {
        $this->productName = $productName;
    }

    /**
     * @return ItemWarehouseData
     */
    public function getWarehouseData()
    {
        return $this->warehouseData;
    }

    /**
     * @param ItemWarehouseData $warehouseData
     */
    public function setWarehouseData(ItemWarehouseData $warehouseData)
    {
        $this->warehouseData = $warehouseData;
    }
}