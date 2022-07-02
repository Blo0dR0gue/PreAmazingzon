<?php

//Add database
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
     * Constructor for an order.
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

    // region getter

    /**
     * Gets the amount of orders.
     * @return int The amount of orders.
     */
    public static function getAmount(): int
    {
        $stmt = getDB()->prepare("SELECT COUNT(DISTINCT id) AS count FROM `order`;");

        if (!$stmt->execute()) {
            logData("Order Model", "Query execute error!", LOG_CRITICAL);
            return 0;
        }

        // get result
        $res = $stmt->get_result();
        if ($res->num_rows === 0) {
            logData("Order Model", "No Items were found! Amount is 0.", LOG_NOTICE);
            return 0;
        }
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

        if (!$stmt->execute()) {
            logData("Order Model", "Query execute error!", LOG_CRITICAL);
            return 0;
        }

        // get result
        $res = $stmt->get_result();
        if ($res->num_rows === 0) {
            logData("Order Model", "No Items were found for user " . $userId . "!", LOG_NOTICE);
            return 0;
        }
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
        if (!$stmt->execute()) {
            logData("Order Model", "Query execute error!", LOG_CRITICAL);
            return null;
        }

        // get result
        $res = $stmt->get_result();
        if ($res->num_rows === 0) {
            logData("Order Model", "No Items were found for user " . $userId . "!", LOG_NOTICE);
            return null;
        }

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
        if (!$stmt->execute()) {
            logData("Order Model", "Query execute error!", LOG_CRITICAL);
            return null;
        }

        // get result
        $res = $stmt->get_result();
        if ($res->num_rows === 0) {
            logData("Order Model", "No Items were found.", LOG_NOTICE);
            return null;
        }

        $arr = array();
        while ($r = $res->fetch_assoc()) {
            $arr[] = new Order($r["id"], new DateTime($r["orderDate"]), new DateTime($r["deliveryDate"]), $r["paid"], $r["orderState"], $r["user"], $r["shippingAddress"]);
        }
        $stmt->close();

        return $arr;
    }

    /**
     * Gets a specific order by his id.
     * @return Order|null The {@link Order} stored inside the database or null, if not found.
     * @throws Exception If it is not possible to convert a string to a datetime object.
     */
    public static function getById(int $id): ?Order
    {
        $stmt = getDB()->prepare("SELECT * FROM `order` WHERE id = ?;");
        $stmt->bind_param("i", $id);
        if (!$stmt->execute()) {
            logData("Order Model", "Query execute error! (get)", LOG_CRITICAL);
            return null;
        }

        // get result
        $res = $stmt->get_result();
        if ($res->num_rows === 0) {
            logData("Order Model", "No Items were found for id: " . $id, LOG_NOTICE);
            return null;
        }
        $res = $res->fetch_assoc();
        $stmt->close();

        return new Order($id, new DateTime($res["orderDate"]), new DateTime($res["deliveryDate"]), $res["paid"], $res["orderState"], $res["user"], $res["shippingAddress"]);
    }

    /**
     * Gets the id of the {@link Order} inside the database.
     * @return int The id of the object.
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Gets the create date of the {@link Order}.
     * @return DateTime The datetime object for the order data.
     */
    public function getOrderDate(): DateTime
    {
        return $this->orderDate;
    }

    /**
     * Gets the formatted create date of the {@link Order}.
     * @return string The formatted order date as a string in the format: d.m.Y H:i:s
     */
    public function getFormattedOrderDate(): string
    {
        if (isset($this->orderDate)) {
            return $this->orderDate->format(DATA_FORMAT);
        }
        return "Not Set";
    }

    /**
     * Gets the delivery date of the {@link Order}.
     * @return DateTime The datetime object for the delivery date.
     */
    public function getDeliveryDate(): DateTime
    {
        return $this->deliveryDate;
    }

    /**
     * Gets the formatted delivery date of the {@link Order}.
     * @return string The formatted delivery date as a string in the format: d.m.Y H:i:s.
     */
    public function getFormattedDeliveryDate(): string
    {
        if (isset($this->deliveryDate)) {
            return $this->deliveryDate->format(DATE_FORMAT_SHORT);
        }
        return "Not Set";
    }

    /**
     * Is the {@link Order} paid?
     * @return bool true, if the order is paid
     */
    public function isPaid(): bool
    {
        return $this->paid;
    }

    /**
     * Gets the {@link OrderState} id of the {@link Order}.
     * @return int The state id in which the order currently is.
     */
    public function getOrderStateId(): int
    {
        return $this->orderStateId;
    }

    // endregion

    /**
     * Gets the user id who created this order.
     * @return int The id of the {@link User} who placed this order
     */
    public function getUserId(): int
    {
        return $this->userId;
    }

    /**
     * Gets the id of the shipping address.
     * @return int The id of the {@link Address}, to which this order gets shipped.
     */
    public function getShippingAddressId(): int
    {
        return $this->shippingAddressId;
    }

    //endregion

    //region setter

    /**
     * Sets the state of the order.
     * @param int $orderStateId The id of the {@link OrderState}. (From the databse)
     */
    public function setOrderStateId(int $orderStateId): void
    {
        $this->orderStateId = $orderStateId;
    }

    /**
     * Sets the paid status of the order.
     * @param bool $paid Set it to true, if the order is paid.
     */
    public function setPaid(bool $paid): void
    {
        $this->paid = $paid;
    }

    //endregion

    /**
     * Creates an {@link Order} inside tha database.
     * @return Order|null The created {@link Order} or null, if an error occurred.
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
        if (!$stmt->execute()) {
            logData("Order Model", "Query execute error! (insert)", LOG_CRITICAL);
            return null;
        }

        // get result
        $newId = $stmt->insert_id;
        $stmt->close();

        return self::getById($newId);
    }

    /**
     * Updates an {@link Order} inside the database
     * @return Order|null The updated {@link Order} or null, if an error occurred.
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
        if (!$stmt->execute()) {
            logData("Order Model", "Query execute error! (update)", LOG_CRITICAL);
            return null;
        }

        // get result
        $stmt->close();

        return self::getById($this->id);
    }

    /**
     * Deletes an {@link Order} from the database.
     * @return bool true, if the {@link Order} got deleted.
     * @throws Exception Will not be called
     */
    public function delete(): bool
    {
        $stmt = getDB()->prepare("DELETE FROM `order` WHERE id = ?;");
        $stmt->bind_param("i",
            $this->id);
        if (!$stmt->execute()) {
            logData("Order Model", "Query execute error! (delete)", LOG_CRITICAL);
            return false;
        }

        $stmt->close();

        return self::getById($this->id) == null;
    }
}
