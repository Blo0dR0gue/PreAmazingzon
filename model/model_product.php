<?php
// TODO Comments

require_once INCLUDE_DIR . "database.inc.php";

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
    private bool $active;
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
    public function __construct(int $id, string $title, string $description, float $price, int $stock, float $shippingCost, ?int $categoryID, bool $active)
    {
        $this->id = $id;
        $this->title = $title;
        $this->description = $description;
        $this->price = $price;
        $this->stock = $stock;
        $this->shippingCost = $shippingCost;
        $this->categoryID = $categoryID;
        $this->active = $active;
    }

    /**
     * @return array an array with all Products in the Database
     */
    public static function getAllProducts(): array
    {
        try {
            $products = [];

            // No need for prepared statement, because we do not use inputs.
            $result = getDB()->query("SELECT id FROM product ORDER BY id;");

            if (!$result) {
                return [];
            }

            $rows = $result->fetch_all(MYSQLI_ASSOC);

            foreach ($rows as $product) {
                $products[] = self::getByID($product["id"]);
            }

            return $products;
        } catch (Exception $e) {
            echo $e; // TODO Error Handling
        }
        return [];
    }

    public static function getByID(int $id): ?Product
    {
        $stmt = getDB()->prepare("SELECT * FROM product WHERE id = ?;");
        $stmt->bind_param("i", $id);
        if (!$stmt->execute()) {
            return null;
        }

        // get result
        $res = $stmt->get_result();
        if ($res->num_rows === 0) {
            return null;
        }
        $res = $res->fetch_assoc();
        $stmt->close();

        return new Product($id, $res["title"], $res["description"], $res["price"], $res["stock"], $res["shippingCost"], $res["category"], $res["active"]);
    }

    /**
     * Select a specified amount of products starting at an offset.
     * @param int $offset The first row, which should be selected.
     * @param int $amount The amount of rows, which should be selected.
     * @param bool $onlyActiveProducts Set it to true, if only active products should be selected.
     * @return array|null An array with the found products or null, if an error occurred.
     */
    public static function getProductsInRange(int $offset, int $amount, bool $onlyActiveProducts): ?array
    {
        $products = [];

        $sql = "SELECT id FROM product";

        if ($onlyActiveProducts) {
            $sql .= " WHERE active = 1 ";
        } else {
            $sql .= " ";
        }

        $sql .= "ORDER BY id LIMIT ? OFFSET ?;";

        $stmt = getDB()->prepare($sql);
        $stmt->bind_param("ii", $amount, $offset);
        if (!$stmt->execute()) {
            return null;
        }     // TODO ERROR handling

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

        $stmt = getDB()->prepare("SELECT id FROM product ORDER BY RAND() LIMIT ?;");
        $stmt->bind_param("i", $amount);
        if (!$stmt->execute()) {
            return null;
        }

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
        if (!$stmt->execute()) {
            return null;
        }     // TODO ERROR handling

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
     * @param bool $onlyActiveProducts Set it to true to only search for active products.
     * @return array|null An array with the found products or null, if an error occurred.
     */
    public static function searchProducts(string $searchString, bool $onlyActiveProducts): ?array
    {
        $products = [];

        $searchFilter = strtolower($searchString);  // TODO never used?
        $searchString = "%$searchString%";

        $sql = "SELECT DISTINCT p.id FROM product AS p LEFT OUTER JOIN category AS c ON p.category = c.id WHERE LOWER(p.description) LIKE ? OR LOWER(p.title) LIKE ? OR LOWER(c.name) LIKE ?";

        if ($onlyActiveProducts) {
            $sql .= " AND active = 1;";
        } else {
            $sql .= ";";
        }

        $stmt = getDB()->prepare($sql);

        $stmt->bind_param("sss", $searchString, $searchString, $searchString);
        if (!$stmt->execute()) {
            return null;
        }    // TODO ERROR handling

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
     * @param bool $onlyActiveProducts Set it to true to only search for active products.
     * @return array|null An array with the found products or null, if an error occurred.
     */
    public static function searchProductsInRange(string $searchString, bool $onlyActiveProducts, int $offset, int $amount): ?array
    {
        $products = [];

        $searchFilter = strtolower($searchString);  // TODO never used?
        $searchString = "%$searchString%";

        $sql = "SELECT DISTINCT p.id FROM product AS p LEFT OUTER JOIN category AS c ON p.category = c.id WHERE LOWER(p.description) LIKE ? OR LOWER(p.title) LIKE ? OR LOWER(c.name) LIKE ?";

        if ($onlyActiveProducts) {
            $sql .= " AND active = 1 ";
        } else {
            $sql .= " ";
        }

        $sql .= "ORDER BY id LIMIT ? OFFSET ?;";

        $stmt = getDB()->prepare($sql);

        $stmt->bind_param("sssii", $searchString, $searchString, $searchString, $amount, $offset);
        if (!$stmt->execute()) {
            return null;
        }    // TODO ERROR handling

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
     * @param bool $onlyActiveProducts Set it to true to only count active products
     * @return int  The amount of found products
     */
    public static function getAmountOfProducts(?string $searchString, bool $onlyActiveProducts): int
    {
        if (isset($searchString)) {
            $searchFilter = strtolower($searchString);
            $searchString = "%$searchString%";
            $sql = "SELECT COUNT(DISTINCT p.id) AS count FROM product AS p LEFT OUTER JOIN category AS c ON p.category = c.id WHERE (LOWER(p.description) LIKE ? OR LOWER(p.title) LIKE ? OR LOWER(c.name) LIKE ?)";
        } else {
            //We use 1=1 to make it possible to add the AND later
            $sql = "SELECT COUNT(DISTINCT id) AS count FROM product WHERE 1=1";
        }

        if ($onlyActiveProducts) {
            $sql .= " AND active = 1;";
        } else {
            $sql .= ";";
        }

        $stmt = getDB()->prepare($sql);

        if (isset($searchFilter)) {
            $stmt->bind_param("sss", $searchString, $searchString, $searchString);
        }

        if (!$stmt->execute()) {
            return 0;
        }

        // get result
        $res = $stmt->get_result();
        if ($res->num_rows === 0) {
            return 0;
        }
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

    /**
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->active;
    }

    /**
     * @param bool $active
     */
    public function setActive(bool $active): void
    {
        $this->active = $active;
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
        $mainImages = glob(IMAGE_PRODUCT_DIR . $this->id . DS . "*main.*");
        if (!empty($mainImages)) {
            return $mainImages[0];
        }

        $mainImages = $this->getAllImgs();
        if (!empty($mainImages)) {
            return $mainImages[0];
        }

        return IMAGE_PRODUCT_DIR . "notfound.jpg";
    }

    public function getAllImgs(): array
    {
        $images = glob(IMAGE_PRODUCT_DIR . $this->id . DS . "*");
        if (count($images) !== 0) {
            return $images;
        }

        return [IMAGE_PRODUCT_DIR . "notfound.jpg"];
    }

    /**
     * Returns all image paths in an array or null, if there are no images uploaded.
     * @return array|null
     */
    public function getAllImgsOrNull(): ?array
    {
        $images = glob(IMAGE_PRODUCT_DIR . $this->id . DS . "*");
        if (count($images) !== 0) {
            return $images;
        }
        return null;
    }

    // endregion


    public function insert(): ?Product
    {
        $stmt = getDB()->prepare("INSERT INTO product(title, description, price, stock, shippingCost, category, active) 
                                        VALUES (?, ?, ?, ?, ?, ?, ?);");
        $stmt->bind_param("ssdidii",
            $this->title,
            $this->description,
            $this->price,
            $this->stock,
            $this->shippingCost,
            $this->categoryID,
            $this->active
        );
        if (!$stmt->execute()) {
            return null;
        }    // TODO ERROR handling

        // get result
        $newId = $stmt->insert_id;
        $stmt->close();

        return self::getById($newId);
    }

    public function update(): ?Product
    {
        $stmt = getDB()->prepare("UPDATE product 
                                    SET title = ?,
                                        description = ?,
                                        price = ?,
                                        shippingCost = ?,
                                        stock = ?,
                                        category = ?,
                                        active = ?
                                    WHERE id = ?;");
        $stmt->bind_param("ssddiiii",
            $this->title,
            $this->description,
            $this->price,
            $this->shippingCost,
            $this->stock,
            $this->categoryID,
            $this->active,
            $this->id);
        if (!$stmt->execute()) {
            return null;
        }    // TODO ERROR handling

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
        if (!$stmt->execute()) {
            return false;
        }

        $stmt->close();

        return self::getById($this->id) == null;
    }

}