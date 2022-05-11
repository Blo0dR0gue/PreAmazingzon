<!-- TODO COMMENT-->

<?php
// load required files
require_once(INCLUDE_DIR . DIRECTORY_SEPARATOR . "database.inc.php");

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
