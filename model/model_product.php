<!-- TODO COMMENT -->

<?php

require_once INCLUDE_DIR . DS . "database.inc.php";

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

    public static function getByID(int $id): ?Product
    {
        $stmt = getDB()->prepare("SELECT * FROM product WHERE id = ?;");
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
     * Select a specified amount of products starting at an offset.
     * @param int $offset The first row, which should be selected.
     * @param int $amount The amount of rows, which should be selected.
     * @return array|null An array with the found products or null, if an error occurred.
     */
    public static function getProductsInRange(int $offset, int $amount): ?array
    {
        $products = [];

        $stmt = getDB()->prepare("SELECT id FROM product ORDER BY id LIMIT ? OFFSET ?;");
        $stmt->bind_param("ii", $amount, $offset);
        if (!$stmt->execute()) return null;     // TODO ERROR handling

        // get result

        foreach ($stmt->get_result() as $product) {
            $products[] = self::getByID($product["id"]);
        }
        $stmt->close();
        return $products;
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

        $stmt = getDB()->prepare("SELECT id FROM product WHERE category = ?;");
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

        $searchFilter = strtolower($searchString);
        $searchString = "%$searchString%";

        $stmt = getDB()->prepare("SELECT DISTINCT p.id FROM product AS p LEFT OUTER JOIN Category AS c ON p.category = c.id WHERE LOWER(p.description) LIKE ? OR LOWER(p.title) LIKE ? OR LOWER(c.name) LIKE ?;");
        $stmt->bind_param("sss", $searchString, $searchString, $searchString);
        if (!$stmt->execute()) return null;     // TODO ERROR handling

        // get result

        foreach ($stmt->get_result() as $product) {
            $products[] = self::getByID($product["id"]);
        }
        $stmt->close();
        return $products;
    }

    /**
     * Returns the amounts of products stored in the database using a filter, if it is defined.
     * @param string|null $searchString A filter, which is used to test, if the passed string is either in the description, the title or in the name of the category of a product.
     * @return int  The amount of found products
     */
    public static function getAmountOfProducts(?string $searchString): int
    {
        if (isset($searchString)) {
            $searchFilter = strtolower($searchString);
            $searchString = "%$searchString%";
            $sql = "SELECT COUNT(DISTINCT p.id) AS count FROM product AS p LEFT OUTER JOIN Category AS c ON p.category = c.id WHERE LOWER(p.description) LIKE ? OR LOWER(p.title) LIKE ? OR LOWER(c.name) LIKE ?;";
        } else {
            $sql = "SELECT COUNT(DISTINCT id) AS count FROM product;";
        }
        $stmt = getDB()->prepare($sql);

        if (isset($searchFilter)) {
            $stmt->bind_param("sss", $searchString, $searchString, $searchString);
        }

        if (!$stmt->execute()) return 0;     // TODO ERROR handling

        // get result
        $res = $stmt->get_result();
        if ($res->num_rows === 0) return 0;
        $res = $res->fetch_assoc();
        $stmt->close();

        return $res["count"];
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
     * @param string $title
     */
    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    /**
     * @return string The Description of the modelProduct
     */
    public function getDescription(): string
    {
        return $this->description;
    }


// TODO deal with shipping cost? per amount or add after?

    /**
     * @param string $description
     */
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function getPriceFormatted(int $amount = 1): string
    {
        return number_format($this->getPrice($amount), 2, ".", "") . CURRENCY_SYMBOL;
    }

    /**
     * @return float The Price for this modelProduct
     */
    public function getPrice(int $amount = 1): float
    {
        return $this->price * $amount;
    }

    /**
     * @param float $price
     */
    public function setPrice(float $price): void
    {
        $this->price = $price;
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

    /**
     * @param int $stock
     */
    public function setStock(int $stock): void
    {
        $this->stock = $stock;
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
     * @param float $shippingCost
     */
    public function setShippingCost(float $shippingCost): void
    {
        $this->shippingCost = $shippingCost;
    }

    /**
     * @return int|null
     */
    public function getCategoryID(): ?int
    {
        return $this->categoryID;
    }

    /**
     * @param int|null $categoryID
     */
    public function setCategoryID(?int $categoryID): void
    {
        $this->categoryID = $categoryID;
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
        $mainImages = glob(IMAGE_DIR . DS . "products" . DS . $this->id . DS . "*main.*");
        if (count($mainImages) !== 0) return $mainImages[0];

        $mainImages = $this->getAllImgs();
        if (count($mainImages) !== 0) return $mainImages[0];

        return IMAGE_DIR . DS . "products" . DS . "notfound.jpg";
    }

    public function getAllImgs(): array
    {
        $images = glob(IMAGE_DIR . DS . "products" . DS . $this->id . DS . "*");
        if (count($images) !== 0) return $images;

        return [IMAGE_DIR . DS . "products" . DS . "notfound.jpg"];
    }

    /**
     * Returns all image paths in an array or null, if there are no images uploaded.
     * @return array|null
     */
    public function getAllImgsOrNull(): ?array
    {
        $images = glob(IMAGE_DIR . DS . "products" . DS . $this->id . DS . "*");
        if (count($images) !== 0) return $images;
        return null;
    }

    // endregion


    public function insert(): ?Product
    {
        $stmt = getDB()->prepare("INSERT INTO Product(title, description, price, stock, shippingCost, category) 
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

    public function update(): ?Product
    {
        $stmt = getDB()->prepare("UPDATE Product 
                                    SET title = ?,
                                        description = ?,
                                        price = ?,
                                        shippingCost = ?,
                                        stock = ?,
                                        category = ?
                                    WHERE id = ?;");
        $stmt->bind_param("ssddiii",
            $this->title,
            $this->description,
            $this->price,
            $this->shippingCost,
            $this->stock,
            $this->categoryID,
            $this->id);
        if (!$stmt->execute()) return null;     // TODO ERROR handling

        $stmt->close();

        return self::getById($this->id);
    }


    /**
     * Deletes itself from the database.
     * @return bool true, if the product got deleted.
     */
    public function delete(): bool
    {
        $stmt = getDB()->prepare("DELETE FROM product WHERE id = ?;");
        $stmt->bind_param("i",
            $this->id);
        if (!$stmt->execute()) return false;     // TODO ERROR handling

        $stmt->close();

        return self::getById($this->id) == null;
    }

}