<!-- TODO COMMENT -->
<?php require_once "../include/site_php_head.inc.php" ?>

<?php
if (!isset($_SESSION["login"]))   // if not logged in redirect to home
{
    header("LOCATION: " . ROOT_DIR);
    die();
}
?>

<!DOCTYPE html>
<html class="h-100" lang="en">
<head>
    <?php require_once INCLUDE_DIR . DIRECTORY_SEPARATOR . "site_html_head.inc.php"; ?>
    <title><?= PAGE_NAME ?> - Cart</title>

    <!-- file specific includes -->
    <?php require_once CONTROLLER_DIR . DIRECTORY_SEPARATOR . "controller_cart_product.php"; ?>
    <?php require_once CONTROLLER_DIR . DIRECTORY_SEPARATOR . "controller_product.php"; ?>

    <!-- load data for shopping cart -->
    <?php
    $cartProducts = CartProductController::getAllByUser($_SESSION["uid"]);
    ?>
</head>

<body class="d-flex flex-column h-100">
<!-- header -->
<?php require INCLUDE_DIR . DIRECTORY_SEPARATOR . "site_header.inc.php"; ?>

<!-- main body -->
<main class="">
    <!-- TODO make shopping cart work -->

    <section class="pt-5 pb-5">
        <div class="container">
            <div class="w-100 row">
                <div class="col-lg-12 col-md-12 col-12 px-0">
                    <h3 class="display-5 mb-2 text-center">Shopping Cart</h3>
                    <p class="mb-4 text-center font-weight-bold">
                        <i><?= CartProductController::getCountByUser($_SESSION["uid"]) ?> items in your cart</i>
                    </p>

                    <!-- table -->
                    <table id="shoppingCart" class="table table-condensed table-responsive">
                        <thead>
                        <tr>
                            <th style="width:60%">Product</th>
                            <th style="width:10%">Price</th>
                            <th style="width:10%">Quantity</th>
                            <th style="width:10%">Subtotal</th>
                            <th style="width:2%"></th>
                        </tr>
                        </thead>

                        <tbody>
                        <?php
                        $total = 0.0;
                        if ($cartProducts)  // if exist load cart entries into table rows
                        {
                            foreach ($cartProducts as $cartProduct)
                            {
                                $subtotal = 0;
                                require INCLUDE_DIR . DIRECTORY_SEPARATOR . "elem_cart_entry.php";
                                $total += $subtotal;
                            }
                        }
                        ?>
                        </tbody>
                    </table>
                    <h5 class="text-center text-muted mb-5"><i><?php if(!$cartProducts) echo "empty"; ?></i></h5>


                    <div class="float-end text-end">
                        <h4>Total:</h4>
                        <h1><?= number_format($total, 2, ".", "") . CURRENCY_SYMBOL ?></h1>
                    </div>
                </div>
            </div>

            <!-- bottom navigation  -->
            <div class="row mt-4 d-flex align-items-center">
                <div class="col-sm-6 order-md-2 text-end">
                    <a href="#" class="btn btn-warning mb-4 btn-lg pl-5 pr-5">Checkout</a><!-- TODO make checkout -->

                </div>
                <div class="col-sm-6 mb-3 mb-m-1 order-md-1 text-md-left">
                    <a href="<?= ROOT_DIR ?>" class="text-decoration-none"><i class="fa fa-arrow-left me-2"></i> Continue Shopping</a>
                </div>
            </div>
        </div>
    </section>
</main>

<!-- footer -->
<?php require INCLUDE_DIR . DIRECTORY_SEPARATOR . "site_footer.inc.php" ?>

</body>
</html>