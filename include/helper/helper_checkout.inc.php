<?php
// Handles order creation

require_once "../site_php_head.inc.php";

// Redirect, if user is not logged-in or got blocked (and logout)
UserController::redirectIfNotLoggedIn();

// Redirect back or to the shopping cart, if a post variable is not set.
if (empty($_POST["delivery"]) || !isset($_POST["payment"]) ||
    !is_string($_POST["delivery"]) || !is_string($_POST["payment"])) {

    logData("Checkout", "Value is missing or does not have the correct datatype!", CRITICAL_LOG);

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
    logData("Checkout", "No products found in cart for user with id: " . $_SESSION["uid"] . "!", CRITICAL_LOG);
    header("Location: " . USER_PAGES_DIR . "page_shopping_cart.php");
    die();
}

$deliveryAddress = AddressController::getById($_POST["delivery"]);

// Redirect to shopping cart, if the passed delivery address does not belong to the user or its null
if (isset($deliveryAddress) && !AddressController::doesThisAddressBelongsToUser($_SESSION["uid"], $deliveryAddress)) {
    logData("Checkout", "Address not found or does not belong to the user!", CRITICAL_LOG);
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
    logData("Checkout", "Date could not be parsed!", CRITICAL_LOG, $e->getTrace());
    header("LOCATION: " . PAGES_DIR . 'page_error.php?errorCode=500');
    die();
}

if (!isset($order)) {
    logData("Checkout", "Order could not be created!", CRITICAL_LOG);
    header("LOCATION: " . PAGES_DIR . 'page_error.php?errorCode=500');
    die();
}

// Used for the invoice creation
$productOrders = [];

// Add products to order
foreach ($cartProducts as $cartProduct) {
    $product = ProductController::getByID($cartProduct->getProdId());

    if (!isset($product)) {
        logData("Checkout", "Product with id: " . $cartProduct->getProdId() . " not found!", CRITICAL_LOG);
        header("LOCATION: " . PAGES_DIR . 'page_error.php?errorCode=500');
        die();
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
        logData("Checkout", "Product with id: " . $product->getId() . " could not be added to the order with id: " . $order->getId(), CRITICAL_LOG);
        header("LOCATION: " . PAGES_DIR . 'page_error.php?errorCode=500');
        die();
    }
}

// Done
// Create invoice
require_once INCLUDE_HELPER_DIR . "helper_create_invoice.inc.php";

logData("Checkout", "Order with id: " . $order->getId() . "created!");

header("Location: " . USER_PAGES_DIR . "page_thank_you.php");
die();