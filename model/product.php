<?php

class product
{

    //Database Variables
    private int $id;
    private string $title;
    private string $description;
    private float $price;
    private int $stock;
    private float $shippingCost;

    //Extra Vars
    //TODO

    //region Database Var Getters
    /**
     * @return int The ID of the product
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string The Title of the product
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @return string The Description of the product
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @return float The Price for this product
     */
    public function getPrice(): float
    {
        return $this->price;
    }

    /**
     * @return int The Amount of Items in Stock
     */
    public function getStock(): int
    {
        return $this->stock;
    }

    /**
     * @return float The Cost for Shipping this product
     */
    public function getShippingCost(): float
    {
        return $this->shippingCost;
    }

    //endregion

}