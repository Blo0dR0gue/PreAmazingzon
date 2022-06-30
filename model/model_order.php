<?php

// load required files
require_once(INCLUDE_DIR . "database.inc.php");

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


    /**
     * Gets the amount of orders.
     * @return int The amount of orders.
     */
    public static function getAmount(): int
    {
        $stmt = getDB()->prepare("SELECT COUNT(DISTINCT id) AS count FROM `order`;");

        if (!$stmt->execute()) { return 0; }

        // get result
        $res = $stmt->get_result();
        if ($res->num_rows === 0) { return 0; }
        $res = $res->fetch_assoc();
        $stmt->close();

        return $res["count"];
    }

    /**
     * Returns the amount of orders for a specific user
     * @param int $userId The id of the user.
     * @return int The amount of orders.
     */
    public static function getAmountForUser(int $userId): int
    {
        $stmt = getDB()->prepare("SELECT COUNT(DISTINCT id) AS count FROM `order` WHERE user = ?;");
        $stmt->bind_param("i", $userId);

        if (!$stmt->execute()) { return 0; }

        // get result
        $res = $stmt->get_result();
        if ($res->num_rows === 0) { return 0; }
        $res = $res->fetch_assoc();
        $stmt->close();

        return $res["count"];
    }

    /**
     * Gets all orders for a users from an offset to a limit.
     * @param int $userId The id of the user.
     * @param int $offset The offset from where the first item should be selected.
     * @param int $amount The amount of items, which should be selected.
     * @return array|null An array the selected {@link Order}s or null, if no order was found.
     * @throws Exception If it is not possible to convert a string to a datetime object.
     */
    public static function getAllForUserInRange(int $userId, int $offset, int $amount): ?array
    {
        $stmt = getDB()->prepare("SELECT * FROM `order` WHERE user = ? ORDER BY orderDate DESC limit ? OFFSET ?;");
        $stmt->bind_param("iii", $userId, $amount, $offset);
        if (!$stmt->execute()) { return null; }

        // get result
        $res = $stmt->get_result();
        if ($res->num_rows === 0) { return null; }

        $arr = array();
        while ($r = $res->fetch_assoc()) {
            $arr[] = new Order($r["id"], new DateTime($r["orderDate"]), new DateTime($r["deliveryDate"]), $r["paid"], $r["orderState"], $r["user"], $r["shippingAddress"]);
        }
        $stmt->close();

        return $arr;
    }


    /**
     * Gets all orders from an offset to a limit.
     * @param int $offset The offset from where the first item should be selected.
     * @param int $amount The amount of items, which should be selected.
     * @return array|null An array the selected {@link Order}s or null, if no order was found.
     * @throws Exception If it is not possible to convert a string to a datetime object.
     */
    public static function getAllInRange(int $offset, int $amount): ?array
    {
        $stmt = getDB()->prepare("SELECT * FROM `order` ORDER BY orderDate DESC limit ? OFFSET ?;");
        $stmt->bind_param("ii", $amount, $offset);
        if (!$stmt->execute()) { return null; }

        // get result
        $res = $stmt->get_result();
        if ($res->num_rows === 0) { return null; }

        $arr = array();
        while ($r = $res->fetch_assoc()) {
            $arr[] = new Order($r["id"], new DateTime($r["orderDate"]), new DateTime($r["deliveryDate"]), $r["paid"], $r["orderState"], $r["user"], $r["shippingAddress"]);
        }
        $stmt->close();

        return $arr;
    }

    /**
     * @return int The id of the object.
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return DateTime The datetime object for the order data.
     */
    public function getOrderDate(): DateTime
    {
        return $this->orderDate;
    }

    /**
     * @return string The formatted order date as a string in the format: d.m.Y H:i:s
     */
    public function getFormattedOrderDate(): string
    {
        if (isset($this->orderDate)) {
            return $this->orderDate->format("d.m.Y H:i:s");// TODO constant
        }
        return "Not Set";
    }

    /**
     * @return DateTime The datetime object for the delivery date.
     */
    public function getDeliveryDate(): DateTime
    {
        return $this->deliveryDate;
    }

    /**
     * @return string The formatted delivery date as a string in the format: d.m.Y H:i:s.
     */
    public function getFormattedDeliveryDate(): string
    {
        if (isset($this->deliveryDate)){
            return $this->deliveryDate->format("d.m.Y");// TODO constant
        }
        return "Not Set";
    }

    /**
     * @return bool true, if the order is paid
     */
    public function isPaid(): bool
    {
        return $this->paid;
    }

    /**
     * @return int The state id in which the order currently is.
     */
    public function getOrderStateId(): int
    {
        return $this->orderStateId;
    }

    // endregion

    /**
     * @return int The id of the user who placed this order
     */
    public function getUserId(): int
    {
        return $this->userId;
    }

    /**
     * @return int The id of the address, to which this order gets shipped.
     */
    public function getShippingAddressId(): int
    {
        return $this->shippingAddressId;
    }

    /**
     * @param int $orderStateId
     */
    public function setOrderStateId(int $orderStateId): void
    {
        $this->orderStateId = $orderStateId;
    }



    /**
     * Calls an insert statement for this object.
     * @throws Exception If it is not possible to convert a string to a datetime object.
     */
    public function insert(): ?Order
    {
        $stmt = getDB()->prepare("INSERT INTO `order`(orderDate, deliveryDate, paid, orderState, user, shippingAddress)
                                        VALUES (?, ?, ?, ?, ?, ?);");

        $orderDateString = $this->orderDate->format("Y-m-d H:i:s");
        $deliveryDateString = $this->deliveryDate->format("Y-m-d H:i:s");

        $stmt->bind_param("ssiiii",
            $orderDateString,
            $deliveryDateString,
            $this->paid,
            $this->orderStateId,
            $this->userId,
            $this->shippingAddressId
        );
        if (!$stmt->execute()) { return null; }     // TODO ERROR handling

        // get result
        $newId = $stmt->insert_id;
        $stmt->close();

        return self::getById($newId);
    }

    /**
     * Gets a specific order by his id.
     * @throws Exception If it is not possible to convert a string to a datetime object.
     */
    public static function getById(int $id): ?Order
    {
        $stmt = getDB()->prepare("SELECT * FROM `order` WHERE id = ?;");
        $stmt->bind_param("i", $id);
        if (!$stmt->execute()) { return null; }

        // get result
        $res = $stmt->get_result();
        if ($res->num_rows === 0) { return null; }
        $res = $res->fetch_assoc();
        $stmt->close();

        return new Order($id, new DateTime($res["orderDate"]), new DateTime($res["deliveryDate"]), $res["paid"], $res["orderState"], $res["user"], $res["shippingAddress"]);
    }

    /**
     * Updates an order inside the database
     * @throws Exception If it is not possible to cast the dates from the result to an DateTime object.
     */
    public function update(): ?Order
    {
        $stmt = getDB()->prepare("UPDATE `order` SET
                                          orderDate = ?,
                                          deliveryDate = ?,
                                          paid = ?,
                                          orderState = ?,
                                          user = ?,
                                          shippingAddress = ?");

        $orderDateString = $this->orderDate->format("Y-m-d H:i:s");
        $deliveryDateString = $this->deliveryDate->format("Y-m-d H:i:s");

        $stmt->bind_param("ssiiii",
            $orderDateString,
            $deliveryDateString,
            $this->paid,
            $this->orderStateId,
            $this->userId,
            $this->shippingAddressId);
        if (!$stmt->execute()) { return null; }     // TODO ERROR handling

        // get result
        $stmt->close();

        return self::getById($this->id);
    }

    /**
     * Deletes itself from the database.
     * @return bool true, if the order got deleted.
     * @throws Exception Will not be called
     */
    public function delete(): bool
    {
        $stmt = getDB()->prepare("DELETE FROM `order` WHERE id = ?;");
        $stmt->bind_param("i",
            $this->id);
        if (!$stmt->execute()) { return false; }

        $stmt->close();

        return self::getById($this->id) == null;
    }
}
