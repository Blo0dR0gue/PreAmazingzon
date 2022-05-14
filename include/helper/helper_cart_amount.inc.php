<!-- TODO Comment -->

<?php
require_once "../site_php_head.inc.php";

require_once CONTROLLER_DIR . DIRECTORY_SEPARATOR . "controller_cart_product.php";
require_once CONTROLLER_DIR . DIRECTORY_SEPARATOR . "controller_product.php";

if(isset($_GET["step"]) && isset($_GET["productId"]) && is_numeric($_GET["productId"]))
{
    $cartProduct = CartProductController::getById($_SESSION["uid"], $_GET["productId"]);

    if ($_GET["step"] === "inc")
    {
        CartProductController::incAmount($cartProduct);
    }elseif ($_GET["step"] === "dec")
    {
        CartProductController::decAmount($cartProduct);
    }
}
header("LOCATION: " . PAGES_DIR . DIRECTORY_SEPARATOR . "page_shopping_cart.php");
die();
