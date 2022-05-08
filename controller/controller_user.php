<!--TODO Comments-->

<?php

require_once MODEL_DIR . DIRECTORY_SEPARATOR . "model_user.php";

require_once CONTROLLER_DIR . DIRECTORY_SEPARATOR . "controller_user_role.php";
require_once CONTROLLER_DIR . DIRECTORY_SEPARATOR . "controller_address.php";

class UserController
{

    public static function getByEmail(string $email): ?User
    {   // TODO validate
        return User::getByEmail($email);
    }

    public static function login(User $user, string $password): bool
    {   // TODO validate

        if ($user->isActive())  // only login active users
        {
            if (password_verify($password, $user->getPasswordHash()))   // valid credentials?
            {
                // save the login state
                $_SESSION["login"] = true;
                $_SESSION["user"] = $user;

                return true;    // success
            }
        }
        return false;   // failure
    }

    public static function register(string $first_name, string $last_name, string $email, string $password, string $zip, string $city, string $street, string $number): ?User
    {   // TODO validate

        if (self::emailAvailable($email))  // email unique
        {
            // hash password
            $password_hash = password_hash($password, PASSWORD_DEFAULT);

            // get userRole
            $role_id = UserRoleController::getDefaultUserRole()->getId();

            // create user
            $user = new User(0, $first_name, $last_name, $email, $password_hash, true, $role_id, null);
            $user = $user->insert();
            if (!$user) return null;

            // create address
            $address = AddressController::insertAddress($street, $number, $zip, $city, $user->getId());
            if (!$address) return null;

            // save address to user
            $user->setDefaultAddressId($address->getId());
            $user->update();

            return $user;
        }
        return null; // email not unique
    }

    public static function emailAvailable(string $email): bool
    {   // TODO validate

        if (UserController::getByEmail($email))  // user with mail exists?
        {
            return false;   // user exists
        }
        return true;    // user not exists
    }
}