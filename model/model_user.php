<?php
//TODO Comments

// load required files
require_once(INCLUDE_DIR . "database.inc.php");

class User
{
    // region fields
    private int $id;
    private string $firstName;
    private string $lastName;
    private string $email;
    private string $passwordHash;
    private bool $active;
    private int $roleId;
    private ?int $defaultAddressId;   //Default Address can be null
    // endregion

    /**
     * @param int $id
     * @param string $firstName
     * @param string $lastName
     * @param string $email
     * @param string $passwordHash
     * @param bool $active
     * @param int $roleId
     * @param int|null $defaultAddressId
     */
    public function __construct(int $id, string $firstName, string $lastName, string $email, string $passwordHash, bool $active, int $roleId, ?int $defaultAddressId)
    {
        $this->id = $id;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->email = $email;
        $this->passwordHash = $passwordHash;
        $this->active = $active;
        $this->roleId = $roleId;
        $this->defaultAddressId = $defaultAddressId;
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
        return $this->firstName;
    }

    /**
     * @param string $firstName
     */
    public function setFirstName(string $firstName): void
    {
        $this->firstName = $firstName;
    }

    /**
     * @return string
     */
    public function getLastName(): string
    {
        return $this->lastName;
    }

    /**
     * @param string $lastName
     */
    public function setLastName(string $lastName): void
    {
        $this->lastName = $lastName;
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
        return $this->passwordHash;
    }

    /**
     * @param string $passwordHash
     */
    public function setPasswordHash(string $passwordHash): void
    {
        $this->passwordHash = $passwordHash;
    }

    public function getFormattedName(): string
    {
        return $this->firstName . " " . $this->lastName;
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
        return $this->roleId;
    }

    /**
     * @param int $roleId
     */
    public function setRoleId(int $roleId): void
    {
        $this->roleId = $roleId;
    }

    /**
     * @return null|int
     */
    public function getDefaultAddressId(): ?int
    {
        return $this->defaultAddressId;
    }

    /**
     * @param int|null $defaultAddressId
     */
    public function setDefaultAddressId(?int $defaultAddressId): void
    {
        $this->defaultAddressId = $defaultAddressId;
    }
    // endregion

    public function insert(): ?User
    {
        $stmt = getDB()->prepare("INSERT INTO user(password, email, userRole, firstname, lastname, defaultAddress, active) 
                                        VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssissii",
            $this->passwordHash,
            $this->email,
            $this->roleId,
            $this->firstName,
            $this->lastName,
            $this->defaultAddressId,
            $this->active);
        if (!$stmt->execute()) { return null; }    // TODO ERROR handling

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
        if (!$stmt->execute()) { return null; }

        // get result
        $res = $stmt->get_result();
        if ($res->num_rows === 0) { return null; }
        $res = $res->fetch_assoc();
        $stmt->close();

        return new User($id, $res["firstname"], $res["lastname"], $res["email"], $res["password"], $res["active"], $res["userRole"], $res["defaultAddress"]);
    }

    public static function getByEmail(string $email): ?User
    {
        $stmt = getDB()->prepare("SELECT * FROM user WHERE email = ?;");
        $stmt->bind_param("s", $email);
        if (!$stmt->execute()) { return null; }

        // get result
        $res = $stmt->get_result();
        if ($res->num_rows === 0) { return null; }
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
            $this->passwordHash,
            $this->email,
            $this->roleId,
            $this->firstName,
            $this->lastName,
            $this->defaultAddressId,
            $this->active,
            $this->id);
        if (!$stmt->execute()) { return null; }     // TODO ERROR handling

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

        if (!$stmt->execute()) { return 0; }

        // get result
        $res = $stmt->get_result();
        if ($res->num_rows === 0) { return 0; }
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
        if (!$stmt->execute()) { return null; }

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
        if (!$stmt->execute()) { return false; }

        $stmt->close();

        return self::getById($this->id) == null;
    }
}