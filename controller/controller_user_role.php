<!--TODO Comments -->

<?php

require_once MODEL_DIR . DIRECTORY_SEPARATOR . "model_user_role.php";

class UserRoleController
{

    public static function getDefaultUserRole(): ?UserRole
    {
        return UserRole::getByName("user");
    }

    public static function getAdminUserRole(): ?UserRole
    {
        return UserRole::getByName("admin");
    }
}