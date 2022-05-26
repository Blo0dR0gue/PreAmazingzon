<?php
//Deletes a product
require_once "../site_php_head.inc.php";

UserController::redirectIfNotAdmin();

if (!isset($_GET["id"]) || !is_numeric($_GET["id"])) {
    header("LOCATION: " . ROOT_DIR);
    die();
}

$productID = intval($_GET["id"]);

$product = ProductController::getByID($productID);

if (!isset($product)) {
    header("LOCATION: " . ADMIN_PAGES_DIR . DS . "page_products.php");
    die();
}

$suc = ProductController::delete($product);

header("LOCATION: " . ADMIN_PAGES_DIR . DS . "page_products.php?deleted=" . $suc);