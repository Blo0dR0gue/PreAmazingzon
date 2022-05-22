<?php

require_once MODEL_DIR . DS . 'model_order.php';

class OrderController
{

    public static function getById(int $id): ?Order
    {
        return Order::getById($id);
    }

    public static function insert(DateTime $orderDate, DateTime $deliveryDate, bool $paid, int $orderStateId, int $userId, int $shippingAddressId): ?Order{
        $order = new Order(0, $orderDate, $deliveryDate, $paid, $orderStateId, $userId, $shippingAddressId);
        return $order->insert();
    }

}