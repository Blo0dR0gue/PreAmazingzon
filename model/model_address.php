<!-- TODO COMMENT-->

<?php
// load required files
require_once(INCLUDE_DIR . DIRECTORY_SEPARATOR . "database.php");


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

    /**
     * Get an address by its id.
     *
     * @param int $id ID of an address
     * @return Address new address
     */
    public static function getById(int $id): Address
    {
//        TODO ERROR handling
        $stmt = getDB()->prepare("SELECT * from Address where id = :id;");
        $stmt->bind_param(':id', $id);
        $stmt->execute();

        $res = $stmt->get_result()->fetch_assoc();

        $stmt->close();

        return new Address($id, $res['street'], $res['streetNumber'], $res['zipCode'], $res['city'], $res['user']);
    }


}