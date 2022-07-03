<?php

// Add databse
require_once INCLUDE_DIR . "database.inc.php";

class ProductOrder
{
    // region fields
    private int $productId;
    private int $orderId;
    private int $amount;
    private float $price;
    // endregion

    /**
     * Constructor for {@link ProductOrder}.
     * @param int $productId
     * @param int $orderId
     * @param int $amount
     * @param float $price
     */
    public function __construct(int $productId, int $orderId, int $amount, float $price)
    {
        $this->productId = $productId;
        $this->orderId = $orderId;
        $this->amount = $amount;
        $this->price = $price;
    }

    // region getter

    /**
     * Gets an {@link ProductOrder} by its ids.
     * @param int $productId The id of the {@link Product}.
     * @param int $orderId The id of the {@link Order}
     * @return ProductOrder|null The {@link ProductOrder} or null, if not found.
     */
    public static function getByIDs(int $productId, int $orderId): ?ProductOrder
    {
        $stmt = getDB()->prepare("SELECT * FROM product_order WHERE product = ? AND `order` = ?;");
        $stmt->bind_param("ii", $productId, $orderId);
        if (!$stmt->execute()) {
            logData("Product Order Model", "Query execute error! (get by ids)", CRITICAL_LOG);

            return null;
        }

        // get result
        $res = $stmt->get_result();
        if ($res->num_rows === 0) {
            logData("Product Order Model", "No product order found. (get by ids)", DEBUG_LOG);
            return null;
        }
        $res = $res->fetch_assoc();
        $stmt->close();

        return new ProductOrder($productId, $orderId, $res["amount"], $res["price"]);
    }

    /**
     * Gets all {@link ProductOrder} of an order.
     * @param int $orderId The id of the {@link Order}
     * @return array|null An array with all {@link ProductOrder} or null, if an error occurred.
     */
    public static function getAllByOrder(int $orderId): ?array
    {
        $stmt = getDB()->prepare("SELECT * FROM product_order WHERE `order` = ?;");
        $stmt->bind_param("i", $orderId);
        if (!$stmt->execute()) {
            logData("Product Order Model", "Query execute error! (get by order)", CRITICAL_LOG);
            return null;
        }

        // get result
        $res = $stmt->get_result();
        if ($res->num_rows === 0) {
            logData("Product Order Model", "No product orders were found. (get all by order)", DEBUG_LOG);
            return null;
        }

        $arr = array();
        while ($r = $res->fetch_assoc()) {
            $arr[] = new ProductOrder($r["product"], $r["order"], $r["amount"], $r["price"]);
        }
        $stmt->close();

        return $arr;
    }

    /**
     * Checks, if a {@link User} already bought a specific {@link Product}.
     * @param int $userId The id of the user.
     * @param int $productId The id of the product.
     * @return bool true, if the user bought this product at least once.
     */
    public static function doesUserBoughtThisProduct(int $userId, int $productId): bool
    {
        $stmt = getDB()->prepare("SELECT COUNT(*) as amount FROM product_order po JOIN `order` o on po.`order` = o.id WHERE o.user = ? AND po.product = ?;");
        $stmt->bind_param("ii", $userId, $productId);
        if (!$stmt->execute()) {
            logData("Product Order Model", "Query execute error! (check if user bought item)", CRITICAL_LOG);
            return false;
        }

        // get result
        $res = $stmt->get_result();
        if ($res->num_rows === 0) {
            logData("Product Order Model", "User did not bought this item once.", DEBUG_LOG);
            return 0;
        }
        $res = $res->fetch_assoc();
        $stmt->close();

        return $res["amount"] > 0;
    }

    /**
     * Gets the id of the {@link Product}.
     * @return int The product id.
     */
    public function getProductId(): int
    {
        return $this->productId;
    }

    /**
     * Gets the id of the {@link Order}
     * @return int The order id.
     */
    public function getOrderId(): int
    {
        return $this->orderId;
    }

    /**
     * Gets the price of the {@link Product}
     * @return float The price.
     */
    public function getPrice(): float
    {
        return $this->price;
    }

    /**
     * Gets the formatted price including the currency symbol.
     * @return string The formatted price.
     */
    public function getFormattedUnitPrice(): string
    {
        return number_format($this->price, 2, ".", "") . CURRENCY_SYMBOL;
    }

    /**
     * Gets the formatted full price as string including the currency symbol
     * @return string
     */
    public function getFormattedFullPrice(): string
    {
        return number_format($this->getFullPrice(), 2, ".", "") . CURRENCY_SYMBOL;
    }

    // endregion

    /**
     * Gets full price for this order item (price * amount)
     * @return float The full price
     */
    public function getFullPrice(): float
    {
        return $this->price * $this->getAmount();
    }

    /**
     * Gets the amount of the {@link Product} for this {@link ProductOrder}.
     * @return int The amount.
     */
    public function getAmount(): int
    {
        return $this->amount;
    }

    // endregion

    /**
     * Creates a new {@link ProductOrder} inside the database.
     * @return $this|null The created {@link ProductOrder} or null, if an error occurred.
     */
    public function insert(): ?ProductOrder
    {
        $stmt = getDB()->prepare("INSERT INTO product_order(product, `order`, amount, price)
                                        VALUES (?, ?, ?, ?);");
        $stmt->bind_param("iiid",
                          $this->productId,
                          $this->orderId,
                          $this->amount,
                          $this->price
        );
        if (!$stmt->execute()) {
            logData("Product Order Model", "Query execute error! (insert)", CRITICAL_LOG);
            return null;
        }

        // get result
        $stmt->close();

        return $this;
    }
}