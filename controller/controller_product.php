<?php

require_once MODEL_DIR . DIRECTORY_SEPARATOR . "model_product.php";

class ProductController
{

    public function getAllProducts(): array
    {
        return Product::getAllProducts();
    }

}