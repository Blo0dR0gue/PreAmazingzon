<?php
require_once "../site_php_head.inc.php";

UserController::redirectIfNotLoggedIn();

if (isset($_GET["action"]) && isset($_GET["productId"]) && is_numeric($_GET["productId"])) {
    $cartProduct = CartProductController::getById($_SESSION["uid"], $_GET["productId"]);

    if ($_GET["action"] === "inc") {    // increase amount number by one
        CartProductController::incAmount($cartProduct);
    } elseif ($_GET["action"] === "dec") {  // decrease amount number by one
        CartProductController::decAmount($cartProduct);
    } elseif ($_GET["action"] === "del") {  // delete entry
        CartProductController::delete($cartProduct);
    } elseif ($_GET["action"] === "add") {  // add product
        if (isset($_GET["quantity"])) {
            CartProductController::add($_SESSION["uid"], $_GET["productId"], $_GET["quantity"]);
        }
    }
}

header("LOCATION: " . USER_PAGES_DIR . "page_shopping_cart.php");
die();
