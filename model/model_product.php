<?php
// add database
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
     * Constructor of {@link Product}
     * @param int $id
     * @param string $title
     * @param string $description
     * @param float $price
     * @param int $stock
     * @param float $shippingCost
     * @param int|null $categoryID
     * @param bool $active
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

    // region getter & setter

    /**
     * Gets all {@link Product}s from the database.
     * @return array An array with all {@link Product}s from the database.
     */
    public static function getAllProducts(): array
    {
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
    }

    /**
     * Gets an {@link Product} by its id.
     * @param int $id The id.
     * @return Product|null The {@link Product} or null, if not found.
     */
    public static function getByID(int $id): ?Product
    {
        $stmt = getDB()->prepare("SELECT * FROM product WHERE id = ?;");
        $stmt->bind_param("i", $id);
        if (!$stmt->execute()) {
            logData("Product Model", "Query execute error! (get by id)", CRITICAL_LOG);
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
            logData("Product Model", "Query execute error! (get in range)", CRITICAL_LOG);
            return null;
        }

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
            logData("Product Model", "Query execute error! (get random)", CRITICAL_LOG);
            return null;
        }

        foreach ($stmt->get_result() as $product) {
            $products[] = self::getByID($product["id"]);
        }
        $stmt->close();

        return $products;
    }

    /**
     * Selects all **active** products within a given category and range (for paging).
     * @param int|null $categoryID The id of the category.
     * @param int $offset The first row, which should be selected.
     * @param int $amount The amount of rows, which should be selected.
     * @return array|null An Array of the found products or null, if an error occurred.
     */
    public static function getProductsByCategoryIDInRange(?int $categoryID, int $offset, int $amount): ?array
    {
        $products = [];

        if ($categoryID) {
            $stmt = getDB()->prepare("SELECT id FROM product WHERE category = ? ORDER BY id LIMIT ? OFFSET ?;");
            $stmt->bind_param("iii",
                              $categoryID,
                              $amount,
                              $offset
            );
        } else {
            $stmt = getDB()->prepare("SELECT id FROM product WHERE category IS NULL ORDER BY id LIMIT ? OFFSET ?;");
            $stmt->bind_param("ii",
                              $amount,
                              $offset
            );
        }

        if (!$stmt->execute()) {
            logData("Product Model", "Query execute error! (get by category)", CRITICAL_LOG);
            return null;
        }

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

        $searchFilter = strtolower($searchString);
        $searchString = "%$searchFilter%";

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
            logData("Product Model", "Query execute error! (get by search with range)", CRITICAL_LOG);
            return null;
        }

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
            // We use 1=1 to make it possible to add the AND later
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
            logData("Product Model", "Query execute error! (get amount)", CRITICAL_LOG);
            return 0;
        }

        // get result
        $res = $stmt->get_result();
        if ($res->num_rows === 0) {
            // logData("Product Model", "No items were found by search.", LOG_LVL_NOTICE);
            return 0;
        }
        $res = $res->fetch_assoc();
        $stmt->close();

        return $res["count"];
    }

    /**
     * Returns the amounts of products stored in a given category.
     * @param int|null $categoryId CategoryId in which to count the products
     * @param bool $onlyActiveProducts Set it to true to only count active products
     * @return int  The amount of found products
     */
    public static function getAmountOfProductsInCategory(?int $categoryId, bool $onlyActiveProducts): int
    {
        if ($categoryId) {
            $sql = "SELECT COUNT(DISTINCT id) AS count FROM product WHERE category = ?";
        } else {
            $sql = "SELECT COUNT(DISTINCT id) AS count FROM product WHERE category IS NULL";
        }

        if ($onlyActiveProducts) {
            $sql .= " AND active = 1;";
        } else {
            $sql .= ";";
        }

        $stmt = getDB()->prepare($sql);
        if ($categoryId) {
            $stmt->bind_param("i",
                              $categoryId
            );
        }

        if (!$stmt->execute()) {
            logData("Product Model", "Query execute error! (get amount for category)", CRITICAL_LOG);
            return 0;
        }

        // get result
        $res = $stmt->get_result();
        if ($res->num_rows === 0) {
            logData("Product Model", "No items were found for category: " . $categoryId, NOTICE_LOG);
            return 0;
        }
        $res = $res->fetch_assoc();
        $stmt->close();

        return $res["count"];
    }

    /**
     * Gets the id of the {@link Product}.
     * @return int The id.
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Gets the title of the {@link Product}.
     * @return string The title.
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * Sets the title of the {@link Product}.
     * @param string $title The title
     */
    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    /**
     * Gets the description of the {@link Product}.
     * @return string The description.
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * Sets the description of the {@link Product}.
     * @param string $description The description.
     */
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    /**
     * Gets the formatted price.
     * @param int $amount Amount of items of this product.
     * @return string Formatted price for $amount products
     */
    public function getPriceFormatted(int $amount = 1): string
    {
        return number_format($this->getPrice($amount), 2, ".", "") . CURRENCY_SYMBOL;
    }

    /**
     * Gets the price of the {@link Product} multiplied by an amount.
     * @param int $amount The multiplier.
     * @return float The price for this {@link Product}/s.
     */
    public function getPrice(int $amount = 1): float
    {
        return $this->price * $amount;
    }

    /**
     * Gets the formatted price inklusiv costs.
     * @param int $amount Amount of items of this product.
     * @return string Formatted price for $amount products
     */
    public function getPriceTotalFormatted(int $amount = 1): string
    {
        return number_format($this->getPriceTotal($amount), 2, ".", "") . CURRENCY_SYMBOL;
    }

    /**
     * Gets the price of the {@link Product} multiplied by an amount and added shipping cost.
     * @param int $amount The multiplier.
     * @return float The price for this {@link Product}/s.
     */
    public function getPriceTotal(int $amount = 1): float
    {
        return ($this->price + $this->shippingCost) * $amount;
    }

    /**
     * Sets the price of the {@link Product}.
     * @param float $price The price.
     */
    public function setPrice(float $price): void
    {
        $this->price = $price;
    }

    /**
     * Gets the formatted original price. <br>
     * Is random generated.
     * @return string Formatted 'original' before 'discount' price
     */
    public function getOriginalPriceFormatted(): string
    {
        $originalPrice = $this->getPrice() + rand(1, DISCOUNT_VARIATION);
        return number_format($originalPrice, 0, ".", "") . CURRENCY_SYMBOL;
    }

    /**
     * Gets the stock amount of an {@link Product}.
     * @return int The stock amount.
     */
    public function getStock(): int
    {
        return $this->stock;
    }

    /**
     * Sets the stock amount of the {@link Product}.
     * @param int $stock The stock amount.
     */
    public function setStock(int $stock): void
    {
        $this->stock = $stock;
    }

    /**
     * Gets the formatted shipping costs.
     * @return string The formatted shipping costs
     */
    public function getShippingCostFormatted(): string
    {
        return number_format($this->getShippingCost(), 2, ".", "") . CURRENCY_SYMBOL;
    }

    /**
     * Gets the shipping costs of the {@link Product}.
     * @return float The Cost for Shipping this modelProduct
     */
    public function getShippingCost(): float
    {
        return $this->shippingCost;
    }

    /**
     * Sets the shipping costs for the {@link Product}.
     * @param float $shippingCost The shipping costs.
     */
    public function setShippingCost(float $shippingCost): void
    {
        $this->shippingCost = $shippingCost;
    }

    /**
     * Is the {@link Product} active?
     * @return bool True, if it is active.
     */
    public function isActive(): bool
    {
        return $this->active;
    }

    /**
     * Sets the active status of the {@link Product}.
     * @param bool $active Set it to true, if the {@link Product} should be active and visible.
     */
    public function setActive(bool $active): void
    {
        $this->active = $active;
    }

    /**
     * Gets the id of the {@link Category}.
     * @return int|null The id or null, if root.
     */
    public function getCategoryID(): ?int
    {
        return $this->categoryID;
    }

    /**
     * Sets the id of the {@link Category}.
     * @param int|null $categoryID The id of the category or null, if root.
     */
    public function setCategoryID(?int $categoryID): void
    {
        $this->categoryID = $categoryID;
    }

    // endregion


    // region image attribute

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
     * @return array|null An array with all image paths or null of no image were found.
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


    /**
     * Creates a new {@link Product} inside the database.
     * @return Product|null The created {@link Product} or null, if an error occurred.
     */
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
            logData("Product Model", "Query execute error! (insert)", CRITICAL_LOG);
            return null;
        }

        // get result
        $newId = $stmt->insert_id;
        $stmt->close();

        return self::getById($newId);
    }

    /**
     * Updates an {@link Product} inside the database.
     * @return Product|null The updated {@link Product} or null, if an error occurred.
     */
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
            logData("Product Model", "Query execute error! (update)", CRITICAL_LOG);
            return null;
        }

        $stmt->close();

        return self::getById($this->id);
    }

}