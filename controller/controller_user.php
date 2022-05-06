<!--TODO Comments-->

<?php

require_once MODEL_DIR . DIRECTORY_SEPARATOR . "model_user.php";

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


    public static function emailAvailable(string $email): bool
    {   // TODO validate

        if (UserController::getByEmail($email))  // user with mail exists?
        {
            return false;   // user exists
        }
        return true;    // user not exists
    }
}