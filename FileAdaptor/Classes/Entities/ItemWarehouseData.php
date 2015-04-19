<?php
/**
 * User: Cristian Bitoi
 * Date: 4/17/15
 */
namespace FileAdaptor\Classes\Entities;

class ItemWarehouseData
{
    /**
     * @var float
     */
    protected $weight;

    /**
     * @var float
     */
    protected $height;

    /**
     * @var null|float
     */
    protected $length = null;

    /**
     * @var string
     */
    protected $profiledDate = '';

    /**
     * @return float
     */
    public function getWeight()
    {
        return (float)$this->weight;
    }

    /**
     * @param float $weight
     */
    public function setWeight($weight)
    {
        // weight is represented in kg
        // we should store in gram
        $this->weight = (float)$weight * 1000;
    }

    /**
     * @return float
     */
    public function getHeight()
    {
        return (float)$this->height;
    }

    /**
     * @param float $height
     */
    public function setHeight($height)
    {
        $this->height = (float)$height;
    }

    /**
     * @return float|null
     */
    public function getLength()
    {
        return (float)$this->length;
    }

    /**
     * @param float|null $length
     */
    public function setLength($length = null)
    {
        if($length)
            $this->length = (float)$length;
    }

    /**
     * @return string
     */
    public function getProfiledDate()
    {
        return $this->profiledDate;
    }

    /**
     * @param string $profiledDate
     */
    public function setProfiledDate($profiledDate)
    {
        $this->profiledDate = $profiledDate;
    }


}