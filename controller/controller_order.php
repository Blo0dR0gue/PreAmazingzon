<?php
//TODO Comments

require_once MODEL_DIR . 'model_order.php';

class OrderController
{

    public static function getById(int $id): ?Order
    {
        try {
            return Order::getById($id);
        } catch (Exception $e) {
            //TODO datetime error
        }
        return null;
    }

    public static function insert(DateTime $orderDate, DateTime $deliveryDate, bool $paid, int $orderStateId, int $userId, int $shippingAddressId): ?Order
    {
        $order = new Order(0, $orderDate, $deliveryDate, $paid, $orderStateId, $userId, $shippingAddressId);
        try {
            return $order->insert();
        } catch (Exception $e) {
            //TODO error handling (datetime)
        }
        return null;
    }

    public static function calculateDeliveryDate(): ?DateTime
    {
        $dtZone = new DateTimeZone(DATE_TIME_ZONE);
        $dt = null;
        try {
            $dt = new DateTime('now', $dtZone);
            $dt->setTime(0, 0, 0, 0);
            //Now plus 10 days
            $dt->add(new DateInterval("P10D")); //TODO constant / random?
        } catch (Exception $e) {
            //TODO handle
        }
        return $dt;
    }

    public static function getAmountForUser(int $userId): int
    {
        return Order::getAmountForUser($userId);
    }

    public static function getAllForUserInRange(int $userId, int $offset): ?array
    {
        try {
            return Order::getAllForUserInRange($userId, $offset, LIMIT_OF_SHOWED_ITEMS);
        } catch (Exception $e) {
            //TODO handle DateTime error
        }
        return null;
    }

    public static function getAmountOfUsers(): int
    {
        return Order::getAmount();
    }

    public static function getAllInRange(int $offset, int $LIMIT_OF_SHOWED_ITEMS): ?array
    {
        try {
            return Order::getAllInRange($offset, $LIMIT_OF_SHOWED_ITEMS);
        } catch (Exception $e) {
            //TODO 500 error
            header("LOCATION: " . PAGES_DIR . 'page_error.php?errorCode=500');
            die();
        }
    }

    public static function delete(Order $order): bool
    {
        try {
            return $order->delete();
        } catch (Exception $e) {
            //TODO 500 error
            header("LOCATION: " . PAGES_DIR . 'page_error.php?errorCode=500');
            die();
        }
    }

}