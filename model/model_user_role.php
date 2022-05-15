<!-- TODO COMMENT-->

<?php
// load required files
require_once(INCLUDE_DIR . DS . "database.inc.php");

class UserRole
{
    // region fields
    private int $id;
    private string $name;
    // endregion

    /**
     * Constructor for UserRole.
     * @param int $id
     * @param string $name
     */
    public function __construct(int $id, string $name)
    {
        $this->id = $id;
        $this->name = $name;
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
    // endregion

    /**
     * Get an existing UserRole by its id.
     * @param int $id ID of UserRole
     * @return UserRole|null found UserRole
     */
    public static function getById(int $id): ?UserRole
    {
        $stmt = getDB()->prepare("SELECT * from userrole where id = ?;");
        $stmt->bind_param("i", $id);
        if (!$stmt->execute()) return null;     // TODO ERROR handling

        // get result
        $res = $stmt->get_result();
        if ($res->num_rows === 0) return null;
        $res = $res->fetch_assoc();
        $stmt->close();

        return new UserRole($id, $res["name"]);
    }

    /**
     * Get an existing UserRole by its name.
     * @param string $name Name of Role
     * @return UserRole|null found UserRole
     */
    public static function getByName(string $name): ?UserRole
    {
        $stmt = getDB()->prepare("SELECT * from userrole where name = ?;");
        $stmt->bind_param("s", $name);
        if (!$stmt->execute()) return null;     // TODO ERROR handling

        // get result
        $res = $stmt->get_result();
        if ($res->num_rows === 0) return null;
        $res = $res->fetch_assoc();
        $stmt->close();

        return new UserRole($res["id"], $res["name"]);
    }
}

