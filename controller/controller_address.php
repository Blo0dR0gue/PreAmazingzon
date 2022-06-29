<?php
//TODO Comments

require_once MODEL_DIR . "model_address.php";

class AddressController
{
    public static function insert(string $street, string $number, string $zip, string $city, int $user_id): ?Address
    {   // TODO validate
        $address = new Address(0, $street, $number, $zip, $city, $user_id);
        return $address->insert();
    }

    public static function getById(?int $id): ?Address
    {
        if ($id != null) {
            return Address::getById($id);
        } else {
            return null;
        }
    }

    public static function update(Address $address, string $street, string $zipCode, string $streetNumber, string $city, int $userId = null): ?Address
    { // TODO validate?
        $address->setStreet($street);
        $address->setZip($zipCode);
        $address->setNumber($streetNumber);
        $address->setCity($city);
        if ($userId != null) { $address->setUserId($userId); }

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