<!--TODO Comments -->

<?php

require_once MODEL_DIR . DIRECTORY_SEPARATOR . "model_address.php";

class AddressController
{
    public static function insert(string $street, string $number, string $zip, string $city, int $user_id): ?Address
    {   // TODO validate
        $address = new Address(0, $street, $number, $zip, $city, $user_id);
        return $address->insert();
    }

    public static function getById(?int $id): ?Address
    {
        if ($id != null)
            return Address::getById($id);
        else
            return null;
    }

    public static function update(Address $address, string $street, string $zipCode, string $streetNumber, string $city, int $userId = null): ?Address
    { // TODO validate?
        $address->setStreet($street);
        $address->setZip($zipCode);
        $address->setNumber($streetNumber);
        $address->setCity($city);
        if ($userId != null) $address->setUserId($userId);

        return $address->update();
    }
}