<!-- TODO COMMENT-->

<?php
// load required files
require_once(INCLUDE_DIR . DIRECTORY_SEPARATOR . "database.inc.php");

// TODO implement
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
     * @return int
     */
    public function getUserId(): int
    {
        return $this->userId;
    }

    /**
     * @return int
     */
    public function getProdId(): int
    {
        return $this->prodId;
    }

    /**
     * @return int
     */
    public function getAmount(): int
    {
        return $this->amount;
    }

    // endregion

    // region setter
    /**
     * @param int $amount
     */
    public function setAmount(int $amount): void
    {
        $this->amount = $amount;
    }

    // endregion

    public function insert(): void
    {
        // TODO
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
        if (!$stmt->execute()) return null;     // TODO ERROR handling

        $stmt->close();

        return self::getById($this->prodId, $this->userId);
    }

    public function delete(): void
    {
        // TODO
    }

    /**
     * Get all shopping-cart entries (cartProducts) related to one user.
     * @param int $user_id user of interest
     * @return array<CartProduct>|null array of cartProducts
     */
    public static function getAllByUser(int $user_id): ?array
    {
        $stmt = getDB()->prepare("SELECT * from shoppingcart_product where user = ?;");
        $stmt->bind_param("i", $user_id);
        if (!$stmt->execute()) return null;     // TODO ERROR handling

        // get result
        $res = $stmt->get_result();
        if ($res->num_rows === 0) return null;

        $arr = array();
        while ($r = $res->fetch_assoc()) {
            $arr[] = new CartProduct($r["user"], $r["product"], $r["amount"]);
        }
        $stmt->close();

        return $arr;
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
        if (!$stmt->execute()) return null;     // TODO ERROR handling

        // get result
        $res = $stmt->get_result();
        if ($res->num_rows === 0) return null;
        $res = $res->fetch_assoc();
        $stmt->close();

        return new CartProduct($res["user"], $res["product"], $res["amount"]);
    }
}
