<?php

require_once MODEL_DIR . DS . "model_product_order.php";

class ProductOrderController
{

    public static function getByIDs(int $productId, int $orderId): ?ProductOrder {
        return ProductOrder::getByIDs($productId, $orderId);
    }

    public static function insert(int $productId, int $orderId, int $amount, int $price): ?ProductOrder {
        $productOrder = new ProductOrder($productId, $orderId, $amount, $price);
        return $productOrder->insert();
    }

}