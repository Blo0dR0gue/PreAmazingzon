<?php

//Add database
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


    // region getter & setter

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
            logData("Cart Product Model", "Execute error!", CRITICAL_LOG);
            return null;
        }

        // get result
        $res = $stmt->get_result();
        if ($res->num_rows === 0) {
            logData("Cart Product Model", "No items were found for user with id: " . $userId . "!", NOTICE_LOG);
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
        if (!$stmt->execute()) {
            return 0;
        }

        // get result
        $res = $stmt->get_result();
        if ($res->num_rows === 0) {
            logData("Cart Product Model", "No items were found for user with id: " . $userId . "!", NOTICE_LOG);
            return 0;
        }
        $res = $res->fetch_assoc();
        $stmt->close();

        return $res["count"];
    }

    /**
     * Gets the user id for this cart entry.
     * @return int The user id.
     */
    public function getUserId(): int
    {
        return $this->userId;
    }

    /**
     * Gets the product id for this cart entry.
     * @return int The product id.
     */
    public function getProdId(): int
    {
        return $this->prodId;
    }

    /**
     * Gets the amount of the product for this cart entry.
     * @return int The amount.
     */
    public function getAmount(): int
    {
        return $this->amount;
    }

    /**
     * Sets the amount for the product of this cart entry.
     * @param int $amount The amount.
     */
    public function setAmount(int $amount): void
    {
        $this->amount = $amount;
    }

    // endregion

    /**
     * Creates a new {@link CartProduct} inside the database.
     * @return $this|null The created {@link CartProduct} or null, if an error occurred.
     */
    public function insert(): ?CartProduct
    {
        $stmt = getDB()->prepare("INSERT INTO shoppingcart_product(user, product, amount) VALUES (?, ?, ?);");
        $stmt->bind_param("iii",
                          $this->userId,
                          $this->prodId,
                          $this->amount);
        if (!$stmt->execute()) {
            logData("Cart Product Model", "A new item could not be created!", CRITICAL_LOG);
            return null;
        }

        // get result
        $stmt->close();
        return $this;        // no e.g. autoincrement values, object is inserted as is
    }

    /**
     * Updates the {@link CartProduct} inside the database.
     * @return CartProduct|null The updated {@link CartProduct} or null, if an error occurred.
     */
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
            logData("Cart Product Model", "A new item could not be created!", CRITICAL_LOG);
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
        if (!$stmt->execute()) {
            return null;
        }

        // get result
        $res = $stmt->get_result();
        if ($res->num_rows === 0) {
            logData("Cart Product Model", "No items got selected for id: " . $productId, CRITICAL_LOG);
            return null;
        }
        $res = $res->fetch_assoc();
        $stmt->close();

        return new CartProduct($res["user"], $res["product"], $res["amount"]);
    }

    /**
     * Deletes the {@link CartProduct} from the database
     * @return bool true, if the item got deleted successfully.
     */
    public function delete(): bool
    {
        $stmt = getDB()->prepare("DELETE FROM shoppingcart_product 
                                        WHERE user = ? AND product = ?;");
        $stmt->bind_param("ii",
                          $this->userId,
                          $this->prodId);
        if (!$stmt->execute()) {
            logData("Cart Product Model", "Item with user id: " . $this->userId . " and product id: " . $this->prodId . " could not be deleted!", CRITICAL_LOG);
            return false;
        }

        $stmt->close();

        return true;
    }
}
