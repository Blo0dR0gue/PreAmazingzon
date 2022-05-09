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
            $result = getDB()->query("SELECT id FROM Product");

            if (!$result) return [];

            $rows = $result->fetch_all(MYSQLI_ASSOC);

            foreach ($rows as $row) {
                $products[] = self::getByID($row["id"]);
            }

            return $products;
        } catch (Exception $e) {
            echo $e; //TODO Error Handling
        }
        return [];
    }

    /**
     * Selects random products from the Database and returns them.
     * @param int $amount The amount of random products, which are selected from the database.
     * @return array An Array of these random products.
     */
    public static function getRandomProducts(int $amount): array
    {
        try {
            $products = [];

            $stmt = getDB()->prepare("SELECT id FROM Product ORDER BY RAND() LIMIT ?;");
            $stmt->bind_param("i", $amount);
            $stmt->execute();

            foreach ($stmt->get_result() as $article) {
                $products[] = self::getByID($article["id"]);
            }
            $stmt->close();

            return $products;
        } catch (Exception $e) {
            echo $e; //TODO Error Handling
        }
        return [];
    }

    public static function getByID(int $id): ?Product
    {
        $stmt = getDB()->prepare("SELECT * from product where id = ?;");
        $stmt->bind_param("i", $id);
        if (!$stmt->execute()) return null;     // TODO ERROR handling

        // get result
        $res = $stmt->get_result();
        if ($res->num_rows === 0) return null;
        $res = $res->fetch_assoc();
        $stmt->close();

        return new Product($id, $res["title"], $res["description"], $res["price"], $res["stock"], $res["shippingCost"]);
    }

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

    public function getOriginalPriceFormatted(): string
    {
        $originalPrice = $this->getPrice() + rand(1, DISCOUNT_VARIATION);
        return number_format($originalPrice, 0, ".", "");
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

    public function getShippingCostFormatted(): string
    {
        return number_format($this->getShippingCost(), 2, ".", "");
    }

    //endregion


    //region extra attributes

    /**
     * Gets the mainImg.
     *
     * First checks whether an image of the product contains 'main.' in the name. If that image does not exist,
     * the first picture is checked afterwords. If this does not exist either, the default notFound image is selected.
     *
     * @return string The Path to the main image.
     */
    public function getMainImg(): string
    {
        $mainImages = glob(IMAGE_DIR . DIRECTORY_SEPARATOR . "products" . DIRECTORY_SEPARATOR . $this->id . DIRECTORY_SEPARATOR . "*main.*");
        if (count($mainImages) !== 0) return $mainImages[0];

        $mainImages = glob(IMAGE_DIR . DIRECTORY_SEPARATOR . "products" . DIRECTORY_SEPARATOR . $this->id . DIRECTORY_SEPARATOR . "1.*");
        if (count($mainImages) !== 0) return $mainImages[0];

        return IMAGE_DIR . DIRECTORY_SEPARATOR . "products" . DIRECTORY_SEPARATOR . "notfound.jpg";
    }

    public function getAllImgs(): array
    {
        $mainImages = glob(IMAGE_DIR . DIRECTORY_SEPARATOR . "products" . DIRECTORY_SEPARATOR . $this->id . DIRECTORY_SEPARATOR . "*");
        if (count($mainImages) !== 0) return $mainImages[0];

        return [IMAGE_DIR . DIRECTORY_SEPARATOR . "products" . DIRECTORY_SEPARATOR . "notfound.jpg"];
    }

    //endregion


    public function insert(): void
    {
        // TODO
    }

    public function update(): void
    {
        // TODO
    }

    public function delete(): void
    {
        // TODO
    }

}