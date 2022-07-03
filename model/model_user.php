<?php
// Add database
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
    private ?int $defaultAddressId;   // Default Address can be null
    // endregion

    /**
     * Constructor for {@link User}.
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
     * Gets an {@link User} by its e-mail.
     * @param string $email The e-mail.
     * @return User|null The {@link User} or null, if not found.
     */
    public static function getByEmail(string $email): ?User
    {
        $stmt = getDB()->prepare("SELECT * FROM user WHERE email = ?;");
        $stmt->bind_param("s", $email);
        if (!$stmt->execute()) {
            logData("User Model", "Query execute error! (get)", CRITICAL_LOG);
            return null;
        }

        // get result
        $res = $stmt->get_result();
        if ($res->num_rows === 0) {
            return null;
        }
        $res = $res->fetch_assoc();
        $stmt->close();

        return new User($res["id"], $res["firstname"], $res["lastname"], $res["email"], $res["password"], $res["active"], $res["userRole"], $res["defaultAddress"]);
    }

    /**
     * Returns the amounts of products stored in the database.
     * @return int The amount of users
     */
    public static function getAmountOfUsers(): int
    {
        $stmt = getDB()->prepare("SELECT COUNT(DISTINCT id) AS count FROM user;");

        if (!$stmt->execute()) {
            logData("User Model", "Query execute error! (get)", CRITICAL_LOG);
            return 0;
        }

        // get result
        $res = $stmt->get_result();
        if ($res->num_rows === 0) {
            return 0;
        }
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
        if (!$stmt->execute()) {
            logData("User Model", "Query execute error! (get)", CRITICAL_LOG);
            return null;
        }

        // get result
        foreach ($stmt->get_result() as $user) {
            $users[] = self::getByID($user["id"]);
        }
        $stmt->close();
        return $users;
    }

    /**
     * Get {@link User} by its id.
     * @param int $id ID of a user
     * @return User|null The {@link User} or null, if not found.
     */
    public static function getById(int $id): ?User
    {
        $stmt = getDB()->prepare("SELECT * FROM user WHERE id = ?;");
        $stmt->bind_param("i", $id);
        if (!$stmt->execute()) {
            logData("User Model", "Query execute error! (get)", CRITICAL_LOG);
            return null;
        }

        // get result
        $res = $stmt->get_result();
        if ($res->num_rows === 0) {
            return null;
        }
        $res = $res->fetch_assoc();
        $stmt->close();

        return new User($id, $res["firstname"], $res["lastname"], $res["email"], $res["password"], $res["active"], $res["userRole"], $res["defaultAddress"]);
    }

    /**
     * Gets the id of the {@link User}.
     * @return int The id.
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Gets the firstname of the {@link User}.
     * @return string The firstname.
     */
    public function getFirstName(): string
    {
        return $this->firstName;
    }

    /**
     * Sets the firstname for the {@link User}.
     * @param string $firstName The firstname.
     */
    public function setFirstName(string $firstName): void
    {
        $this->firstName = $firstName;
    }

    /**
     * Gets the lastname of the {@link User}.
     * @return string The lastname.
     */
    public function getLastName(): string
    {
        return $this->lastName;
    }

    /**
     * Sets the lastname for the {@link User}.
     * @param string $lastName The lastname.
     */
    public function setLastName(string $lastName): void
    {
        $this->lastName = $lastName;
    }

    /**
     * Gets the e-mail-address of the {@link User}.
     * @return string The e-mail.
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * Sets the e-mail-address of the {@link User}.
     * @param string $email The e-mail-
     */
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    /**
     * Gets the password hash of the {@link User}.
     * @return string The hash.
     */
    public function getPasswordHash(): string
    {
        return $this->passwordHash;
    }

    /**
     * Sets the password hash for the {@link User}
     * @param string $passwordHash
     */
    public function setPasswordHash(string $passwordHash): void
    {
        $this->passwordHash = $passwordHash;
    }


    /**
     * Gets the formatted {@link User} name. (Firstname Lastname)
     * @return string
     */
    public function getFormattedName(): string
    {
        return $this->firstName . " " . $this->lastName;
    }

    /**
     * Is the {@link User} active?
     * @return bool True, if the {@link User} is active.
     */
    public function isActive(): bool
    {
        return $this->active;
    }

    /**
     * Sets the active status of the {@link User}.
     * @param bool $active Set it to true, if the user should be active.
     */
    public function setActive(bool $active): void
    {
        $this->active = $active;
    }

    /**
     * Gets the id of the {@link UserRole} of the {@link User}.
     * @return int The role id.
     */
    public function getRoleId(): int
    {
        return $this->roleId;
    }

    /**
     * Sets {@link UserRole} for the {@link User}
     * @param int $roleId The id of the {@link UserRole}
     */
    public function setRoleId(int $roleId): void
    {
        $this->roleId = $roleId;
    }

    /**
     * Gets the default {@link Address} is of the {@link User}.
     * @return null|int The default {@link Address} id or null, if the user do not have a default address.
     */
    public function getDefaultAddressId(): ?int
    {
        return $this->defaultAddressId;
    }

    /**
     * Sets the default address for the {@link User}.
     * @param int|null $defaultAddressId The id of the {@link Address}
     */
    public function setDefaultAddressId(?int $defaultAddressId): void
    {
        $this->defaultAddressId = $defaultAddressId;
    }

    // endregion

    /**
     * Creates a new {@link User} inside the database.
     * @return User|null The created {@link User} or null, if an error occurred.
     */
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
        if (!$stmt->execute()) {
            logData("User Model", "Query execute error! (insert)", CRITICAL_LOG);
            return null;
        }

        // get result
        $newId = $stmt->insert_id;
        $stmt->close();

        return self::getById($newId);
    }

    /**
     * Updates the {@link User} inside the database.
     * @return User|null The updated {@link User} or null, if an error occurred.
     */
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
        if (!$stmt->execute()) {
            logData("User Model", "Query execute error! (update)", CRITICAL_LOG);
            return null;
        }

        // get result
        $stmt->close();

        return self::getById($this->id);
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
        if (!$stmt->execute()) {
            logData("User Model", "Query execute error! (delete)", CRITICAL_LOG);
            return false;
        }

        $stmt->close();

        return self::getById($this->id) == null;
    }
}