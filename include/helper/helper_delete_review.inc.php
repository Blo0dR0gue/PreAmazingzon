<?php
require_once "../site_php_head.inc.php";

require_once CONTROLLER_DIR . DS . "controller_user.php";
require_once CONTROLLER_DIR . DS . "controller_review.php";

UserController::redirectIfNotAdmin();

if(!isset($_GET["productId"]) || !is_numeric($_GET["productId"])){
    header("LOCATION: " . ROOT_DIR);
    die();
}

if(!isset($_GET["id"]) || !is_numeric($_GET["id"])){
    header("Location: " . PAGES_DIR . DS . "page_product_detail.php?id=".$_GET["productId"]);
    die();
}

$review = ReviewController::getById($_GET["id"]);

if(!isset($review)){
    header("Location: " . PAGES_DIR . DS . "page_product_detail.php?id=".$_GET["productId"]);
    die();
}

$suc = ReviewController::delete($review);

header("Location: " . PAGES_DIR . DS . "page_product_detail.php?id=".$_GET["productId"]);