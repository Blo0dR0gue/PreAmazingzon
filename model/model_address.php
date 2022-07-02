<?php

//Add database
require_once(INCLUDE_DIR . "database.inc.php");

class Address
{
    // region fields
    private int $id;
    private string $street;
    private string $number;
    private string $zip;
    private string $city;
    private int $userId;
    // endregion

    /**
     * Constructor of Address.
     * @param int $id
     * @param string $street
     * @param string $number
     * @param string $zip
     * @param string $city
     * @param int $userId
     */
    public function __construct(int $id, string $street, string $number, string $zip, string $city, int $userId)
    {
        $this->id = $id;
        $this->street = $street;
        $this->number = $number;
        $this->zip = $zip;
        $this->city = $city;
        $this->userId = $userId;
    }

    // region getter

    /**
     * Get all existing addresses related to one user.
     * @param int $userId user of interest
     * @return array<Address>|null array of addresses
     */
    public static function getAllByUser(int $userId): ?array
    {
        $stmt = getDB()->prepare("SELECT * FROM address WHERE user = ?;");
        $stmt->bind_param("i", $userId);
        if (!$stmt->execute()) {
            return null;
        }

        // get result
        $res = $stmt->get_result();
        if ($res->num_rows === 0) {
            logData("Address Model", "No items were found for user with id: " . $userId . "!", NOTICE_LOG);
            return null;
        }

        $arr = array();
        while ($r = $res->fetch_assoc()) {
            $arr[] = new Address($r["id"], $r["street"], $r["streetNumber"], $r["zipCode"], $r["city"], $r["user"]);
        }
        $stmt->close();

        return $arr;
    }

    /**
     * Get default existing default address related to one user.
     * @param int $userId user of interest
     * @return Address|null default address
     */
    public static function getDefaultByUser(int $userId): ?Address
    {
        $stmt = getDB()->prepare("SELECT defaultAddress FROM user WHERE id = ?;");
        $stmt->bind_param("i", $userId);
        if (!$stmt->execute()) {
            logData("Address Model", "No default address were found for user with id: " . $userId . "!", NOTICE_LOG);
            return null;
        }

        // get result
        $res = $stmt->get_result();
        if ($res->num_rows === 0) {
            return null;
        }
        $res = $res->fetch_assoc();
        $stmt->close();

        return Address::getById($res["defaultAddress"]);
    }

    /**
     * Get an existing address by its id.
     *
     * @param int $id ID of an address
     * @return Address|null corresponding address
     */
    public static function getById(int $id): ?Address
    {
        $stmt = getDB()->prepare("SELECT * FROM address WHERE id = ?;");
        $stmt->bind_param("i", $id);
        if (!$stmt->execute()) {
            return null;
        }

        // get result
        $res = $stmt->get_result();
        if ($res->num_rows === 0) {
            logData("Address Model", "No items were found for id: " . $id . "!", NOTICE_LOG);
            return null;
        }
        $res = $res->fetch_assoc();
        $stmt->close();

        return new Address($id, $res["street"], $res["streetNumber"], $res["zipCode"], $res["city"], $res["user"]);
    }

    /**
     * Gets the database id for the object.
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Gets the street name.
     * @return string The street name.
     */
    public function getStreet(): string
    {
        return $this->street;
    }

    /**
     * Sets the street name
     * @param string $street The street name.
     */
    public function setStreet(string $street): void
    {
        $this->street = $street;
    }

    /**
     * Gets the street number.
     * @return string The street number
     */
    public function getNumber(): string
    {
        return $this->number;
    }

    /**
     * Sets the street number.
     * @param string $number The street number.
     */
    public function setNumber(string $number): void
    {
        $this->number = $number;
    }

    /**
     * Gets the zip code.
     * @return string The zip code.
     */
    public function getZip(): string
    {
        return $this->zip;
    }

    // endregion

    // region setter

    /**
     * Sets the zip code.
     * @param string $zip The zip code.
     */
    public function setZip(string $zip): void
    {
        $this->zip = $zip;
    }

    /**
     * Gets the name of the city.
     * @return string The citiy name.
     */
    public function getCity(): string
    {
        return $this->city;
    }

    /**
     * Sets the name of the city.
     * @param string $city The name
     */
    public function setCity(string $city): void
    {
        $this->city = $city;
    }

    /**
     * Gets the id of the user to which this address belongs.
     * @return int The user id.
     */
    public function getUserId(): int
    {
        return $this->userId;
    }

    /**
     * Sets the id of the user to which this address belongs.
     * @param int $userId The user id.
     */
    public function setUserId(int $userId): void
    {
        $this->userId = $userId;
    }

    // endregion

    /**
     * Creates a new {@link Address} inside the database.
     * @return Address|null The created {@link Address} or null, if an error occurred.
     */
    public function insert(): ?Address
    {
        $stmt = getDB()->prepare("INSERT INTO address(street, zipCode, streetNumber, city, user) 
                                        VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssi",
                          $this->street,
                          $this->zip,
                          $this->number,
                          $this->city,
                          $this->userId);
        if (!$stmt->execute()) {
            logData("Address Model", "A new address could not be created", CRITICAL_LOG);
            return null;
        }

        // get result
        $newId = $stmt->insert_id;
        $stmt->close();

        return self::getById($newId);
    }

    /**
     * Updates an {@link Address} in the database.
     * @return Address|null The updated {@link Address} or null, if an error occurred.
     */
    public function update(): ?Address
    {
        $stmt = getDB()->prepare("UPDATE address 
                                    SET street = ?,
                                        zipCode = ?,
                                        streetNumber = ?,
                                        city = ?,
                                        user = ?
                                    WHERE id = ?;");
        $stmt->bind_param("ssssii",
                          $this->street,
                          $this->zip,
                          $this->number,
                          $this->city,
                          $this->userId,
                          $this->id);
        if (!$stmt->execute()) {
            logData("Address Model", "Address with id: " . $this->id . " could not be updated!", CRITICAL_LOG);
            return null;
        }

        $stmt->close();

        return self::getById($this->id);
    }

    /**
     * Deletes an {@link Address} from the database.
     * @return bool true, if it was successfully.
     */
    public function delete(): bool
    {
        $stmt = getDB()->prepare("DELETE FROM address 
                                        WHERE id = ?;");
        $stmt->bind_param("i",
                          $this->id);
        if (!$stmt->execute()) {
            logData("Address Model", "Item with id: " . $this->userId . " could not be deleted!", CRITICAL_LOG);
            return false;
        }

        $stmt->close();

        return true;
    }
}