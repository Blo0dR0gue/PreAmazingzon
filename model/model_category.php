<?php

//Add database
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

    //region getter

    /**
     * Gets all categories stored inside the database.
     * @return array An array with all {@link Category} objects
     */
    public static function getAll(): array
    {
        $categories = [];

        // No need for prepared statement, because we do not use inputs.
        $result = getDB()->query("SELECT id FROM category;");

        if (!$result) {
            logData("Category Model", "No items were found!", NOTICE_LOG);
            return [];
        }

        $rows = $result->fetch_all(MYSQLI_ASSOC);

        foreach ($rows as $category) {
            $categories[] = self::getById($category["id"]);
        }

        return $categories;
    }

    /**
     * Gets a {@link Category} by its id.
     * @param int|null $id The id of the category.
     * @return Category|null The {@link Category} from the database or null, if not found.
     */
    public static function getById(?int $id): ?Category
    {
        if ($id == null) {
            return null;
        }

        $stmt = getDB()->prepare("SELECT * FROM category WHERE id = ?;");
        $stmt->bind_param("i", $id);
        if (!$stmt->execute()) {
            logData("Category Model", "Query execute error!", CRITICAL_LOG);
            return null;
        }

        // get result
        $res = $stmt->get_result();
        if ($res->num_rows === 0) {
            logData("Category Model", "No items were found for id: " . $id, NOTICE_LOG);
            return null;
        }
        $res = $res->fetch_assoc();
        $stmt->close();

        return new Category($id, $res["name"], $res["description"], $res["parent"]);
    }

    /**
     * Gets a {@link Category} by its name.
     * @param string $name The id of the category.
     * @return Category|null The {@link Category} from the database or null, if not found.
     */
    public static function getByName(string $name): ?Category
    {
        $stmt = getDB()->prepare("SELECT * FROM category WHERE name = ?;");
        $stmt->bind_param("s", $name);
        if (!$stmt->execute()) {
            logData("Category Model", "Query execute error!", CRITICAL_LOG);
            return null;
        }

        // get result
        $res = $stmt->get_result();
        if ($res->num_rows === 0) {
            logData("Category Model", "No items were found for name: " . $name, NOTICE_LOG);
            return null;
        }
        $res = $res->fetch_assoc();
        $stmt->close();

        return new Category($res["id"], $name, $res["description"], $res["parent"]);
    }

    /**
     * Gets the full path to a {@link Category} from the root {@link Category}.
     * @param int $categoryID The id of the searched category.
     * @return string The path string.
     */
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
        if (!$stmt->execute()) {
            logData("Category Model", "Query execute error! (get path)", CRITICAL_LOG);
            return "";
        }

        // get result
        $res = $stmt->get_result();
        if ($res->num_rows === 0) {
            return "";
        }
        $res = $res->fetch_assoc();
        $stmt->close();

        return $res["category_path"];
    }

    /**
     * Gets all sub {@link Category} objects for a specific {@link Category}
     * @param int|null $superId The id of the root {@link Category}.
     * @return array|null An array with all sub {@link Category} objects.
     */
    public static function getSubCategories(?int $superId): ?array
    {
        $categories = [];

        if ($superId) {
            $stmt = getDB()->prepare("SELECT id FROM category WHERE parent = ? ORDER BY id;");
            $stmt->bind_param("i",
                              $superId
            );
        } else {
            $stmt = getDB()->prepare("SELECT id FROM category WHERE parent IS NULL ORDER BY id;");
        }
        if (!$stmt->execute()) {
            logData("Category Model", "Query execute error! (get subs)", CRITICAL_LOG);
            return null;
        }

        // get result
        foreach ($stmt->get_result() as $category) {
            $categories[] = self::getByID($category["id"]);
        }
        $stmt->close();
        return $categories;
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

        if (!$stmt->execute()) {
            logData("Category Model", "Query execute error! (create tree)", CRITICAL_LOG);
            return [];
        }

        // get result
        $res = $stmt->get_result();
        if ($res->num_rows === 0) {
            logData("Category Model", "Tree could not be created. No Categories found!", NOTICE_LOG);
            return [];
        }

        $rows = $res->fetch_all(MYSQLI_ASSOC);

        foreach ($rows as $categoryTree) {
            $categoryIDs[] = $categoryTree;
        }

        return $categoryIDs;
    }

    /**
     * Returns the amounts of categories stored in the database using a filter, if it is defined.
     * @param string|null $searchString Filter used to test, if the passed string is in the description, the title of a category.
     * @return int The amount of found categories.
     */
    public static function getAmountOfCategories(?string $searchString): int
    {
        if (isset($searchString)) {
            $searchFilter = strtolower($searchString);
            $searchString = "%$searchFilter%";
            $stmt = getDB()->prepare("SELECT COUNT(DISTINCT id) as count FROM category AS c WHERE LOWER(c.description) LIKE ? OR LOWER(c.name) LIKE ?;");

            $stmt->bind_param("sss", $searchString, $searchString, $searchString);
        } else {
            $stmt = getDB()->prepare("SELECT COUNT(DISTINCT id) as count FROM category;");
        }

        if (!$stmt->execute()) {
            logData("Category Model", "Query execute error! (get amount by search)", CRITICAL_LOG);
            return 0;
        }

        // get result
        $res = $stmt->get_result();
        if ($res->num_rows === 0) {
            logData("Category Model", "No Items were found for search.", NOTICE_LOG);
            return 0;
        }
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
        if (!$stmt->execute()) {
            logData("Category Model", "Query execute error (get in range)!", CRITICAL_LOG);
            return null;
        }

        // get result
        foreach ($stmt->get_result() as $category) {
            $categories[] = self::getByID($category["id"]);
        }
        $stmt->close();
        return $categories;
    }

    // region getter & setter

    /**
     * Gets the database id.
     * @return int The id.
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Gets the name of the category.
     * @return string The name.
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Sets the name of the category.
     * @param string $name The name.
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * Gets the description of the category.
     * @return string The description
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    //endregion

    //region setter

    /**
     * Sets the description of the category.
     * @param string $description The description
     */
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    /**
     * Gets the parent category id.
     * @return null|int The id or null, if root.
     */
    public function getParentID(): ?int
    {
        return $this->parentID;
    }

    /**
     * Sets the parent category id.
     * @param int|null $parentID The parent id or null for root.
     */
    public function setParentID(?int $parentID): void
    {
        $this->parentID = $parentID;
    }

    // endregion

    /**
     * Creates a new {@link Category} inside the database.
     * @return Category|null The created {@link Category} or null, if an error occurred.
     */
    public function insert(): ?Category
    {
        $stmt = getDB()->prepare("INSERT INTO category(name, description, parent) 
                                        VALUES (?, ?, ?);");
        $stmt->bind_param("ssi",
                          $this->name,
                          $this->description,
                          $this->parentID
        );
        if (!$stmt->execute()) {
            logData("Category Model", "Query execute error! (insert)", CRITICAL_LOG);
            return null;
        }

        // get result
        $newId = $stmt->insert_id;
        $stmt->close();

        return self::getById($newId);
    }

    /**
     * Updates an {@link Category} inside the database.
     * @return Category|null The updated {@link Category} or null, if an error occurred.
     */
    public function update(): ?Category
    {
        $stmt = getDB()->prepare("UPDATE category 
                                        SET name = ?,
                                            description = ?,
                                            parent = ?
                                        WHERE id = ?;");
        $stmt->bind_param("ssii",
                          $this->name,
                          $this->description,
                          $this->parentID,
                          $this->id
        );
        if (!$stmt->execute()) {
            logData("Category Model", "Query execute error! (update)", CRITICAL_LOG);
            return null;
        }

        $stmt->close();

        return self::getById($this->id);
    }
}
