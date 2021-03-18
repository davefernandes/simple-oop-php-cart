<?php

class Product
{
    private $id;
    private $name;
    private $price;

    /**
     * Product Constructor
     * 
     * @param string $id
     * @param string $name
     * @param float  $price
     */
    public function __construct(string $id,string $name,float $price)
    {
        $this->id = $id;
        $this->name = $name;
        $this->price = $price;
    }

    /**
     * @return string $id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string $name
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return float $price
     */
    public function getPrice()
    {
        return $this->price;
    }

}