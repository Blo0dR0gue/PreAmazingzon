<?php
// Enables / Disables a user

require_once "../site_php_head.inc.php";

UserController::redirectIfNotAdmin();

if (!isset($_POST["userId"]) || !is_numeric($_POST["userId"])) {
    echo json_encode(array("state" => "error", "msg" => "value error"));
    die();
}

$user = UserController::getById($_POST["userId"]);

if (!isset($user)) {
    exit(json_encode(array("state" => "error", "msg" => "user error")));
}

$user->setActive(!$user->isActive());
$user = $user->update();

if (!isset($user)) {
    exit(json_encode(array("state" => "error", "msg" => "update error")));
}

exit(json_encode(array("state" => "success", "msg" => "done", "active" => $user->isActive())));