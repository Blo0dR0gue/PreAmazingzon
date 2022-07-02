<?php
// Enables / Disables a user

require_once "../site_php_head.inc.php";

UserController::redirectIfNotAdmin();

if (!isset($_POST["userId"]) || !is_numeric($_POST["userId"])) {
    logData("Toggle User Activation", "Value is missing or does not have the correct datatype!", CRITICAL_LOG);
    echo json_encode(array("state" => "error", "msg" => "value error"));
    die();
}

$user = UserController::getById($_POST["userId"]);

if (!isset($user)) {
    logData("Toggle User Activation", "User with id: " . $_POST["userId"] . " not found!", CRITICAL_LOG);
    exit(json_encode(array("state" => "error", "msg" => "user error")));
}

$user->setActive(!$user->isActive());
$user = $user->update();

if (!isset($user)) {
    logData("Toggle User Activation", "User with id: " . $_POST["userId"] . " could not be updated!", CRITICAL_LOG);
    exit(json_encode(array("state" => "error", "msg" => "update error")));
}

logData("Toggle User Activation", "User with id " . $user->getId() . " now has the status: " . ($user->isActive() ? "Active" : "Disabled"));
exit(json_encode(array("state" => "success", "msg" => "done", "active" => $user->isActive())));