<?php
//TODO Comments

// load required files
require_once(INCLUDE_DIR . "database.inc.php");

class User
{
    // region fields
    private int $id;
    private string $first_name;
    private string $last_name;
    private string $email;
    private string $password_hash;
    private bool $active;
    private int $role_id;
    private ?int $default_address_id;   //Default Address can be null
    // endregion

    /**
     * @param int $id
     * @param string $first_name
     * @param string $last_name
     * @param string $email
     * @param string $password_hash
     * @param bool $active
     * @param int $role_id
     * @param int|null $default_address_id
     */
    public function __construct(int $id, string $first_name, string $last_name, string $email, string $password_hash, bool $active, int $role_id, ?int $default_address_id)
    {
        $this->id = $id;
        $this->first_name = $first_name;
        $this->last_name = $last_name;
        $this->email = $email;
        $this->password_hash = $password_hash;
        $this->active = $active;
        $this->role_id = $role_id;
        $this->default_address_id = $default_address_id;
    }

    // region getter & setter

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
    public function getFirstName(): string
    {
        return $this->first_name;
    }

    /**
     * @param string $first_name
     */
    public function setFirstName(string $first_name): void
    {
        $this->first_name = $first_name;
    }

    /**
     * @return string
     */
    public function getLastName(): string
    {
        return $this->last_name;
    }

    /**
     * @param string $last_name
     */
    public function setLastName(string $last_name): void
    {
        $this->last_name = $last_name;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getPasswordHash(): string
    {
        return $this->password_hash;
    }

    /**
     * @param string $password_hash
     */
    public function setPasswordHash(string $password_hash): void
    {
        $this->password_hash = $password_hash;
    }

    public function getFormattedName(): string
    {
        return $this->first_name . " " . $this->last_name;
    }

    /**
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->active;
    }

    /**
     * @param bool $active
     */
    public function setActive(bool $active): void
    {
        $this->active = $active;
    }

    /**
     * @return int
     */
    public function getRoleId(): int
    {
        return $this->role_id;
    }

    /**
     * @param int $role_id
     */
    public function setRoleId(int $role_id): void
    {
        $this->role_id = $role_id;
    }

    /**
     * @return null|int
     */
    public function getDefaultAddressId(): ?int
    {
        return $this->default_address_id;
    }

    /**
     * @param int|null $default_address_id
     */
    public function setDefaultAddressId(?int $default_address_id): void
    {
        $this->default_address_id = $default_address_id;
    }
    // endregion

    public function insert(): ?User
    {
        $stmt = getDB()->prepare("INSERT INTO user(password, email, userRole, firstname, lastname, defaultAddress, active) 
                                        VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssissii",
            $this->password_hash,
            $this->email,
            $this->role_id,
            $this->first_name,
            $this->last_name,
            $this->default_address_id,
            $this->active);
        if (!$stmt->execute()) return null;     // TODO ERROR handling

        // get result
        $newId = $stmt->insert_id;
        $stmt->close();

        return self::getById($newId);
    }

    /**
     * Get an existing user by its id.
     *
     * @param int $id ID of a user
     * @return User|null new address
     */
    public static function getById(int $id): ?User
    {
        $stmt = getDB()->prepare("SELECT * FROM user WHERE id = ?;");
        $stmt->bind_param("i", $id);
        if (!$stmt->execute()) return null;

        // get result
        $res = $stmt->get_result();
        if ($res->num_rows === 0) return null;
        $res = $res->fetch_assoc();
        $stmt->close();

        return new User($id, $res["firstname"], $res["lastname"], $res["email"], $res["password"], $res["active"], $res["userRole"], $res["defaultAddress"]);
    }

    public static function getByEmail(string $email): ?User
    {
        $stmt = getDB()->prepare("SELECT * FROM user WHERE email = ?;");
        $stmt->bind_param("s", $email);
        if (!$stmt->execute()) return null;

        // get result
        $res = $stmt->get_result();
        if ($res->num_rows === 0) return null;
        $res = $res->fetch_assoc();
        $stmt->close();

        return new User($res["id"], $res["firstname"], $res["lastname"], $res["email"], $res["password"], $res["active"], $res["userRole"], $res["defaultAddress"]);
    }

    public function update(): ?User
    {
        $stmt = getDB()->prepare("UPDATE user 
                                        SET password = ?,
                                            email = ?,
                                            userRole = ?,
                                            firstname = ?,
                                            lastname = ?,
                                            defaultAddress = ?,
                                            active = ?
                                        WHERE id = ?;");
        $stmt->bind_param("ssissiii",
            $this->password_hash,
            $this->email,
            $this->role_id,
            $this->first_name,
            $this->last_name,
            $this->default_address_id,
            $this->active,
            $this->id);
        if (!$stmt->execute()) return null;     // TODO ERROR handling

        // get result
        $stmt->close();

        return self::getById($this->id);
    }


    /**
     * Returns the amounts of products stored in the database.
     * @return int The amount of users
     */
    public static function getAmountOfUsers(): int
    {
        $stmt = getDB()->prepare("SELECT COUNT(DISTINCT id) AS count FROM user;");

        if (!$stmt->execute()) return 0;

        // get result
        $res = $stmt->get_result();
        if ($res->num_rows === 0) return 0;
        $res = $res->fetch_assoc();
        $stmt->close();

        return $res["count"];
    }

    /**
     * Select a specified amount of products starting at an offset.
     * @param int $offset The first row, which should be selected.
     * @param int $amount The amount of rows, which should be selected.
     * @return array|null An array with the found products or null, if an error occurred.
     */
    public static function getUsersInRange(int $offset, int $amount): ?array
    {
        $users = [];

        $stmt = getDB()->prepare("SELECT id FROM user ORDER BY id LIMIT ? OFFSET ?;");
        $stmt->bind_param("ii", $amount, $offset);
        if (!$stmt->execute()) return null;

        // get result

        foreach ($stmt->get_result() as $user) {
            $users[] = self::getByID($user["id"]);
        }
        $stmt->close();
        return $users;
    }

    /**
     * Deletes itself from the database.
     * @return bool true, if the user got deleted.
     */
    public function delete(): bool
    {
        $stmt = getDB()->prepare("DELETE FROM user WHERE id = ?;");
        $stmt->bind_param("i",
            $this->id);
        if (!$stmt->execute()) return false;

        $stmt->close();

        return self::getById($this->id) == null;
    }
}