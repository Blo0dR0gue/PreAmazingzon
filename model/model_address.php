<!-- TODO COMMENT-->

<?php
// load required files
require_once(INCLUDE_DIR . DIRECTORY_SEPARATOR . "database.inc.php");


class Address
{
    // region fields
    private int $id;
    private string $street;
    private string $number;
    private string $zip;
    private string $city;
    private int $user_id;
    // endregion

    /**
     * Constructor of Address.
     * @param int $id
     * @param string $street
     * @param string $number
     * @param string $zip
     * @param string $city
     * @param int $user_id
     */
    public function __construct(int $id, string $street, string $number, string $zip, string $city, int $user_id)
    {
        $this->id = $id;
        $this->street = $street;
        $this->number = $number;
        $this->zip = $zip;
        $this->city = $city;
        $this->user_id = $user_id;
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
    public function getStreet(): string
    {
        return $this->street;
    }

    /**
     * @return string
     */
    public function getNumber(): string
    {
        return $this->number;
    }

    /**
     * @return string
     */
    public function getZip(): string
    {
        return $this->zip;
    }

    /**
     * @return string
     */
    public function getCity(): string
    {
        return $this->city;
    }

    /**
     * @return int
     */
    public function getUserId(): int
    {
        return $this->user_id;
    }

    // endregion

    // region setter
    /**
     * @param string $street
     */
    public function setStreet(string $street): void
    {
        $this->street = $street;
    }

    /**
     * @param string $number
     */
    public function setNumber(string $number): void
    {
        $this->number = $number;
    }

    /**
     * @param string $zip
     */
    public function setZip(string $zip): void
    {
        $this->zip = $zip;
    }

    /**
     * @param string $city
     */
    public function setCity(string $city): void
    {
        $this->city = $city;
    }

    /**
     * @param int $user_id
     */
    public function setUserId(int $user_id): void
    {
        $this->user_id = $user_id;
    }

    // endregion

    public function insert(): ?Address
    {
        $stmt = getDB()->prepare("INSERT INTO address(street, zipCode, streetNumber, city, user) 
                                        VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssi",
            $this->street,
            $this->zip,
            $this->number,
            $this->city,
            $this->user_id);
        if (!$stmt->execute()) return null;     // TODO ERROR handling

        // get result
        $newId = $stmt->insert_id;
        $stmt->close();

        return self::getById($newId);
    }

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
            $this->user_id,
            $this->id);
        if (!$stmt->execute()) return null;     // TODO ERROR handling

        $stmt->close();

        return self::getById($this->id);
    }

    public function delete(): void
    {
        // TODO
    }

    /**
     * Get an existing address by its id.
     *
     * @param int $id ID of an address
     * @return Address|null new address
     */
    public static function getById(int $id): ?Address
    {
        $stmt = getDB()->prepare("SELECT * from address where id = ?;");
        $stmt->bind_param("i", $id);
        if (!$stmt->execute()) return null;     // TODO ERROR handling

        // get result
        $res = $stmt->get_result();
        if ($res->num_rows === 0) return null;
        $res = $res->fetch_assoc();
        $stmt->close();

        return new Address($id, $res["street"], $res["streetNumber"], $res["zipCode"], $res["city"], $res["user"]);
    }

    /**
     * Get all existing addresses related to one user.
     * @param int $user_id user of interest
     * @return array<Address>|null array if addresses
     */
    public static function getAllByUser(int $user_id): ?array
    {
        $stmt = getDB()->prepare("SELECT * from address where user = ?;");
        $stmt->bind_param("i", $user_id);
        if (!$stmt->execute()) return null;     // TODO ERROR handling

        // get result
        $res = $stmt->get_result();
        if ($res->num_rows === 0) return null;

        $arr = array();
        while ($r = $res->fetch_assoc()) {
            $arr[] = new Address($r["id"], $r["street"], $r["streetNumber"], $r["zipCode"], $r["city"], $r["user"]);
        }
        $stmt->close();

        return $arr;
    }

    /**
     * Get default existing default address related to one user.
     * @param int $user_id user of interest
     * @return Address|null default address
     */
    public static function getDefaultByUser(int $user_id): ?Address
    {
        $stmt = getDB()->prepare("SELECT defaultAddress from user where id = ?;");
        $stmt->bind_param("i", $user_id);
        if (!$stmt->execute()) return null;     // TODO ERROR handling

        // get result
        $res = $stmt->get_result();
        if ($res->num_rows === 0) return null;
        $res = $res->fetch_assoc();
        $stmt->close();

        return Address::getById($res["defaultAddress"]);
    }
}

?>