<?php

require_once INCLUDE_DIR . DIRECTORY_SEPARATOR . "database.inc.php";

class Product
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

    //region Static Functions
    /**
     * @return array AN Array with all Products in the Database
     */
    public static function getAllProducts(): array
    {
        try {
            $sql = "SELECT * FROM Product";

            //No need for prepared statement, because we do not use inputs.
            $result = getDB()->query($sql);

            if (!$result) {
                return [];
            }

            return $result->fetch_all(MYSQLI_ASSOC);
        }catch (Exception $e){
            echo $e; //TODO Error Handling
        }
        return [];
    }

    //endregion

    //region Database Var Getters
    /**
     * @return int The ID of the modelProduct
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string The Title of the modelProduct
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @return string The Description of the modelProduct
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @return float The Price for this modelProduct
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
     * @return float The Cost for Shipping this modelProduct
     */
    public function getShippingCost(): float
    {
        return $this->shippingCost;
    }

    //endregion

}