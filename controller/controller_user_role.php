<?php

//Add the user role model.
require_once MODEL_DIR . "model_user_role.php";

class UserRoleController
{

    /**
     * Gets the default user role: "user"
     * @return UserRole|null The {@link UserRole} "user"
     */
    public static function getDefaultUserRole(): ?UserRole
    {
        return UserRole::getByName("user");
    }

    /**
     * Gets the admin user role: "admin"
     * @return UserRole|null The {@link UserRole} "admin"
     */
    public static function getAdminUserRole(): ?UserRole
    {
        return UserRole::getByName("admin");
    }
}