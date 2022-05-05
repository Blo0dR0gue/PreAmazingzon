<?php

require_once MODEL_DIR . DIRECTORY_SEPARATOR . "model_product.php";

class ProductController
{

    public static function getAllProducts(): array
    {
        return Product::getAllProducts();
    }

    public static function getRandomProducts(int $amount = 4): array
    {
        return Product::getRandomProducts($amount);
    }

}