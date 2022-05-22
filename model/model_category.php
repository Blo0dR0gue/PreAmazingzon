<!-- TODO COMMENT-->

<?php
// load required files
require_once(INCLUDE_DIR . DS . "database.inc.php");

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

        //No need for prepared statement, because we do not use inputs.
        $result = getDB()->query("SELECT id FROM Category;");

        if (!$result) return [];

        $rows = $result->fetch_all(MYSQLI_ASSOC);

        foreach ($rows as $product) {
            $categories[] = self::getById($product["id"]);
        }

        return $categories;
    }

    public static function getById(?int $id): ?Category
    {
        if ($id == null) return null;

        $stmt = getDB()->prepare("SELECT * from category where id = ?;");
        $stmt->bind_param("i", $id);
        if (!$stmt->execute()) return null;     // TODO ERROR handling

        // get result
        $res = $stmt->get_result();
        if ($res->num_rows === 0) return null;
        $res = $res->fetch_assoc();
        $stmt->close();

        return new Category($id, $res["name"], $res["description"], $res["parent"]);
    }

    public static function getByName(string $name): ?Category
    {
        $stmt = getDB()->prepare("SELECT * from category where name = ?;");
        $stmt->bind_param("s", $name);
        if (!$stmt->execute()) return null;     // TODO ERROR handling

        // get result
        $res = $stmt->get_result();
        if ($res->num_rows === 0) return null;
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
        if (!$stmt->execute()) return "";     // TODO ERROR handling

        // get result
        $res = $stmt->get_result();
        if ($res->num_rows === 0) return "";
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

        if (!$stmt->execute()) return [];     // TODO ERROR handling

        // get result
        $res = $stmt->get_result();
        if ($res->num_rows === 0) return [];

        $rows = $res->fetch_all(MYSQLI_ASSOC);

        foreach ($rows as $categoryTree) {
            $categoryIDs[] = $categoryTree;
        }

        return $categoryIDs;
    }

    public function getImg(): string
    {
        $images = glob(IMAGE_DIR . DS . "categories" . DS . $this->id . DS . "*");
        if (count($images) !== 0) return $images[0];

        return IMAGE_DIR . DS . "products" . DS . "notfound.jpg";
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
