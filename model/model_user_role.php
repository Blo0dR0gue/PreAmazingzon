<!-- TODO COMMENT-->

<?php
// load required files
require_once(INCLUDE_DIR . DIRECTORY_SEPARATOR . "database.inc.php");

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
     * @return UserRole found UserRole
     */
    public static function getById(int $id): UserRole
    {
        // TODO ERROR handling
        $stmt = getDB()->prepare("SELECT * from userrole where id = ?;");
        $stmt->bind_param("i", $id);
        $stmt->execute();

        $res = $stmt->get_result()->fetch_assoc();

        $stmt->close();

        return new UserRole($id, $res["name"]);
    }
}

