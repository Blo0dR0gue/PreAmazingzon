<?php
//Enables / Disables a user

require_once "../site_php_head.inc.php";

UserController::redirectIfNotAdmin();

if(!isset($_POST["userId"]) || !is_numeric($_POST["userId"])){
    echo json_encode(array("msg"=>"value error"));
    die();
}

$user = UserController::getById($_POST["userId"]);

if(!isset($user)){
    exit(json_encode(array("msg"=>"user error")));
}

$user->setActive(!$user->isActive());
$user = $user->update();

if(!isset($user)){
    exit(json_encode(array("msg"=>"update error")));
}

exit(json_encode(array("msg"=>"done", "active"=>$user->isActive())));