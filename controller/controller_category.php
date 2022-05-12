<?php

require_once MODEL_DIR . DIRECTORY_SEPARATOR . 'model_category.php';

class CategoryController
{

    public static function getById(?int $id): ?Category {
        return Category::getById($id);  //TODO error handling; validation
    }

    public static function getNameById(?int $id): string {
        if(!isset($id)) return "No Category";
        return self::getById($id);
    }

    public static function getByName(string $name): ?Category {
        return Category::getByName($name); //TODO error handling; validation
    }

    public static function getCategoryPathAsString(Product $product): string
    {
        return implode(" > ", self::getCategoryPathByProduct($product));
    }

    public static function getCategoryPathByProduct(Product $product): array
    {
        $tmpProductCat = Category::getById($product->getCategoryID());

        if (!isset($tmpProductCat)) return [];
        $path = [];

        while ($tmpProductCat != null) {
            $path[] = $tmpProductCat->getName();
            $tmpProductCat = Category::getById($tmpProductCat->getParentID());
        }

        return array_reverse($path);
    }
}