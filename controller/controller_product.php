<?php

require_once MODEL_DIR . DIRECTORY_SEPARATOR . "model_product.php";
require_once MODEL_DIR . DIRECTORY_SEPARATOR . "model_category.php";

class ProductController
{

    public static function addNew(string $title, int $categoryID, string $description, float $price, float $shippingCost, int $stock): ?Product
    {
        $product = new Product(0, $title, $description, $price, $stock, $shippingCost, $categoryID);

        $product = $product->insert();

        if (!$product) return null;

        return $product;
    }

    public static function searchProducts(string $search): array
    {
        return Product::searchProducts($search);
    }

    public static function getAllProducts(): array
    {
        return Product::getAllProducts();
    }

    public static function getProductsInRange(int $offset = 0, int $amount = 8): array
    {
        return Product::getProductsInRange($offset, $amount);
    }

    public static function getRandomProducts(int $amount = 4): array
    {
        return Product::getRandomProducts($amount);
    }

    public static function getByID(int $productID): ?Product
    {
        if ($productID == null || $productID == 0)
            return null;

        return Product::getByID($productID);

    }

}