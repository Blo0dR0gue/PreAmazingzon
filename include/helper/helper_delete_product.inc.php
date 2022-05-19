<?php
//TODO umbauen auf ajax?
require_once "../site_php_head.inc.php";

if (!isset($_SESSION["login"]) || !isset($_SESSION["isAdmin"]) || !$_SESSION["isAdmin"]) {
    header("LOCATION: " . ROOT_DIR);    //User is not allowed to be here.
    //TODO log?
    die();
}

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