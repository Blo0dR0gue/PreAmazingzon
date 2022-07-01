<?php

//Add the required category model
require_once MODEL_DIR . 'model_category.php';

class CategoryController
{

    /**
     * Get all {@link Category}s
     * @return array A array with all {@link Category}s.
     */
    public static function getAll(): array
    {
        return Category::getAll();
    }

    /**
     * Gets a name of a category by its id.
     * @param int|null $id The id.
     * @return string The name of the category. (Can be empty if no category got found)
     */
    public static function getNameById(?int $id): string
    {
        if (!isset($id)) { return "No Category"; }
        return self::getById($id)->getName();
    }

    /**
     * Gets a {@link Category} by its id.
     * @param int|null $id The id.
     * @return Category|null The {@link Category} object or null, if not found.
     */
    public static function getById(?int $id): ?Category
    {
        return Category::getById($id);
    }

    /**
     * Gets a {@link Category} by its name.
     * @param string $name The name.
     * @return Category|null The {@link Category} object or null, if not found.
     */
    public static function getByName(string $name): ?Category
    {
        return Category::getByName($name); //TODO error handling; validation
    }

    /**
     * Gets all subcategories for a {@link Category}.
     * @param int $superId The id of the category.
     * @return array|null A array with all sub {@link Category} objects or null, if an error occurred.
     */
    public static function getSubCategories(int $superId): ?array
    {
        if($superId != -1){
            return Category::getSubCategories($superId);
        } else {
            return Category::getSubCategories(null);
        }
    }

    /**
     * Gets the full path to a category for a product.
     * @param Product $product The {@link Product}.
     * @return string The category path.
     */
    public static function getCategoryPathAsString(Product $product): string    // TODO not used?
    {
        return implode(" > ", self::getCategoryPathByProduct($product));
    }

    /**
     * Gets the full path to a category for a product.
     * @param Product $product The {@link Product}
     * @return array A array with each {@link Category}. Root category is on index 0.
     */
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

    /**
     * Gets a specific amount categories from an offset out of the database.
     * @param int $offset The offset from where to select.
     * @param int $amount The amount of categories, which should be selected.
     * @return array
     */
    public static function getCategoriesInRange(int $offset = 0, int $amount = 8): array
    {
        return Category::getCategoriesInRange($offset, $amount);
    }

    /**
     * Gets a path to a specific category
     * @param int|null $categoryID The id of the category
     * @return string The path to this category. From root to $categoryID
     */
    public static function getPathToCategory(?int $categoryID): string
    {
        if (!isset($categoryID)) { return ""; }
        return Category::getPathToCategory($categoryID);
    }

    /**
     * Gets the full tree for all categories. <br>
     * Each entry has the key "root", "top" and "path" <br>
     * "root" is the lowest point of the subtree. <br>
     * "top" is the head of the subtree. <br>
     * "path" is the full path for this subtree.
     * @return array An array witch each subtree.
     */
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

    /**
     * Updates an {@link Category} object
     * @param Category $category The {@link Category} object, which should be updated.
     * @param string $title The new title.
     * @param string $description The new description.
     * @param int|null $parent The new parent category.
     * @return Category|null The updated {@link Category} or null, if an error occurred.
     */
    public static function update(Category $category, string $title, string $description, ?int $parent): ?Category
    {
        $category->setName(htmlspecialchars($title, ENT_QUOTES, 'UTF-8'));
        $category->setDescription(htmlspecialchars($description, ENT_QUOTES, 'UTF-8'));
        $category->setParentID($parent==-1?null:$parent);

        return $category->update();
    }

    /**
     * Inserts an new {@link Category}.
     * @param string $title The title.
     * @param string $description The description
     * @param int|null $parent The parent root element or null
     * @return Category|null
     */
    public static function insert(string $title, string $description, ?int $parent): ?Category
    {
        $category = new Category(
            0,
            htmlspecialchars($title, ENT_QUOTES, 'UTF-8'),
            htmlspecialchars($description, ENT_QUOTES, 'UTF-8'),
            $parent==-1?null:$parent
        );

        return $category->insert();
    }

}