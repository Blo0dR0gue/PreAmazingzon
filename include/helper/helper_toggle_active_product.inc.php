<?php
// Toggles the active status of a product

require_once "../site_php_head.inc.php";

UserController::redirectIfNotAdmin();

if (!isset($_POST["productId"]) || !is_numeric($_POST["productId"])) {
    logData("Toggle Product Activation", "Value is missing or does not have the correct datatype!", CRITICAL_LOG);
    echo json_encode(array("state" => "error", "msg" => "value error"));
    die();
}

$product = ProductController::getById($_POST["productId"]);

if (!isset($product)) {
    logData("Toggle Product Activation", "Product with id: " . $_POST["productId"] . " not found!", CRITICAL_LOG);
    exit(json_encode(array("state" => "error", "msg" => "product error")));
}

$product->setActive(!$product->isActive());
$product = $product->update();

if (!isset($product)) {
    logData("Toggle Product Activation", "Product with id: " . $_POST["productId"] . " could not be updated!", CRITICAL_LOG);
    exit(json_encode(array("state" => "error", "msg" => "update error")));
}

logData("Toggle Product Activation", "Product with id " . $product->getId() . " now has the status: " . ($product->isActive() ? "Active" : "Disabled"));
exit(json_encode(array("state" => "success", "msg" => "done", "active" => $product->isActive())));