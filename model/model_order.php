<!-- TODO COMMENT-->

<?php
// load required files
require_once(INCLUDE_DIR . DS . "database.inc.php");

// TODO implement
class Order
{

    // region fields
    private int $id;
    private DateTime $orderDate;
    private DateTime $deliveryDate;
    private bool $paid;
    private int $orderStateId;
    private int $userId;
    private int $shippingAddressId;
    // endregion

    /**
     * @param int $id
     * @param DateTime $orderDate
     * @param DateTime $deliveryDate
     * @param bool $paid
     * @param int $orderStateId
     * @param int $userId
     * @param int $shippingAddressId
     */
    public function __construct(int $id, DateTime $orderDate, DateTime $deliveryDate, bool $paid, int $orderStateId, int $userId, int $shippingAddressId)
    {
        $this->id = $id;
        $this->orderDate = $orderDate;
        $this->deliveryDate = $deliveryDate;
        $this->paid = $paid;
        $this->orderStateId = $orderStateId;
        $this->userId = $userId;
        $this->shippingAddressId = $shippingAddressId;
    }

    // region getter & setter

    public static function getAmountForUser(int $userId): int
    {
        $stmt = getDB()->prepare("SELECT COUNT(DISTINCT id) AS count FROM `order` WHERE user = ?;");
        $stmt->bind_param("i", $userId);

        if (!$stmt->execute()) return 0;     // TODO ERROR handling

        // get result
        $res = $stmt->get_result();
        if ($res->num_rows === 0) return 0;
        $res = $res->fetch_assoc();
        $stmt->close();

        return $res["count"];
    }

    /**
     * @throws Exception
     */
    public static function getAllForUserInRange(int $user_id, int $offset, int $amount): ?array
    {
        $stmt = getDB()->prepare("SELECT * FROM `order` WHERE user = ? ORDER BY orderDate DESC limit ? OFFSET ?;");
        $stmt->bind_param("iii", $user_id, $amount, $offset);
        if (!$stmt->execute()) return null;     // TODO ERROR handling

        // get result
        $res = $stmt->get_result();
        if ($res->num_rows === 0) return null;

        $arr = array();
        while ($r = $res->fetch_assoc()) {
            $arr[] = new Order($r["id"], new DateTime($r["orderDate"]), new DateTime($r["deliveryDate"]), $r["paid"], $r["orderState"], $r["user"], $r["shippingAddress"]);
        }
        $stmt->close();

        return $arr;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return DateTime
     */
    public function getOrderDate(): DateTime
    {
        return $this->orderDate;
    }

    public function getFormattedOrderDate(): string
    {
        return $this->orderDate->format("d.m.Y H:i:s");//TODO constant
    }

    /**
     * @return DateTime
     */
    public function getDeliveryDate(): DateTime
    {
        return $this->deliveryDate;
    }

    public function getFormattedDeliveryDate(): string
    {
        return $this->deliveryDate->format("d.m.Y");//TODO constant
    }

    /**
     * @return bool
     */
    public function isPaid(): bool
    {
        return $this->paid;
    }

    /**
     * @return int
     */
    public function getOrderStateId(): int
    {
        return $this->orderStateId;
    }

    // endregion

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
    public function getShippingAddressId(): int
    {
        return $this->shippingAddressId;
    }

    /**
     * @throws Exception
     */
    public function insert(): ?Order
    {
        $stmt = getDB()->prepare("INSERT INTO `order`(orderDate, deliveryDate, paid, orderState, user, shippingAddress)
                                        VALUES (?, ?, ?, ?, ?, ?);");

        $orderDate = $this->orderDate->format("Y-m-d H:i:s");
        $deliveryDate = $this->deliveryDate->format("Y-m-d H:i:s");

        $stmt->bind_param("ssiiii",
            $orderDate,
            $deliveryDate,
            $this->paid,
            $this->orderStateId,
            $this->userId,
            $this->shippingAddressId
        );
        if (!$stmt->execute()) return null;     // TODO ERROR handling

        // get result
        $newId = $stmt->insert_id;
        $stmt->close();

        return self::getById($newId);
    }

    /**
     * @throws Exception
     */
    public static function getById(int $id): ?Order
    {
        $stmt = getDB()->prepare("SELECT * FROM `order` WHERE id = ?;");
        $stmt->bind_param("i", $id);
        if (!$stmt->execute()) return null;     // TODO ERROR handling

        // get result
        $res = $stmt->get_result();
        if ($res->num_rows === 0) return null;
        $res = $res->fetch_assoc();
        $stmt->close();

        return new Order($id, new DateTime($res["orderDate"]), new DateTime($res["deliveryDate"]), $res["paid"], $res["orderState"], $res["user"], $res["shippingAddress"]);
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
