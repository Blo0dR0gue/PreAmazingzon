<!-- TODO COMMENT -->
<?php
require_once "../../include/site_php_head.inc.php";

require_once CONTROLLER_DIR . DS . "controller_user.php";
require_once CONTROLLER_DIR . DS . "controller_cart_product.php";
require_once CONTROLLER_DIR . DS . "controller_product.php";

//Redirect to login page, if user is not logged-in.
UserController::redirectIfNotLoggedIn();

//Redirect, if no products are inside the cart.
if (CartProductController::getCountByUser($_SESSION["uid"]) <= 0) {
    header("Location: " . USER_PAGES_DIR . DS . "page_shopping_cart.php");
    die();
}

$user = UserController::getById($_SESSION["uid"]);
$primaryAddress = AddressController::getById($user->getDefaultAddressId());
$deliveryAddresses = AddressController::getAllByUser($user->getId());

?>

<!DOCTYPE html>
<html class="h-100" lang="en">
<head>
    <?php require_once INCLUDE_DIR . DS . "site_html_head.inc.php"; ?>
    <title><?= PAGE_NAME ?> - About</title>

    <!-- file specific includes-->
    <link rel="stylesheet" href="<?= STYLE_DIR . DS . "style_checkout.css"; ?>">
</head>

<body class="d-flex flex-column h-100">
<!-- header -->
<?php require INCLUDE_DIR . DS . "site_header.inc.php"; ?>

<!-- main body -->
<main class="flex-shrink-0">

    <form method="post" action="#" name="checkoutForm" id="checkoutForm">


    </form>

    <div class="container mt-1 mb-5 card shadow">
        <!-- Chosen address -->
        <div class="row g-0 border-bottom p-3">
            <div class="col-lg-3">
                <h5 class="mt-2" id="review_header">1. Delivery Address</h5>
            </div>
            <div class="col-lg-9 p-3 right-side align-content-center h-100">
                <?php if (isset($primaryAddress)): //Is the primaryAddress set? ?>

                    <div id="deliveryAddress">

                        <ul class="list-group">
                            <li class="list-group-item borderless p-0"><?= UserController::getFormattedName($user); ?></li>
                            <!--TODO Add other recipient (missing in database)?-->
                            <li class="list-group-item borderless p-0"><?= $primaryAddress->getStreet() . " " . $primaryAddress->getNumber(); ?></li>
                            <li class="list-group-item borderless p-0"><?= $primaryAddress->getCity() . ", " . $primaryAddress->getZip(); ?></li>
                        </ul>

                    </div>

                <?php else: ?>

                    <h5 class='text-muted mb-5'><i>There is no default address in your profile! Please select a delivery
                            address.</i></h5>

                <?php endif; ?>

                <div class="collapse w-100" id="collapseChooseDeliveryOption">
                    <div class="form-group position-relative">

                        <?php if (isset($primaryAddress)): ?>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="delivery" id="defaultDelivery" checked>
                                <label class="form-check-label" for="flexRadioDefault1">
                                    <?= UserController::getFormattedName($user) . " " . $primaryAddress->getStreet() . " " . $primaryAddress->getNumber() .
                                    ", " . $primaryAddress->getCity() . ", " . $primaryAddress->getZip()?>
                                </label>
                            </div>
                        <?php endif; ?>
                        <?php if (isset($deliveryAddresses) && count($deliveryAddresses) > 1): ?>
                            <?php foreach ($deliveryAddresses as $deliveryOption): ?>
                                <?php if (isset($primaryAddress) && $primaryAddress->getId() != $deliveryOption->getId()): ?>

                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="delivery">
                                        <label class="form-check-label"><!--TODO Add other recipient (missing in database)?-->
                                            <?= UserController::getFormattedName($user) . " " . $deliveryOption->getStreet() . " " . $deliveryOption->getNumber() .
                                            ", " . $deliveryOption->getCity() . ", " . $deliveryOption->getZip()?>
                                        </label>
                                    </div>

                                <?php endif ?>
                            <?php endforeach; ?>
                        <?php elseif (!isset($primaryAddress)): ?>
                            <h5 class='text-muted mb-5'><i>There are no addresses in your profile.</i></h5>
                            <input type="hidden" name="delivery" value="">
                        <?php endif; ?>
                    </div>

                </div>

                <br>

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
            <div class="col-lg-9 p-3 right-side align-content-center h-100">
                sdfasdfasdf
            </div>
        </div>

        <!-- Products -->
        <div class="row g-0 border-bottom p-3">
            <div class="col-lg-3">
                <h5 class="mt-2" id="review_header">3. Check Items and Prices</h5>

            </div>
            <div class="col-lg-9 p-3 right-side align-content-center h-100">
                sdfasdfasdf
            </div>
        </div>


    </div>


</main>

<!-- footer -->
<?php require INCLUDE_DIR . DS . "site_footer.inc.php"; ?>

</body>
</html>
