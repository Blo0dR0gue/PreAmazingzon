<?php
// TODO Comments

// load required files
require_once(INCLUDE_DIR . "database.inc.php");

class Category
{
    // region fields

    private int $id;
    private string $name;
    private string $description;
    private ?int $parentID;

    // endregion

    /**
     * Constructor of Category.
     * @param int $id
     * @param string $name
     * @param string $description
     * @param null|int $parentID
     */
    public function __construct(int $id, string $name, string $description, ?int $parentID)
    {
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
        $this->parentID = $parentID;
    }

    public static function getAll(): array
    {
        $categories = [];

        // No need for prepared statement, because we do not use inputs.
        $result = getDB()->query("SELECT id FROM category;");

        if (!$result) { return []; }

        $rows = $result->fetch_all(MYSQLI_ASSOC);

        foreach ($rows as $product) {
            $categories[] = self::getById($product["id"]);
        }

        return $categories;
    }

    public static function getById(?int $id): ?Category
    {
        if ($id == null) { return null; }

        $stmt = getDB()->prepare("SELECT * FROM category WHERE id = ?;");
        $stmt->bind_param("i", $id);
        if (!$stmt->execute()) { return null; }

        // get result
        $res = $stmt->get_result();
        if ($res->num_rows === 0) { return null; }
        $res = $res->fetch_assoc();
        $stmt->close();

        return new Category($id, $res["name"], $res["description"], $res["parent"]);
    }

    public static function getByName(string $name): ?Category
    {
        $stmt = getDB()->prepare("SELECT * FROM category WHERE name = ?;");
        $stmt->bind_param("s", $name);
        if (!$stmt->execute()) { return null; }

        // get result
        $res = $stmt->get_result();
        if ($res->num_rows === 0) { return null; }
        $res = $res->fetch_assoc();
        $stmt->close();

        return new Category($res["id"], $name, $res["description"], $res["parent"]);
    }

    public static function getPathToCategory(int $categoryID): string
    {
        $stmt = getDB()->prepare("with recursive tree as (
                       select *, id as root_category, concat('', name) as category_path
                       from category
                       where category.parent is null
                       union all
                       select c.*, p.root_category, concat(p.category_path, ' > ', c.name)
                       from category c
                                join tree p on c.parent = p.id
                   )
                    select t.root_category,
                           t.category_path
                    from tree t
                    WHERE t.id = ?;");
        $stmt->bind_param("i", $categoryID);
        if (!$stmt->execute()) { return ""; }    // TODO ERROR handling

        // get result
        $res = $stmt->get_result();
        if ($res->num_rows === 0) { return ""; }
        $res = $res->fetch_assoc();
        $stmt->close();

        return $res["category_path"];
    }

    public static function getCategoryTree(): array
    {
        $categoryIDs = [];

        $stmt = getDB()->prepare("with recursive tree as (
                       select *, id as root_category, concat('', name) as category_path
                       from category
                       where category.parent is null
                       union all
                       select c.*, p.root_category, concat(p.category_path, ' > ', c.name)
                       from category c
                                join tree p on c.parent = p.id
                   )
                    select t.id as top,
                           t.root_category as root,
                           t.category_path as path
                    from tree t
                    ORDER BY t.category_path;");

        if (!$stmt->execute()) { return []; }     // TODO ERROR handling

        // get result
        $res = $stmt->get_result();
        if ($res->num_rows === 0) { return []; }

        $rows = $res->fetch_all(MYSQLI_ASSOC);

        foreach ($rows as $categoryTree) {
            $categoryIDs[] = $categoryTree;
        }

        return $categoryIDs;
    }

    /**
     * Returns the amounts of categories stored in the database using a filter, if it is defined.
     * @param string|null $searchString Filter used to test, if the passed string is in the description, the title of a category.
     * @return int  The amount of found categories.
     */
    public static function getAmountOfCategories(?string $searchString): int
    {
        if (isset($searchString)) {
            $searchFilter = strtolower($searchString);  // TODO unused?
            $searchString = "%$searchString%";
            $stmt = getDB()->prepare("SELECT COUNT(DISTINCT id) as count FROM category AS c WHERE LOWER(c.description) LIKE ? OR LOWER(c.name) LIKE ?;");

            $stmt->bind_param("sss", $searchString, $searchString, $searchString);
        } else {
            $stmt = getDB()->prepare("SELECT COUNT(DISTINCT id) as count FROM category;");
        }

        if (!$stmt->execute()) { return 0; }

        // get result
        $res = $stmt->get_result();
        if ($res->num_rows === 0) { return 0; }
        $res = $res->fetch_assoc();
        $stmt->close();

        return $res["count"];
    }

    /**
     * Select a specified amount of categories starting at an offset.
     * @param int $offset The first category, which should be selected.
     * @param int $amount The amount of categories, which should be selected.
     * @return array|null An array with the found categories or null, if an error occurred.
     */
    public static function getCategoriesInRange(int $offset, int $amount): ?array
    {
        $categories = [];

        $stmt = getDB()->prepare("SELECT id FROM category ORDER BY id LIMIT ? OFFSET ?;");
        $stmt->bind_param("ii", $amount, $offset);
        if (!$stmt->execute()) { return null; }

        // get result
        foreach ($stmt->get_result() as $category) {
            $categories[] = self::getByID($category["id"]);
        }
        $stmt->close();
        return $categories;
    }

    // region getter

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @return null|int
     */
    public function getParentID(): ?int
    {
        return $this->parentID;
    }

    // endregion

    public function insert(): void
    {
        // TODO
    }

    public function update(): void
    {
        // TODO
    }

    public function delete(): void
    {
        // TODO
    }
}
