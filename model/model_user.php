<!-- TODO COMMENT-->

<?php
// load required files
require_once(INCLUDE_DIR . DIRECTORY_SEPARATOR . "database.inc.php");

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
    private int $default_address_id;
    // endregion

    /**
     * @param int $id
     * @param string $first_name
     * @param string $last_name
     * @param string $email
     * @param string $password_hash
     * @param bool $active
     * @param int $role_id
     * @param int $default_address_id
     */
    public function __construct(int $id, string $first_name, string $last_name, string $email, string $password_hash, bool $active, int $role_id, int $default_address_id)
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
     * @return string
     */
    public function getLastName(): string
    {
        return $this->last_name;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @return string
     */
    public function getPasswordHash(): string
    {
        return $this->password_hash;
    }

    /**
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->active;
    }

    /**
     * @return int
     */
    public function getRoleId(): int
    {
        return $this->role_id;
    }

    /**
     * @return int
     */
    public function getDefaultAddressId(): int
    {
        return $this->default_address_id;
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

    /**
     * Get an existing user by its id.
     *
     * @param int $id ID of an user
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
}