<!--TODO Comments-->

<?php

require_once MODEL_DIR . DIRECTORY_SEPARATOR . "model_address.php";

class AddressController
{

    public static function insertAddress(string $street, string $number, string $zip, string $city, int $user_id): ?Address
    {   // TODO validate
        $address = new Address(0, $street, $number, $zip, $city, $user_id);
        return $address->insert();
    }

}