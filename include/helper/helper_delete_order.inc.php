<?php
//Deletes a order
require_once "../site_php_head.inc.php";

UserController::redirectIfNotAdmin();

if (!isset($_GET["id"]) || !is_numeric($_GET["id"])) {
    //Go back to previous page, if it got set, else to the index.php
    if (isset($_SERVER["HTTP_REFERER"])) {
        header("Location: " . $_SERVER["HTTP_REFERER"]);
    } else {
        header("LOCATION: " . ROOT_DIR);
    }
    die();
}

$order = OrderController::getById($_GET["id"]);

if (!isset($order)) {
    //Go back to previous page, if it got set, else go back to the page_users.php page
    if (isset($_SERVER["HTTP_REFERER"])) {
        header("Location: " . $_SERVER["HTTP_REFERER"]); //In this way, we can keep all set get parameters in the url
    } else {
        header("Location: " . ADMIN_PAGES_DIR . "page_orders.php");
    }
    die();
}

$suc = OrderController::delete($order);

//Go back to previous page, if it got set, else go back to the page_users.php page
if (isset($_SERVER["HTTP_REFERER"])) {
    header("Location: " . $_SERVER["HTTP_REFERER"]); //In this way, we can keep all set get parameters in the url
} else {
    header("Location: " . ADMIN_PAGES_DIR . "page_users.php");
}
die();