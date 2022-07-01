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
        if (!isset($id)) { return "No Category"; }
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

        if (!isset($tmpProductCat)) { return []; }
        $path = [];

        while ($tmpProductCat != null) {
            $path[] = $tmpProductCat->getName();
            $tmpProductCat = Category::getById($tmpProductCat->getParentID());
        }

        return array_reverse($path);
    }

    public static function getCategoriesInRange(int $offset = 0, int $amount = 8): array
    {
        return Category::getCategoriesInRange($offset, $amount);
    }

    public static function getPathToCategory(?int $categoryID): string
    {
        if (!isset($categoryID)) { return ""; }
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

    public static function update(Category $category, string $title, string $description, int $parent): ?Category
    {
        $category->setName(htmlspecialchars($title, ENT_QUOTES, 'UTF-8'));
        $category->setDescription(htmlspecialchars($description, ENT_QUOTES, 'UTF-8'));
        $category->setParentID($parent==-1?null:$parent);

        return $category->update();
    }

    public static function insert(string $title, string $description, ?int $parent): ?Category
    {
        // TODO validation
        $category = new Category(
            0,
            htmlspecialchars($title, ENT_QUOTES, 'UTF-8'),
            htmlspecialchars($description, ENT_QUOTES, 'UTF-8'),
            $parent==-1?null:$parent
        );

        $category = $category->insert();

        return $category;
    }

}