<?php
// Toggles the user role (Admin / Default)

require_once "../site_php_head.inc.php";

UserController::redirectIfNotAdmin();

if (!isset($_POST["userId"]) || !is_numeric($_POST["userId"])) {
    logData("Toggle User Role", "Value is missing or does not have the correct datatype!", CRITICAL_LOG);
    echo json_encode(array("state" => "error", "msg" => "value missing"));
    die();
}

$user = UserController::getById($_POST["userId"]);

if (!isset($user)) {
    logData("Toggle User Role", "User with id: " . $_POST["userId"] . " not found!", CRITICAL_LOG);
    exit(json_encode(array("state" => "error", "msg" => "user not found")));
}

$isNowDefault = false;

$defaultRole = UserRoleController::getDefaultUserRole();

if (!isset($defaultRole)) {
    logData("Toggle User Role", "Default role not found", CRITICAL_LOG);
    exit(json_encode(array("state" => "error", "msg" => "default role not found")));
}

$adminRole = UserRoleController::getAdminUserRole();

if (!isset($adminRole)) {
    logData("Toggle User Role", "Admin role not found", CRITICAL_LOG);
    exit(json_encode(array("state" => "error", "msg" => "admin role not found")));
}

if($user->getRoleId() == $adminRole->getId()){
    $isNowDefault = true;
    $user->setRoleId($defaultRole->getId());
}else{
    $user->setRoleId($adminRole->getId());
}

$user = $user->update();

if (!isset($user)) {
    logData("Toggle User Role", "User with id: " . $_POST["userId"] . " could not be updated!", CRITICAL_LOG);
    exit(json_encode(array("state" => "error", "msg" => "update error")));
}

logData("Toggle User Role", "User with id " . $user->getId() . " now has the role: " . ($isNowDefault?$defaultRole->getName():$adminRole->getName()));
exit(json_encode(array("state" => "success", "msg" => "done", "admin" => !$isNowDefault, "adminRoleName" => $adminRole->getName(), "defaultRoleName" => $defaultRole->getName())));