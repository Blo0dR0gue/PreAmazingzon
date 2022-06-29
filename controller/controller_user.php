<?php
//TODO Comments

require_once MODEL_DIR . "model_user.php";

class UserController
{

    public static function getFormattedName(User $user): string
    {
        return $user->getFirstName() . " " . $user->getLastName();
    }

    public static function login(User $user, string $password): bool
    {   // TODO validate

        if ($user->isActive()) {    // only login active users
            if (password_verify($password, $user->getPasswordHash())) {     // valid credentials?
                // save the login state
                $_SESSION["login"] = true;
                $_SESSION["first_name"] = $user->getFirstName();
                $_SESSION["last_name"] = $user->getLastName();
                $_SESSION["uid"] = $user->getId();
                $_SESSION["isAdmin"] = $user->getRoleId() === UserRoleController::getAdminUserRole()->getId();

                return true;    // success
            }
        }
        return false;   // failure
    }

    public static function register(string $first_name, string $last_name, string $email, string $password, string $zip, string $city, string $street, string $number, int $role_id): ?User
    {   // TODO validate

        if (self::emailAvailable($email)) {     // email unique
            // hash password
            $password_hash = password_hash($password, PASSWORD_DEFAULT);

            // create user
            $user = new User(0, $first_name, $last_name, $email, $password_hash, true, $role_id, null);
            $user = $user->insert();
            if (!$user) { return null; }

            // create address
            $address = AddressController::insert($street, $number, $zip, $city, $user->getId());
            if (!$address) { return null; }

            // save address to user
            $user->setDefaultAddressId($address->getId());
            $user->update();

            return $user;
        }
        return null; // email not unique
    }

    public static function emailAvailable(string $email): bool
    {   // TODO validate
        // user with mail exists?
        if (UserController::getByEmail($email)) { return false; }   // user exists
        return true;    // user not exists
    }

    public static function getByEmail(string $email): ?User
    {   // TODO validate?
        return User::getByEmail($email);
    }

    public static function update(User $user, string $first_name, string $last_name, string $email, string $password, int $role_id = null, int $defaultAddressId = null): ?User
    { // TODO validate?
        if ($user->getEmail() === $email || self::emailAvailable($email)) {     // email unique?
            // update user
            $user->setFirstName($first_name);
            $user->setLastName($last_name);
            $user->setEmail($email);
            $user->setPasswordHash(password_hash($password, PASSWORD_DEFAULT));
            $user->setActive(true);
            if ($role_id != null) { $user->setRoleId($role_id); }
            if ($defaultAddressId != null) { $user->setDefaultAddressId($defaultAddressId); }

            return $user->update();
        }
        return null; // email not unique
    }

    /**
     * Redirect to the login page, if the user is not logged in or is inactive.
     * @return void
     */
    public static function redirectIfNotLoggedIn(): void
    {
        if (UserController::isCurrentSessionLoggedIn()) { return; } //User is logged in

        //delete all session variables
        header("Location: " . PAGES_DIR . "page_login.php");
        die();
    }

    /**
     * Is user logged in?
     * @return bool true, if he is logged in
     */
    public static function isCurrentSessionLoggedIn(): bool
    {
        if (isset($_SESSION["login"]) && isset($_SESSION["uid"])) {
            $user = self::getById($_SESSION["uid"]);

            //Check, if this user even exist and if he is active
            if (isset($user) && $user->isActive()) {
                return true; //User is logged in
            }
        }
        return false;
    }

    public static function getById(?int $id): ?User
    {
        if (isset($id)){
            return User::getById($id);
        } else {
            return null;
        }
    }

    /**
     * Redirect to the index page, if the user is not an admin
     * @return void
     */
    public static function redirectIfNotAdmin(): void
    {
        if (UserController::isCurrentSessionAnAdmin()) { return; } //User is admin

        header("Location: " . ROOT_DIR);
        die();
    }

    /**
     * Is the current logged-in user an admin?
     * @return bool true, if he is an admin
     */
    public static function isCurrentSessionAnAdmin(): bool
    {
        if (isset($_SESSION["login"]) && isset($_SESSION["uid"]) && isset($_SESSION["isAdmin"]) && $_SESSION["isAdmin"]) {
            $user = self::getById($_SESSION["uid"]);

            //Check, if this user even exist and if he is active
            if (isset($user) && $user->isActive()) {
                //Check if user is really an admin
                if ($user->getRoleId() === UserRoleController::getAdminUserRole()->getId()){
                    return true; // user is admin
                }
            }
        }
        return false;
    }

    public static function getAmountOfUsers(): int
    {
        return User::getAmountOfUsers();
    }

    public static function getUsersInRange(int $offset): array
    {
        return User::getUsersInRange($offset, LIMIT_OF_SHOWED_ITEMS);
    }

    public static function delete(User $user): bool
    {
        return $user->delete();
    }

}