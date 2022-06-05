<?php
//TODO Comments

require_once MODEL_DIR . "model_user_role.php";

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