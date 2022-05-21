<!-- TODO COMMENT -->
<?php
require_once "../../include/site_php_head.inc.php";

require_once CONTROLLER_DIR . DS . "controller_user.php";
require_once CONTROLLER_DIR . DS . "controller_cart_product.php";
require_once CONTROLLER_DIR . DS . "controller_product.php";

//Redirect to login page, if user is not logged-in.
UserController::redirectIfNotLoggedIn();

//Redirect, if no products are inside the cart.
if(CartProductController::getCountByUser($_SESSION["uid"]) <= 0){
    header("Location: " . USER_PAGES_DIR . DS . "page_shopping_cart.php");
    die();
}

$deliveryAddresses = AddressController::getAllByUser($_SESSION["uid"]);

?>

<!DOCTYPE html>
<html class="h-100" lang="en">
<head>
    <?php require_once INCLUDE_DIR . DS . "site_html_head.inc.php"; ?>
    <title><?= PAGE_NAME ?> - About</title>
</head>

<body class="d-flex flex-column h-100">
<!-- header -->
<?php require INCLUDE_DIR . DS . "site_header.inc.php"; ?>

<!-- main body -->
<main class="flex-shrink-0">

    <section class="container" id="selectDeliveryAdress">
        <div class="row">
            <?php
            foreach ($deliveryAddresses as $deliveryAddress): ?>
                <div class="col-3">
                    <div class="card">
                        <div class="card-body">
                            <strong class="recipient"><?= $deliveryAddress['recipient'] ?></strong>
                            <p class="street">
                                <?= $deliveryAddress['street'] ?> <?= $deliveryAddress['streetNumber'] ?>
                            </p>
                            <p class="city">
                                <?= $deliveryAddress['zipCode'] ?> <?= $deliveryAddress['city'] ?>
                            </p>
                            <a class="card-link" href="index.php/selectDeliveryAddress/<?= $deliveryAddress['id'] ?>">Wählen</a>
                        </div>
                    </div>
                </div>
            <?php
            endforeach; ?>
        </div>
    </section>

    <section class="container" id="newDelieryAdress">
        <!--TODO-->
    </section>

</main>

<!-- footer -->
<?php require INCLUDE_DIR . DS . "site_footer.inc.php"; ?>

</body>
</html>
