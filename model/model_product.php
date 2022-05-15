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
    private ?int $categoryID;
    // endregion


    /**
     * @param int $id
     * @param string $title
     * @param string $description
     * @param float $price
     * @param int $stock
     * @param float $shippingCost
     * @param int|null $categoryID
     */
    public function __construct(int $id, string $title, string $description, float $price, int $stock, float $shippingCost, ?int $categoryID)
    {
        $this->id = $id;
        $this->title = $title;
        $this->description = $description;
        $this->price = $price;
        $this->stock = $stock;
        $this->shippingCost = $shippingCost;
        $this->categoryID = $categoryID;
    }

    /**
     * @return array an array with all Products in the Database
     */
    public static function getAllProducts(): array
    {
        try {
            $products = [];

            //No need for prepared statement, because we do not use inputs.
            $result = getDB()->query("SELECT id FROM Product ORDER BY id;");

            if (!$result) return [];

            $rows = $result->fetch_all(MYSQLI_ASSOC);

            foreach ($rows as $product) {
                $products[] = self::getByID($product["id"]);
            }

            return $products;
        } catch (Exception $e) {
            echo $e; //TODO Error Handling
        }
        return [];
    }

    /**
     * Select a specified amount of products starting at an offset.
     * @param int $offset The first row, which should be selected.
     * @param int $amount The amount of rows, which should be selected.
     * @return array|null An array with the found products or null, if an error occurred.
     */
    public static function getProductsInRange(int $offset, int $amount): ?array
    {
        $products = [];

        $stmt = getDB()->prepare("SELECT id from product ORDER BY id limit ? offset ?;");
        $stmt->bind_param("ii", $amount, $offset);
        if (!$stmt->execute()) return null;     // TODO ERROR handling

        // get result

        foreach ($stmt->get_result() as $product) {
            $products[] = self::getByID($product["id"]);
        }
        $stmt->close();
        return $products;
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

        return new Product($id, $res["title"], $res["description"], $res["price"], $res["stock"], $res["shippingCost"], $res["category"]);
    }

    /**
     * Selects random products from the Database and returns them.
     * @param int $amount The amount of random products, which are selected from the database.
     * @return null|array An Array of these random products or null, if an error occurred.
     */
    public static function getRandomProducts(int $amount): ?array
    {
        $products = [];

        $stmt = getDB()->prepare("SELECT id FROM Product ORDER BY RAND() LIMIT ?;");
        $stmt->bind_param("i", $amount);
        if (!$stmt->execute()) return null;     // TODO ERROR handling

        foreach ($stmt->get_result() as $product) {
            $products[] = self::getByID($product["id"]);
        }
        $stmt->close();

        return $products;
    }

    /**
     * Selects all products with the passed category id.
     * @param int $categoryID The id of the category.
     * @return array|null An Array of the found products or null, if an error occurred.
     */
    public static function getProductsByCategoryID(int $categoryID): ?array
    {
        $products = [];

        $stmt = getDB()->prepare("SELECT id from product where category = ?;");
        $stmt->bind_param("i", $categoryID);
        if (!$stmt->execute()) return null;     // TODO ERROR handling

        // get result

        foreach ($stmt->get_result() as $product) {
            $products[] = self::getByID($product["id"]);
        }
        $stmt->close();
        return $products;
    }

    /**
     * Selects all products containing the passed string in either the description, the title or in the name of the category.
     * @param string $searchString The string which should be in the defined texts.
     * @return array|null An array with the found products or null, if an error occurred.
     */
    public static function searchProducts(string $searchString): ?array
    {
        $products = [];

        $searchString = "%$searchString%";

        $stmt = getDB()->prepare("SELECT DISTINCT p.id from product as p LEFT OUTER JOIN Category as c on p.category = c.id where p.description LIKE ? OR p.title LIKE ? OR c.name LIKE ?;");
        $stmt->bind_param("sss", $searchString, $searchString, $searchString);
        if (!$stmt->execute()) return null;     // TODO ERROR handling

        // get result

        foreach ($stmt->get_result() as $product) {
            $products[] = self::getByID($product["id"]);
        }
        $stmt->close();
        return $products;
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

    public function getPriceFormatted(int $amount = 1): string
    {
        return number_format($this->getPrice($amount), 2, ".", "") . CURRENCY_SYMBOL;
    }

// TODO deal with sipping cost? per amount or add after?
    /**
     * @return float The Price for this modelProduct
     */
    public function getPrice(int $amount = 1): float
    {
        return $this->price * $amount;
    }

    public function getOriginalPriceFormatted(): string
    {
        $originalPrice = $this->getPrice() + rand(1, DISCOUNT_VARIATION);
        return number_format($originalPrice, 0, ".", "") . CURRENCY_SYMBOL;
    }

    /**
     * @return int The Amount of Items in Stock
     */
    public function getStock(): int
    {
        return $this->stock;
    }

    public function getShippingCostFormatted(): string
    {
        return number_format($this->getShippingCost(), 2, ".", "") . CURRENCY_SYMBOL;
    }

    /**
     * @return float The Cost for Shipping this modelProduct
     */
    public function getShippingCost(): float
    {
        return $this->shippingCost;
    }

    /**
     * @return int|null
     */
    public function getCategoryID(): ?int
    {
        return $this->categoryID;
    }

    // endregion


    // region extra attributes

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

        $mainImages = $this->getAllImgs();
        if (count($mainImages) !== 0) return $mainImages[0];

        return IMAGE_DIR . DIRECTORY_SEPARATOR . "products" . DIRECTORY_SEPARATOR . "notfound.jpg";
    }

    public function getAllImgs(): array
    {
        $images = glob(IMAGE_DIR . DIRECTORY_SEPARATOR . "products" . DIRECTORY_SEPARATOR . $this->id . DIRECTORY_SEPARATOR . "*");
        if (count($images) !== 0) return $images;

        return [IMAGE_DIR . DIRECTORY_SEPARATOR . "products" . DIRECTORY_SEPARATOR . "notfound.jpg"];
    }

    // endregion


    public function insert(): ?Product
    {
        $stmt = getDB()->prepare("INSERT INTO product(title, description, price, stock, shippingCost, category) 
                                        VALUES (?, ?, ?, ?, ?, ?);");
        $stmt->bind_param("ssdidi",
            $this->title,
            $this->description,
            $this->price,
            $this->stock,
            $this->shippingCost,
            $this->categoryID,
        );
        if (!$stmt->execute()) return null;     // TODO ERROR handling

        // get result
        $newId = $stmt->insert_id;
        $stmt->close();

        return self::getById($newId);
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