<?php

//Add the product order model.
require_once MODEL_DIR . "model_product_order.php";

class ProductOrderController
{

    /**
     * Gets an {@link ProductOrder} by its ids.
     * @param int $productId The id of the product.
     * @param int $orderId The id of the order.
     * @return ProductOrder|null The {@link ProductOrder} or null, if not found.
     */
    public static function getByIDs(int $productId, int $orderId): ?ProductOrder
    {
        return ProductOrder::getByIDs($productId, $orderId);
    }

    /**
     * Creates a new {@link ProductOrder}
     * @param int $productId The id of the product.
     * @param int $orderId The id of the order.
     * @param int $amount How many items for this product?
     * @param float $price The price for the product.
     * @return ProductOrder|null
     */
    public static function insert(int $productId, int $orderId, int $amount, float $price): ?ProductOrder
    {
        $productOrder = new ProductOrder($productId, $orderId, $amount, $price);
        return $productOrder->insert();
    }

    /**
     * Gets all {@link ProductOrder} by an order.
     * @param int $orderId The if of the order.
     * @return array An array with all {@link ProductOrder} for a specific order.
     */
    public static function getAllByOrder(int $orderId): array
    {
        return ProductOrder::getAllByOrder($orderId);
    }

}