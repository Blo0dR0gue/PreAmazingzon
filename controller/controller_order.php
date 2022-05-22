<?php

require_once MODEL_DIR . DS . 'model_order.php';

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

    public static function insert(DateTime $orderDate, DateTime $deliveryDate, bool $paid, int $orderStateId, int $userId, int $shippingAddressId): ?Order{
        $order = new Order(0, $orderDate, $deliveryDate, $paid, $orderStateId, $userId, $shippingAddressId);
        try {
            return $order->insert();
        } catch (Exception $e) {
            //TODO error handling (datetime)
        }
        return null;
    }

    public static function calculateDeliveryDate(): ?DateTime {
        $dtZone = new DateTimeZone('Europe/Berlin');
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

}