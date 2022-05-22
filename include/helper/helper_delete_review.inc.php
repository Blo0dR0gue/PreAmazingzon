<?php
require_once "../site_php_head.inc.php";

UserController::redirectIfNotAdmin();

if (!isset($_GET["productId"]) || !is_numeric($_GET["productId"])) {
    //Go back to previous page, if it got set, else to the index.php
    if (isset($_SERVER["HTTP_REFERER"])) {
        header("Location: " . $_SERVER["HTTP_REFERER"]);
    } else {
        header("LOCATION: " . ROOT_DIR);
    }
    die();
}

if (!isset($_GET["id"]) || !is_numeric($_GET["id"])) {
    //Go back to previous page, if it got set, else go back to the page_product_detail.php page
    if (isset($_SERVER["HTTP_REFERER"])) {
        header("Location: " . $_SERVER["HTTP_REFERER"]); //In this way, we can keep all set get parameters in the url
    } else {
        header("Location: " . PAGES_DIR . DS . "page_product_detail.php?id=" . $_GET["productId"]);
    }
    die();
}

$review = ReviewController::getById($_GET["id"]);

if (!isset($review)) {
    //Go back to previous page, if it got set, else go back to the page_product_detail.php page
    if (isset($_SERVER["HTTP_REFERER"])) {
        header("Location: " . $_SERVER["HTTP_REFERER"]); //In this way, we can keep all set get parameters in the url
    } else {
        header("Location: " . PAGES_DIR . DS . "page_product_detail.php?id=" . $_GET["productId"]);
    }
    die();
}

$suc = ReviewController::delete($review);

//Go back to previous page, if it got set, else go back to the page_product_detail.php page
if (isset($_SERVER["HTTP_REFERER"])) {
    header("Location: " . $_SERVER["HTTP_REFERER"]); //In this way, we can keep all set get parameters in the url
} else {
    header("Location: " . PAGES_DIR . DS . "page_product_detail.php?id=" . $_GET["productId"]);
}
die();