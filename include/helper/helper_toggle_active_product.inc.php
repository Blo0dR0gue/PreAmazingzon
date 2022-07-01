<?php
// Toggles the active status of a product

require_once "../site_php_head.inc.php";

UserController::redirectIfNotAdmin();

if (!isset($_POST["productId"]) || !is_numeric($_POST["productId"])) {
    echo json_encode(array("state" => "error", "msg" => "value error"));
    die();
}

$product = ProductController::getById($_POST["productId"]);

if (!isset($product)) {
    exit(json_encode(array("state" => "error", "msg" => "product error")));
}

$product->setActive(!$product->isActive());
$product = $product->update();

if (!isset($product)) {
    exit(json_encode(array("state" => "error", "msg" => "update error")));
}

exit(json_encode(array("state" => "success", "msg" => "done", "active" => $product->isActive())));