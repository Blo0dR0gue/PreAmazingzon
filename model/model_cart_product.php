<?php
// TODO Comments

// load required files
require_once(INCLUDE_DIR . "database.inc.php");

class CartProduct
{
    // region fields
    private int $userId;
    private int $prodId;
    private int $amount;
    // endregion

    /**
     * @param int $userId
     * @param int $prodId
     * @param int $amount
     */
    public function __construct(int $userId, int $prodId, int $amount)
    {
        $this->userId = $userId;
        $this->prodId = $prodId;
        $this->amount = $amount;
    }


    // region getter

    /**
     * Get all shopping-cart entries (cartProducts) related to one user.
     * @param int $userId user of interest
     * @return array<CartProduct>|null array of cartProducts
     */
    public static function getAllByUser(int $userId): ?array
    {
        $stmt = getDB()->prepare("SELECT * FROM shoppingcart_product WHERE user = ?;");
        $stmt->bind_param("i", $userId);
        if (!$stmt->execute()) {
            logData("Cart Product Model", "Execute error!", LOG_LVL_CRITICAL);
            return null;
        }

        // get result
        $res = $stmt->get_result();
        if ($res->num_rows === 0) {
            logData("Cart Product Model", "No items were found for user with id: ". $userId . "!", LOG_LVL_NOTICE);
            return null;
        }

        $arr = array();
        while ($r = $res->fetch_assoc()) {
            $arr[] = new CartProduct($r["user"], $r["product"], $r["amount"]);
        }
        $stmt->close();

        return $arr;
    }

    /**
     * Get number of shopping-cart entries (cartProducts) related to one user.
     * @param int $userId user of interest
     * @return int number of products in shopping-cart of user
     */
    public static function getCountByUser(int $userId): int
    {
        $stmt = getDB()->prepare("SELECT COUNT(*) AS count FROM shoppingcart_product WHERE user = ?;");
        $stmt->bind_param("i", $userId);
        if (!$stmt->execute()) { return 0; }

        // get result
        $res = $stmt->get_result();
        if ($res->num_rows === 0) {
            logData("Cart Product Model", "No items were found for user with id: ". $userId . "!", LOG_LVL_NOTICE);
            return 0;
        }
        $res = $res->fetch_assoc();
        $stmt->close();

        return $res["count"];
    }

    /**
     * @return int
     */
    public function getUserId(): int
    {
        return $this->userId;
    }

    // endregion

    // region setter

    /**
     * @return int
     */
    public function getProdId(): int
    {
        return $this->prodId;
    }

    // endregion

    /**
     * @return int
     */
    public function getAmount(): int
    {
        return $this->amount;
    }

    /**
     * @param int $amount
     */
    public function setAmount(int $amount): void
    {
        $this->amount = $amount;
    }

    public function insert(): ?CartProduct
    {
        $stmt = getDB()->prepare("INSERT INTO shoppingcart_product(user, product, amount) VALUES (?, ?, ?);");
        $stmt->bind_param("iii",
            $this->userId,
            $this->prodId,
            $this->amount);
        if (!$stmt->execute()) {
            logData("Cart Product Model", "A new item could not be created!", LOG_LVL_CRITICAL);
            return null;
        }

        // get result
        $stmt->close();
        return $this;        // no e.g. autoincrement values, object is inserted as is
    }

    public function update(): ?CartProduct
    {
        $stmt = getDB()->prepare("UPDATE shoppingcart_product 
                                    SET amount = ?
                                    WHERE user = ? AND product = ?;");
        $stmt->bind_param("iii",
            $this->amount,
            $this->userId,
            $this->prodId);
        if (!$stmt->execute()) {
            logData("Cart Product Model", "A new item could not be created!", LOG_LVL_CRITICAL);
            return null;
        }

        $stmt->close();

        return self::getById($this->prodId, $this->userId);
    }

    /**
     * Get an existing cartProduct by its id combination.
     *
     * @param int $productId related product id
     * @param int $userId related user id
     * @return CartProduct|null corresponding cartProduct
     */
    public static function getById(int $productId, int $userId): ?CartProduct
    {
        $stmt = getDB()->prepare("SELECT * FROM shoppingcart_product WHERE user = ? AND product = ?;");
        $stmt->bind_param("ii", $userId, $productId);
        if (!$stmt->execute()) { return null; }

        // get result
        $res = $stmt->get_result();
        if ($res->num_rows === 0) {
            logData("Cart Product Model", "No items got selected for id: " . $productId, LOG_LVL_CRITICAL);
            return null;
        }
        $res = $res->fetch_assoc();
        $stmt->close();

        return new CartProduct($res["user"], $res["product"], $res["amount"]);
    }

    public function delete(): bool
    {
        $stmt = getDB()->prepare("DELETE FROM shoppingcart_product 
                                        WHERE user = ? AND product = ?;");
        $stmt->bind_param("ii",
            $this->userId,
            $this->prodId);
        if (!$stmt->execute()) {
            logData("Cart Product Model", "Item with user id: " . $this->userId . " and product id: " . $this->prodId . " could not be deleted!", LOG_LVL_CRITICAL);
            return false;
        }

        $stmt->close();

        return true;
    }
}
