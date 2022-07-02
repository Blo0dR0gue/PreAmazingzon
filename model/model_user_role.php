<?php

//Add database
require_once(INCLUDE_DIR . "database.inc.php");

class UserRole
{
    // region fields
    private int $id;
    private string $name;
    // endregion

    /**
     * Constructor for {@link UserRole}.
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
     * Get an existing {@link UserRole} by its id.
     * @param int $id The id.
     * @return UserRole|null The {@link UserRole} or null, if not found.
     */
    public static function getById(int $id): ?UserRole
    {
        $stmt = getDB()->prepare("SELECT * FROM userrole WHERE id = ?;");
        $stmt->bind_param("i", $id);
        if (!$stmt->execute()) {
            logData("User Role Model", "Query execute error! (get)", LOG_LVL_CRITICAL);
            return null;
        }

        // get result
        $res = $stmt->get_result();
        if ($res->num_rows === 0) {
            return null;
        }
        $res = $res->fetch_assoc();
        $stmt->close();

        return new UserRole($id, $res["name"]);
    }

    /**
     * Get an existing {@link UserRole} by its name.
     * @param string $name The name
     * @return UserRole|null The {@link UserRole} or null, if not found.
     */
    public static function getByName(string $name): ?UserRole
    {
        $stmt = getDB()->prepare("SELECT * FROM userrole WHERE name = ?;");
        $stmt->bind_param("s", $name);
        if (!$stmt->execute()) {
            logData("User Role Model", "Query execute error! (get)", LOG_LVL_CRITICAL);
            return null;
        }

        // get result
        $res = $stmt->get_result();
        if ($res->num_rows === 0) {
            return null;
        }
        $res = $res->fetch_assoc();
        $stmt->close();

        return new UserRole($res["id"], $res["name"]);
    }

    /**
     * Gets the id of this {@link UserRole}.
     * @return int The id.
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Gets the name of this {@link UserRole}.
     * @return string The name.
     */
    public function getName(): string
    {
        return $this->name;
    }

    // endregion
}

