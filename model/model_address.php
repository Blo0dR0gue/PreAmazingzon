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
     * Get an existing address by its id.
     *
     * @param int $id ID of an address
     * @return Address new address
     */
    public static function getById(int $id): Address
    {
        // TODO ERROR handling
        $stmt = getDB()->prepare("SELECT * from address where id = ?;");
        $stmt->bind_param("i", $id);
        $stmt->execute();

        $res = $stmt->get_result()->fetch_assoc();

        $stmt->close();

        return new Address($id, $res['street'], $res['streetNumber'], $res['zipCode'], $res['city'], $res['user']);
    }

    /**
     * Get all existing addresses related to one user.
     * @param int $user_id user of interest
     * @return array<Address> array if addresses
     */
    public static function getAllByUser(int $user_id): array
    {
        // TODO ERROR handling
        $stmt = getDB()->prepare("SELECT * from address where user = ?;");
        $stmt->bind_param('i', $user_id);
        $stmt->execute();

        $res = $stmt->get_result();

        $arr = Array();
        while ($r = $res->fetch_assoc()){
            $arr[] = new Address($r['id'], $r['street'], $r['streetNumber'], $r['zipCode'], $r['city'], $r['user']);
        }
        $stmt->close();

        return $arr;
    }

    /**
     * Get default excisting default address related to one user.
     * @param int $user_id user of interest
     * @return Address default address
     */
    public static function getDefaultByUser(int $user_id): Address
    {
        // TODO ERROR handling
        $stmt = getDB()->prepare("SELECT defaultAddress from user where id = ?;");
        $stmt->bind_param('i', $user_id);
        $stmt->execute();

        $res = $stmt->get_result()->fetch_assoc();

        $stmt->close();

        return Address::getById($res['defaultAddress']);
    }
}
?>