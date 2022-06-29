<?php
// Handles order creation

require_once "../site_php_head.inc.php";

// Redirect, if user is not logged-in or got blocked (and logout)
UserController::redirectIfNotLoggedIn();

// Redirect back or to the shopping cart, if a post variable is not set.
if (!isset($_POST["delivery"]) || empty($_POST["delivery"]) || !isset($_POST["payment"]) || // TODO Condition is unnecessary because it is checked by 'empty($_POST["delivery"])
    !is_string($_POST["delivery"]) || !is_string($_POST["payment"])) {
    // Go back to previous page, if it got set, else go back to the shopping cart page
    if (isset($_SERVER["HTTP_REFERER"])) {
        header("Location: " . $_SERVER["HTTP_REFERER"]);
    } else {
        header("Location: " . USER_PAGES_DIR . "page_shopping_cart.php");
    }
    die();
}

$cartProducts = CartProductController::getAllByUser($_SESSION["uid"]);

// Redirect to shopping cart, if no products are in it.
if (!isset($cartProducts) && count($cartProducts) > 0) {
    header("Location: " . USER_PAGES_DIR . "page_shopping_cart.php");
    die();
}

$deliveryAddress = AddressController::getById($_POST["delivery"]);

// Redirect to shopping cart, if the passed delivery address does not belong to the user or its null
if (isset($deliveryAddress) && !AddressController::doesThisAddressBelongsToUser($_SESSION["uid"], $deliveryAddress)) {
    header("Location: " . USER_PAGES_DIR . "page_shopping_cart.php");
    die();
}

$oderState = OrderStateController::getByName("new");
$order = null;

try {
    $order = OrderController::insert(new DateTime("NOW", new DateTimeZone(DATE_TIME_ZONE)),
        OrderController::calculateDeliveryDate(),
        false,
        $oderState->getId(),
        $_SESSION["uid"],
        $deliveryAddress->getId()
    );
} catch (Exception $e) {
    // TODO handle (Datetime error)
}

if (!isset($order)) {
    // TODO error handling
}

// Used for the invoice creation
$productOrders = [];

// Add products to order
foreach ($cartProducts as $cartProduct) {
    $product = ProductController::getByID($cartProduct->getProdId());

    if (!isset($product)) {
        // TODO error
    }

    $productOrder = ProductOrderController::insert(
        $cartProduct->getProdId(),
        $order->getId(),
        $cartProduct->getAmount(),
        $product->getPrice()
    );

    if (isset($productOrder)) {
        // Add this item to the list of orders products for the invoice creation
        $productOrders[] = $productOrder;

        // Decrease the amount of the bought product.
        ProductController::decreaseStockAmount($cartProduct->getAmount(), $product);
        // Remove product from cart.
        CartProductController::delete($cartProduct);
    } else {
        // TODO error handling
    }
}

// Done
// Create invoice
require_once INCLUDE_HELPER_DIR . "helper_create_invoice.inc.php";

header("Location: " . USER_PAGES_DIR . "page_thank_you.php");
die();