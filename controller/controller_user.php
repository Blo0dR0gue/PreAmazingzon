<?php

//Add the user model
require_once MODEL_DIR . "model_user.php";

class UserController
{

    /**
     * Gets the formatted name of a {@link User}. (Firstname Lastname)
     * @param User $user The {@link User}
     * @return string The formatted name.
     */
    public static function getFormattedName(User $user): string
    {
        return $user->getFirstName() . " " . $user->getLastName();
    }

    /**
     * Login a {@link User}.
     * @param User $user The {@link User}.
     * @param string $password The password.
     * @return bool true, if it was successfully.
     */
    public static function login(User $user, string $password): bool
    {

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

    /**
     * Registers a new {@link User}.
     * @param string $firstName The firstname
     * @param string $lastName The lastname.
     * @param string $email The email address.
     * @param string $password The password.
     * @param string $zip The primary zip code.
     * @param string $city The primary city.
     * @param string $street The primary street name
     * @param string $number The primary street number
     * @param int $roleId The role of the user.
     * @return User|null A new {@link User} object or null, if an error occurred.
     */
    public static function register(string $firstName, string $lastName, string $email, string $password, string $zip, string $city, string $street, string $number, int $roleId): ?User
    {   // TODO validate

        if (self::emailAvailable($email)) {     // email unique
            // hash password
            $passwordHash = password_hash($password, PASSWORD_DEFAULT);

            // create user
            $user = new User(0, $firstName, $lastName, $email, $passwordHash, true, $roleId, null);
            $user = $user->insert();
            if (!$user) {
                return null;
            }

            // create address
            $address = AddressController::insert($street, $number, $zip, $city, $user->getId());
            if (!$address) {
                return null;
            }

            // save address to user
            $user->setDefaultAddressId($address->getId());
            $user->update();

            return $user;
        }
        return null; // email not unique
    }

    /**
     * Checks, if an email address is available. (Is not already used)
     * @param string $email The email address.
     * @return bool true, if email is free to use.
     */
    public static function emailAvailable(string $email): bool
    {
        // user with mail exists?
        if (UserController::getByEmail($email)) {
            return false;
        }   // user exists
        return true;    // user not exists
    }

    /**
     * Gets an {@link User} by its email address.
     * @param string $email The email.
     * @return User|null The specific {@link User} or null, if not found.
     */
    public static function getByEmail(string $email): ?User
    {
        return User::getByEmail($email);
    }

    /**
     * Updates a {@link User}
     * @param User $user The {@link User}.
     * @param string $firstName The new firstname
     * @param string $lastName The new lastname
     * @param string $email The new email
     * @param string $password The new password.
     * @param int|null $roleId The new user role or null, if the current one should be used.
     * @param int|null $defaultAddressId The new id of the default address or null, if the current one should be used.
     * @return User|null The updated {@link User} or null, if an error occured.
     */
    public static function update(User $user, string $firstName, string $lastName, string $email, string $password, int $roleId = null, int $defaultAddressId = null, bool $active = null): ?User
    {
        if ($user->getEmail() === $email || self::emailAvailable($email)) {     // email unique?
            // update user
            $user->setFirstName(htmlspecialchars($firstName, ENT_QUOTES, "UTF-8"));
            $user->setLastName(htmlspecialchars($lastName, ENT_QUOTES, "UTF-8"));
            $user->setEmail(htmlspecialchars($email, ENT_QUOTES, "UTF-8"));
            $user->setPasswordHash(password_hash($password, PASSWORD_DEFAULT));

            if ($active != null)
                $user->setActive($active);

            if ($roleId != null) {
                $user->setRoleId($roleId);
            }
            if ($defaultAddressId != null) {
                $user->setDefaultAddressId($defaultAddressId);
            }

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
        if (UserController::isCurrentSessionLoggedIn()) {
            return;
        } //User is logged in

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
                //User is logged in
                $_SESSION["isAdmin"] = $user->getRoleId() === UserRoleController::getAdminUserRole()->getId();  //Update the role status if changed.
                return true;
            }
        }
        return false;
    }

    /**
     * Gets an {@link User} by its id.
     * @param int|null $id The id of the user.
     * @return User|null The {@link User} object or null, if not found.
     */
    public static function getById(?int $id): ?User
    {
        if (isset($id)) {
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
        if (UserController::isCurrentSessionAnAdmin()) {
            return;
        } //User is admin

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
                if ($user->getRoleId() === UserRoleController::getAdminUserRole()->getId()) {
                    //user is admin
                    $_SESSION["isAdmin"] = $user->getRoleId() === UserRoleController::getAdminUserRole()->getId();  //Update the role status if changed.
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * Gets the amount of users.
     * @return int The amount.
     */
    public static function getAmountOfUsers(): int
    {
        return User::getAmountOfUsers();
    }

    /**
     * Gets all {@link User}s in range.
     * @param int $offset The offset from where to select from the database
     * @return array An array with all {@link User}s in range.
     */
    public static function getUsersInRange(int $offset): array
    {
        return User::getUsersInRange($offset, LIMIT_OF_SHOWED_ITEMS);
    }

    /**
     * Deletes an {@link User}.
     * @param User $user The {@link User}.
     * @return bool true, if it was successfully.
     */
    public static function delete(User $user): bool
    {
        return $user->delete();
    }

}