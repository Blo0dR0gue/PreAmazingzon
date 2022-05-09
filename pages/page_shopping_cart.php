<!-- TODO COMMENT -->
<?php require_once "../include/site_php_head.inc.php" ?>

<!DOCTYPE html>
<html class="h-100" lang="en">
<head>
    <?php require_once INCLUDE_DIR . DIRECTORY_SEPARATOR . "site_html_head.inc.php"; ?>
    <title><?= PAGE_NAME ?> - Card</title>
</head>

<body class="d-flex flex-column h-100">
<!--header -->
<?php require INCLUDE_DIR . DIRECTORY_SEPARATOR . "site_header.inc.php"; ?>

<!-- main body -->
<main class="">

    <section class="pt-5 pb-5">
        <div class="container">
            <div class="w-100 row">
                <div class="col-lg-12 col-md-12 col-12 px-0">
                    <h3 class="display-5 mb-2 text-center">Shopping Cart</h3>
                    <p class="mb-4 text-center font-weight-bold">3 items in your cart</p>
                    <table id="shoppingCart" class="table table-condensed table-responsive">
                        <thead>
                        <tr>
                            <th style="width:60%">Product</th>
                            <th style="width:10%">Price</th>
                            <th style="width:10%">Quantity</th>
                            <th style="width:10%">Total</th>
                            <th style="width:2%"></th>
                        </tr>
                        </thead>

                        <tbody>
                        <?php require INCLUDE_DIR . DIRECTORY_SEPARATOR . "elem_cart_entry.php"?>
                        <?php require INCLUDE_DIR . DIRECTORY_SEPARATOR . "elem_cart_entry.php"?>
                        <?php require INCLUDE_DIR . DIRECTORY_SEPARATOR . "elem_cart_entry.php"?>
                        </tbody>
                    </table>

                    <div class="float-end text-end">
                        <h4>Subtotal:</h4>
                        <h1>99.00€</h1>
                    </div>
                </div>
            </div>

            <!-- bottom navigation  -->
            <div class="row mt-4 d-flex align-items-center">
                <div class="col-sm-6 order-md-2 text-end">
                    <a href="#" class="btn btn-warning mb-4 btn-lg pl-5 pr-5">Checkout</a>
                </div>
                <div class="col-sm-6 mb-3 mb-m-1 order-md-1 text-md-left">
                    <a href="#" class="text-decoration-none"><i class="fa fa-arrow-left me-2"></i> Continue Shopping</a>
                </div>
            </div>
        </div>
    </section>
</main>

<!-- footer -->
<?php require INCLUDE_DIR . DIRECTORY_SEPARATOR . "site_footer.inc.php" ?>

</body>
</html>