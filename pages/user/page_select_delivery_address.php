<?php
require_once "../../include/site_php_head.inc.php";

// Redirect to login page, if user is not logged-in or got blocked.
UserController::redirectIfNotLoggedIn();

// Redirect, if no products are inside the cart.
if (CartProductController::getCountByUser($_SESSION["uid"]) <= 0) {
    header("Location: " . USER_PAGES_DIR . "page_shopping_cart.php");
    die();
}

// Load required data
$user = UserController::getById($_SESSION["uid"]);
$primaryAddress = AddressController::getById($user->getDefaultAddressId());
$deliveryAddresses = AddressController::getAllByUser($user->getId());
$cartItems = CartProductController::getAllByUser($user->getId());

// The total amount for the order
$totalProductPrice = 0;
?>

<!DOCTYPE html>
<html class="h-100" lang="en">
<head>
    <?php require_once INCLUDE_DIR . "site_html_head.inc.php"; ?>
    <title><?= PAGE_NAME ?> - About</title>

    <!-- file specific includes -->
    <link rel="stylesheet" href="<?= STYLE_DIR . "style_checkout.css"; ?>">
    <script src="<?= SCRIPT_DIR . "checkout_handler.js" ?>"></script>
</head>

<body class="d-flex flex-column h-100">
<!-- header -->
<?php require_once INCLUDE_DIR . "site_header.inc.php"; ?>

<!-- main body -->
<main class="flex-shrink-0">

    <!-- The purchase form -->
    <form method="post" class="needs-validation" action="<?= INCLUDE_HELPER_DIR . "helper_checkout.inc.php"; ?>"
          name="checkoutForm" id="checkoutForm" novalidate>

        <input type="hidden" value="<?= $user->getId() ?>" name="userId">

        <div class="container mt-5 mb-5 card shadow px-0">
            <!-- BODY -->
            <div class="card-body">
                <!-- Choose address -->
                <div class="row g-0 border-bottom p-3">
                    <div class="col-lg-3">
                        <h5 class="mt-2" id="review_header">1. Delivery Address</h5>
                    </div>
                    <div class="col-lg-9 right-side align-content-center h-100 mt-2">
                        <div id="deliveryAddress" class="mb-4">
                            <ul class="list-group">
                                <li id="selectedDeliveryName"
                                    class="list-group-item border-0 p-0"><?= isset($primaryAddress) ? UserController::getFormattedName($user) : ""; ?></li>
                                <li id="selectedDeliveryStreet"
                                    class="list-group-item border-0 p-0"><?= isset($primaryAddress) ? $primaryAddress->getStreet() . " " . $primaryAddress->getNumber() : ""; ?></li>
                                <li id="selectedDeliveryCity"
                                    class="list-group-item border-0 p-0"><?= isset($primaryAddress) ? $primaryAddress->getCity() . ", " . $primaryAddress->getZip() : ""; ?></li>
                            </ul>
                        </div>

                        <?php if (!isset($primaryAddress)) { ?>
                            <!-- Default address not found -->
                            <div id="noDeliveryText">
                                <h5 class='mb-4 text-danger'>
                                    <em>There is no default address in your profile! Please select a delivery address.</em>
                                </h5>
                            </div>
                        <?php } ?>

                        <!-- Delivery address select container -->
                        <div class="collapse w-100 mb-3" id="collapseChooseDeliveryOption">
                            <div class="form-group position-relative">
                                <!-- Are there some delivery addresses for the user?-->
                                <?php if (count($deliveryAddresses) > 0): ?>
                                    <!-- Add each delivery address as a selection input -->
                                    <?php foreach ($deliveryAddresses as $deliveryOption): ?>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="delivery"
                                                   id="<?= $deliveryOption->getId() ?>"
                                                   data-user="<?= UserController::getFormattedName($user) ?>"
                                                   data-street="<?= $deliveryOption->getStreet() . " " . $deliveryOption->getNumber() ?>"
                                                   data-city="<?= $deliveryOption->getCity() . ", " . $deliveryOption->getZip() ?>"
                                                   value="<?= $deliveryOption->getId(); ?>"
                                                   required
                                                <?php
                                                // Select the default address
                                                if (isset($primaryAddress) && $deliveryOption->getId() === $primaryAddress->getId()) {
                                                    echo "checked";
                                                } ?>>
                                            <label class="form-check-label" for="<?= $deliveryOption->getId() ?>">
                                                <?= "<b>" . UserController::getFormattedName($user) . "</b> " . $deliveryOption->getStreet() . " " . $deliveryOption->getNumber() .
                                                ", " . $deliveryOption->getCity() . ", " . $deliveryOption->getZip() ?>
                                            </label>
                                        </div>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <!-- No addresses found -->
                                    <h5 class='text-muted mb-5'><em>There are no addresses in your profile.</em></h5>
                                    <input type="hidden" name="delivery" value="" required>
                                <?php endif; ?>
                                <!-- Missing address validation text -->
                                <div class="invalid-tooltip opacity-75">Please choose a delivery address.</div>
                            </div>
                        </div>

                        <!-- Button to show the delivery address select container -->
                        <button class="btn btn-sm btn-primary w-100" type="button" data-bs-toggle="collapse"
                                data-bs-target="#collapseChooseDeliveryOption" aria-expanded="false"
                                aria-controls="collapseExample">
                            Select your delivery address
                        </button>
                    </div>
                </div>

                <!-- Payment method -->
                <div class="row g-0 border-bottom p-3">
                    <div class="col-lg-3">
                        <h5 class="mt-2" id="review_header">2. Payment Method</h5>
                    </div>
                    <div class="col-lg-9 right-side align-content-center h-100 mt-2">
                        <!-- Default payment method -->
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="payment" id="default" checked value="default">
                            <label class="form-check-label" for="default">
                                Default
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Products -->
                <div class="row g-0 p-3">
                    <div class="col-lg-3">
                        <h5 class="mt-2" id="review_header">3. Check Items and Prices</h5>
                    </div>
                    <div class="col-lg-9 right-side align-content-center h-100 mt-2">
                        <div class="d-flex justify-content-center row">
                            <div class="col-md-10">
                                <!-- List of all products in cart -->
                                <?php foreach ($cartItems as $cartProduct) {
                                    $subtotal = 0;
                                    require INCLUDE_ELEMENTS_DIR . "elem_checkout_product_card.inc.php";
                                    $totalProductPrice += $subtotal;
                                } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- FOOTER -->
            <div class="card-footer">
                <a href="javascript:history.back()" class="btn btn-danger me-2">Abort</a>
                <button class="btn btn-success">
                    Purchase for <?= number_format($totalProductPrice, 2, ".", "") . CURRENCY_SYMBOL ?>
                </button>
            </div>
        </div>
    </form>
</main>

<!-- footer -->
<?php require_once INCLUDE_DIR . "site_footer.inc.php"; ?>

<!-- load custom form validation script -->
<script src="<?= SCRIPT_DIR . "form_validation.js" ?>"></script>
<!-- enable tooltips on this page (by default disabled for performance)-->
<script src="<?= SCRIPT_DIR . "tooltip_enable.js" ?>"></script>

</body>
</html>
