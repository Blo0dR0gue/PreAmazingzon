<?php
//Add order model.
require_once MODEL_DIR . 'model_order.php';

class OrderController
{

    /**
     * Gets an {@link Order} by its id.
     * @param int $id The id.
     * @return Order|null A {@link Order} object or null, if not found.
     */
    public static function getById(int $id): ?Order
    {
        try {
            return Order::getById($id);
        } catch (Exception $e) {
            logData("Order Controller", "Date could not be parsed! (get by id)", CRITICAL_LOG, $e->getTrace());
            header("LOCATION: " . PAGES_DIR . 'page_error.php?errorCode=500');
            die();
        }
    }

    /**
     * Calculates the delivery date. We add 10 days to the order date.
     * @return DateTime|null A new <a href="psi_element://DateTime">DateTime</a> object or null, if an error occurred.
     */
    public static function calculateDeliveryDate(): ?DateTime
    {
        $dtZone = new DateTimeZone(DATE_TIME_ZONE);
        $dt = null; // TODO unused
        try {
            $dt = new DateTime('now', $dtZone);
            $dt->setTime(0, 0, 0, 0);
            //Now plus 10 days
            $dt->add(new DateInterval("P10D"));
            return $dt;
        } catch (Exception $e) {
            logData("Order Controller", "Date could not be parsed! (calculate delivery date)", CRITICAL_LOG, $e->getTrace());
            header("LOCATION: " . PAGES_DIR . 'page_error.php?errorCode=500');
            die();
        }
    }

    /**
     * The amount of orders for a specific user.
     * @param int $userId The id of the user.
     * @return int The amount of orders.
     */
    public static function getAmountForUser(int $userId): int
    {
        return Order::getAmountForUser($userId);
    }

    /**
     * Gets all order for a user in a specific range.
     * @param int $userId The id of the user.
     * @param int $offset The offset from where to select from the database
     * @return array|null
     */
    public static function getAllForUserInRange(int $userId, int $offset): ?array
    {
        try {
            return Order::getAllForUserInRange($userId, $offset, LIMIT_OF_SHOWED_ITEMS);
        } catch (Exception $e) {
            logData("Order Controller", "Date could not be parsed! (get for user)", CRITICAL_LOG, $e->getTrace());
            header("LOCATION: " . PAGES_DIR . 'page_error.php?errorCode=500');
            die();
        }
    }

    /**
     * Gets the amount of all orders.
     * @return int The total amount of orders.
     */
    public static function getAmountOfUsers(): int
    {
        return Order::getAmount();
    }

    /**
     * Get all orders in a specific range.
     * @param int $offset The offset from where to select from the database
     * @param int $LIMIT_OF_SHOWED_ITEMS The amount of items, which should be selected.
     * @return array|null
     */
    public static function getAllInRange(int $offset, int $LIMIT_OF_SHOWED_ITEMS): ?array
    {
        try {
            return Order::getAllInRange($offset, $LIMIT_OF_SHOWED_ITEMS);
        } catch (Exception $e) {
            logData("Order Controller", "Date could not be parsed! (get in range)", CRITICAL_LOG, $e->getTrace());
            header("LOCATION: " . PAGES_DIR . 'page_error.php?errorCode=500');
            die();
        }
    }

    /**
     * Creates a new {@link Order}.
     * @param DateTime $orderDate The date of the order.
     * @param DateTime $deliveryDate The delivery date for the order.
     * @param bool $paid Is the order paid?
     * @param int $orderStateId The state id for this order.
     * @param int $userId The id of the user who created the order.
     * @param int $shippingAddressId The id of the shipping address.
     * @return Order|null A new {@link Order} object or null, if an error occurred.
     */
    public static function insert(DateTime $orderDate, DateTime $deliveryDate, bool $paid, int $orderStateId, int $userId, int $shippingAddressId): ?Order
    {
        $order = new Order(0, $orderDate, $deliveryDate, $paid, $orderStateId, $userId, $shippingAddressId);
        try {
            return $order->insert();
        } catch (Exception $e) {
            logData("Order Controller", "Date could not be parsed! (insert)", CRITICAL_LOG, $e->getTrace());
            header("LOCATION: " . PAGES_DIR . 'page_error.php?errorCode=500');
            die();
        }
    }

    /**
     * Deletes an order.
     * @param Order $order The order, which should be deleted.
     * @return bool true, if it was successfully.
     */
    public static function delete(Order $order): bool
    {
        try {
            return $order->delete();
        } catch (Exception $e) {
            logData("Order Controller", "Date could not be parsed! (delete)", CRITICAL_LOG, $e->getTrace());
            header("LOCATION: " . PAGES_DIR . 'page_error.php?errorCode=500');
            die();
        }
    }
}