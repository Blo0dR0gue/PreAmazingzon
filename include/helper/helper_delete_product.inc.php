<?php
require_once "../site_php_head.inc.php";

require_once CONTROLLER_DIR . DS . "controller_user.php";

UserController::redirectIfNotAdmin();

if (!isset($_GET["id"]) || !is_numeric($_GET["id"])) {
    header("LOCATION: " . ROOT_DIR);
    die();
}

$productID = intval($_GET["id"]);

require_once CONTROLLER_DIR . DS . 'controller_product.php';

$product = ProductController::getByID($productID);

if (!isset($product)) {
    header("LOCATION: " . ADMIN_PAGES_DIR . DS . "page_products.php");
    die();
}

$suc = ProductController::delete($product);

header("LOCATION: " . ADMIN_PAGES_DIR . DS . "page_products.php?deleted=" . $suc);