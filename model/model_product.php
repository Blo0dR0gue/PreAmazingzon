<!-- TODO COMMENT -->

<?php

require_once INCLUDE_DIR . DIRECTORY_SEPARATOR . "database.inc.php";

class Product
{
    // region fields
    private int $id;
    private string $title;
    private string $description;
    private float $price;
    private int $stock;
    private float $shippingCost;
    // endregion

    // Extra Vars
    //TODO
    private string $mainImg;

    /**
     * @param int $id
     * @param string $title
     * @param string $description
     * @param float $price
     * @param int $stock
     * @param float $shippingCost
     */
    public function __construct(int $id, string $title, string $description, float $price, int $stock, float $shippingCost)
    {
        $this->id = $id;
        $this->title = $title;
        $this->description = $description;
        $this->price = $price;
        $this->stock = $stock;
        $this->shippingCost = $shippingCost;
    }

    /**
     * @return array an array with all Products in the Database
     */
    public static function getAllProducts(): array
    {
        try {
            $products = [];

            //No need for prepared statement, because we do not use inputs.
            $result = getDB()->query("SELECT * FROM Product");

            if (!$result) {
                return [];
            }

            $rows = $result->fetch_all(MYSQLI_ASSOC);

            foreach($rows as $row){
                $product = new Product($row["id"], $row["title"], $row["description"], $row["price"], $row["stock"], $row["shippingCost"]);
                $product->setMainImg();
                $products[] = $product;
            }

            return $products;
        }catch (Exception $e){
            echo $e; //TODO Error Handling
        }
        return [];
    }

    // endregion

    // region getter & setter
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

    public function getPriceFormatted(): string
    {
        return number_format($this->getPrice(), 2, ".", "");
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

    //region extra vars
    /**
     * @return string
     */
    public function getMainImg(): string
    {
        if(!isset($this->mainImg)){
            $this->setMainImg();
        }
        return $this->mainImg;
    }

    /**
     * Sets the mainImg Variable.
     */
    public function setMainImg(): void
    {
        $mainImages = glob(IMAGE_DIR . DIRECTORY_SEPARATOR . "products" . DIRECTORY_SEPARATOR . $this->id . DIRECTORY_SEPARATOR . "*-main*");

        if (count($mainImages) === 0) {
            $mainImages = glob(IMAGE_DIR . DIRECTORY_SEPARATOR . "products" . DIRECTORY_SEPARATOR . $this->id . DIRECTORY_SEPARATOR . "1.*");
        }

        if (count($mainImages) === 0) {
            $this->mainImg = IMAGE_DIR . DIRECTORY_SEPARATOR . "products" . DIRECTORY_SEPARATOR . "notfound.jpg";
            return;
        }

        $this->mainImg = $mainImages[0];
    }
    //endregion

    //endregion

}