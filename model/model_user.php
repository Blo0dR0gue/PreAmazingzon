<!-- TODO COMMENT-->

<?php
// load required files
require_once(INCLUDE_DIR . DS . "database.inc.php");

// TODO implement
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

    // region getter

    public static function getByEmail(string $email): ?User
    {
        $stmt = getDB()->prepare("SELECT * from user where email = ?;");
        $stmt->bind_param("s", $email);
        if (!$stmt->execute()) return null;     // TODO ERROR handling

        // get result
        $res = $stmt->get_result();
        if ($res->num_rows === 0) return null;
        $res = $res->fetch_assoc();
        $stmt->close();

        return new User($res["id"], $res["firstname"], $res["lastname"], $res["email"], $res["password"], $res["active"], $res["userRole"], $res["defaultAddress"]);
    }

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

    // endregion

    // region setter

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

    // endregion

    /**
     * @param int|null $default_address_id
     */
    public function setDefaultAddressId(?int $default_address_id): void
    {
        $this->default_address_id = $default_address_id;
    }

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
        $stmt = getDB()->prepare("SELECT * from user where id = ?;");
        $stmt->bind_param("i", $id);
        if (!$stmt->execute()) return null;     // TODO ERROR handling

        // get result
        $res = $stmt->get_result();
        if ($res->num_rows === 0) return null;
        $res = $res->fetch_assoc();
        $stmt->close();

        return new User($id, $res["firstname"], $res["lastname"], $res["email"], $res["password"], $res["active"], $res["userRole"], $res["defaultAddress"]);
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

    public function delete(): void
    {
        // TODO
    }
}