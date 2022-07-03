<?php
//Add the address model
require_once MODEL_DIR . "model_address.php";

class AddressController
{

    /**
     * Inserts a new address to the database
     * @param string $street The street name.
     * @param string $number The street number.
     * @param string $zip The zip code.
     * @param string $city The city.
     * @param int $userId The user to which this address belongs.
     * @return Address|null A new {@link Address} object or null, if an error occurred.
     */
    public static function insert(string $street, string $number, string $zip, string $city, int $userId): ?Address
    {
        $address = new Address(0, $street, $number, $zip, $city, $userId);
        return $address->insert();
    }

    /**
     * Gets an {@link Address} by the id.
     * @param int|null $id The id of the required address.
     * @return Address|null The {@link Address} object or null, if not found.
     */
    public static function getById(?int $id): ?Address
    {
        if ($id != null) {
            return Address::getById($id);
        } else {
            return null;
        }
    }

    /**
     * Updates an {@link Address} object
     * @param Address $address The new address
     * @param string $street The new street
     * @param string $zipCode The new zip code
     * @param string $streetNumber The new street number
     * @param string $city The new city
     * @param int|null $userId The new user id. Set it to null to use the current user id of the address.
     * @return Address|null The updated Address or null, if an error occurred.
     */
    public static function update(Address $address, string $street, string $zipCode, string $streetNumber, string $city, int $userId = null): ?Address  // TODO unused?
    {
        $address->setStreet($street);
        $address->setZip($zipCode);
        $address->setNumber($streetNumber);
        $address->setCity($city);
        if ($userId != null) {
            $address->setUserId($userId);
        }

        return $address->update();
    }

    /**
     * Checks, if an address belongs to a user or not
     * @param int $userId The id of the user
     * @param Address $address The address
     * @return bool true, if it belongs to the user
     */
    public static function doesThisAddressBelongsToUser(int $userId, Address $address): bool
    {
        $all = self::getAllByUser($userId);

        return in_array($address, $all);
    }

    /**
     * Get all existing addresses related to one user.
     * @param int $userId user of interest
     * @return array<Address>|null array of addresses
     */
    public static function getAllByUser(int $userId): ?array
    {
        return Address::getAllByUser($userId);
    }
}