<?php

require_once INCLUDE_DIR . DS . "database.inc.php";

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

    //region getter & setter

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
     * @return int
     */
    public function getAmount(): int
    {
        return $this->amount;
    }

    /**
     * @return float
     */
    public function getPrice(): float
    {
        return $this->price;
    }

    //endregion

    public static function getByIDs(int $productId, int $orderId): ?ProductOrder
    {
        $stmt = getDB()->prepare("SELECT * from product_order where product = ? and `order` = ?;");
        $stmt->bind_param("ii", $productId, $orderId);
        if (!$stmt->execute()) return null;     // TODO ERROR handling

        // get result
        $res = $stmt->get_result();
        if ($res->num_rows === 0) return null;
        $res = $res->fetch_assoc();
        $stmt->close();

        return new ProductOrder($productId, $orderId, $res["amount"], $res["price"]);
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
        if (!$stmt->execute()) return null;     // TODO ERROR handling

        // get result
        $stmt->close();

        return $this;
    }


}