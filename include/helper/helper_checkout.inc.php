<?php
//Handles order creation

require_once "../site_php_head.inc.php";

require_once CONTROLLER_DIR . DS . "controller_user.php";
require_once CONTROLLER_DIR . DS . "controller_cart_product.php";
require_once CONTROLLER_DIR . DS . "controller_product.php";
require_once CONTROLLER_DIR . DS . "controller_address.php";
require_once CONTROLLER_DIR . DS . "controller_order.php";

//Redirect, if user is not logged-in or got blocked (and logout)
UserController::redirectIfNotLoggedIn();

//Redirect back or to the shopping cart, if a post variable is not set.
if (!isset($_POST["delivery"]) || !isset($_POST["payment"]) ||
    !is_string($_POST["delivery"]) || !is_string($_POST["payment"])) {
    //Go back to previous page, if it got set, else go back to the shopping cart page
    if (isset($_SERVER["HTTP_REFERER"])) {
        header("Location: " . $_SERVER["HTTP_REFERER"]);
    } else {
        header("Location: " . USER_PAGES_DIR . DS . "page_shopping_cart.php");
    }
    die();
}

$orderProducts = CartProductController::getAllByUser($_SESSION["uid"]);

//Redirect to shopping cart, if no products are in it.
if(!isset($orderProducts)){
    header("Location: " . USER_PAGES_DIR . DS . "page_shopping_cart.php");
    die();
}

$deliveryAddress = AddressController::getById($_POST["delivery"]);

//Redirect to shopping cart, if the passed delivery address does not belong to the user or its null
if(isset($deliveryAddress) && !AddressController::doesThisAddressBelongsToUser($_SESSION["uid"], $deliveryAddress)){
    header("Location: " . USER_PAGES_DIR . DS . "page_shopping_cart.php");
    die();
}

