<!-- TODO Comment -->

<?php
require_once "../site_php_head.inc.php";

require_once CONTROLLER_DIR . DIRECTORY_SEPARATOR . "controller_cart_product.php";
require_once CONTROLLER_DIR . DIRECTORY_SEPARATOR . "controller_product.php";

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

header("LOCATION: " . PAGES_DIR . DIRECTORY_SEPARATOR . "page_shopping_cart.php");
die();
