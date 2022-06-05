<?php
//TODO Comments

require_once MODEL_DIR . 'model_category.php';

class CategoryController
{
    public static function getAll(): array
    {
        return Category::getAll();
    }

    public static function getNameById(?int $id): string
    {
        if (!isset($id)) return "No Category";
        return self::getById($id)->getName();
    }

    public static function getById(?int $id): ?Category
    {
        return Category::getById($id);
    }

    public static function getByName(string $name): ?Category
    {
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

    public static function getPathToCategoryL(?int $categoryID): string
    {
        if (!isset($categoryID)) return "";
        return Category::getPathToCategory($categoryID);
    }

    public static function getCategoryTree(): array
    {
        return Category::getCategoryTree();
    }

    /**
     * Returns the amounts of categories stored in the database using a filter, if it is defined.
     * @param string|null $searchFilter Filter used to test, if the passed string is in the description or the name of a category.
     * @return int  The amount of found categories.
     */
    public static function getAmountOfCategories(?string $searchFilter): int
    {
        return Category::getAmountOfCategories($searchFilter);
    }

}