<?php require_once "../../include/site_php_head.inc.php" ?>

<?php
// Check if no user is logged-in or the logged-in user got blocked. If not redirect to root.
UserController::redirectIfNotLoggedIn();?>

<!DOCTYPE html>
<html class="h-100" lang="en">
<head>
    <?php require_once INCLUDE_DIR . "site_html_head.inc.php"; ?>
    <?php require_once INCLUDE_MODAL_DIR . "modal_popup.inc.php"; ?>
    <title><?= PAGE_NAME ?> - Cart</title>

    <!-- load data for shopping cart -->
    <?php $cartProducts = CartProductController::getAllByUser($_SESSION["uid"]); ?>
</head>

<body class="d-flex flex-column h-100">
<!-- header -->
<?php require_once INCLUDE_DIR . "site_header.inc.php"; ?>

<!-- main body -->
<main class="">
    <section class="pt-5 pb-5">
        <div class="container">
            <div class="w-100 row">
                <div class="col-lg-12 col-md-12 col-12 px-0">
                    <!-- heading -->
                    <h3 class="display-5 mb-2 text-center">Shopping Cart</h3>
                    <p class="mb-4 text-center font-weight-bold">
                        <em><?= CartProductController::getCountByUser($_SESSION["uid"]) ?> items in your cart</em>
                    </p>

                    <!-- table -->
                    <table id="shoppingCart" class="table table-condensed table-responsive"
                           aria-label="Shopping Cart List">
                        <thead>
                        <tr>
                            <th style="width:60%">Product</th>
                            <th style="width:10%">Price</th>
                            <th style="width:10%; text-align: center">Quantity</th>
                            <th style="width:10%">Subtotal</th>
                            <th style="width:2%"></th>
                        </tr>
                        </thead>

                        <tbody>
                        <?php
                        // The total sum of the cart.
                        $total = 0.0;
                        if ($cartProducts) {    // if exist load cart entries into table rows
                            foreach ($cartProducts as $cartProduct) {
                                // Reset parameter used by the template.
                                $subtotal = 0;
                                // decrease the amount of this product in cart or delete it, if another user bought this item and there a not enough items in stock.
                                if (!CartProductController::handleOtherUserBoughtItemInCart($cartProduct)) {    // if it got removed, don't show the item
                                    require INCLUDE_ELEMENTS_DIR . "elem_cart_entry.php";
                                }
                                $total += $subtotal;
                            }
                        } ?>
                        </tbody>
                    </table>

                    <h5 class="text-center text-muted mb-5"><em><?php if (!$cartProducts) {
                                echo "empty";
                            } ?></em>
                    </h5>

                    <div class="float-end text-end">
                        <h1 class="mb-0">
                            <small>Total:</small> <?= number_format($total, 2, ".", "") . CURRENCY_SYMBOL ?>
                        </h1>
                        <small class="text-muted mt-0">including tax and shipping</small>
                    </div>
                </div>
            </div>

            <!-- bottom navigation  -->
            <div class="row mt-4 d-flex align-items-center">
                <div class="col-sm-6 order-md-2 text-end">
                    <a href="<?= USER_PAGES_DIR . "page_select_delivery_address.php"; ?>"
                       class="btn btn-warning mb-4 btn-lg pl-5 pr-5 <?= CartProductController::getCountByUser($_SESSION["uid"]) > 0 ? "" : "disabled"; ?>">Checkout</a>
                </div>
                <div class="col-sm-6 mb-3 mb-m-1 order-md-1 text-md-left">
                    <a href="javascript:history.back()" class="text-decoration-none">
                        <em class="fa fa-arrow-left me-2"></em> Continue Shopping
                    </a>
                </div>
            </div>
        </div>
    </section>
</main>

<!-- footer -->
<?php require_once INCLUDE_DIR . "site_footer.inc.php" ?>

</body>
</html>