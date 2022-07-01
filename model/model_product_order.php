<?php
// TODO Comments

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

    // region getter & setter

    public static function getByIDs(int $productId, int $orderId): ?ProductOrder
    {
        $stmt = getDB()->prepare("SELECT * FROM product_order WHERE product = ? AND `order` = ?;");
        $stmt->bind_param("ii", $productId, $orderId);
        if (!$stmt->execute()) {
            logData("Product Order Model", "Query execute error! (get)", LOG_LVL_CRITICAL);

            return null;
        }

        // get result
        $res = $stmt->get_result();
        if ($res->num_rows === 0) { return null; }
        $res = $res->fetch_assoc();
        $stmt->close();

        return new ProductOrder($productId, $orderId, $res["amount"], $res["price"]);
    }

    public static function getAllByOrder(int $orderId): ?array
    {
        $stmt = getDB()->prepare("SELECT * FROM product_order WHERE `order` = ?;");
        $stmt->bind_param("i", $orderId);
        if (!$stmt->execute()) {
            logData("Product Order Model", "Query execute error! (get)", LOG_LVL_CRITICAL);
            return null;
        }

        // get result
        $res = $stmt->get_result();
        if ($res->num_rows === 0) { return null; }

        $arr = array();
        while ($r = $res->fetch_assoc()) {
            $arr[] = new ProductOrder($r["product"], $r["order"], $r["amount"], $r["price"]);
        }
        $stmt->close();

        return $arr;
    }

    /**
     * @return int
     */
    public function getProductId(): int
    {
        return $this->productId;
    }

    /**
     * @return int
     */
    public function getOrderId(): int
    {
        return $this->orderId;
    }

    /**
     * @return float
     */
    public function getPrice(): float
    {
        return $this->price;
    }

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
     * @return int
     */
    public function getAmount(): int
    {
        return $this->amount;
    }

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
            logData("Product Order Model", "Query execute error! (insert)", LOG_LVL_CRITICAL);
            return null;
        }

        // get result
        $stmt->close();

        return $this;
    }
}