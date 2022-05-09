<!-- TODO COMMENT -->
<?php require_once "include/site_php_head.inc.php" ?>

<!DOCTYPE html>
<html class="h-100" lang="en">
<head>
    <?php require_once INCLUDE_DIR . DIRECTORY_SEPARATOR . "site_html_head.inc.php"; ?>
    <title><?= PAGE_NAME ?></title>
</head>

<body class="d-flex flex-column h-100">
<!--header -->
<?php require INCLUDE_DIR . DIRECTORY_SEPARATOR . "site_header.inc.php"; ?>

<!-- main body -->
<main class="flex-shrink-0">
    <section class="text-center bg-secondary">
        <div class="row py-lg-5 justify-content-center mx-0">
            <div class="col-lg-6 col-md-8">
                <h1 class="fw-light">Welcome on Amazingzon</h1>
                <p class="lead text-white">Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et magna aliquyam erat, sed diam voluptua. At vero eos et justo duo dolores et ea rebum. </p>
                <a href="<?= PAGES_DIR . DIRECTORY_SEPARATOR . "page_about.php" ?>"
                   class="btn btn-warning my-2 rounded-0">Learn more about us</a>
            </div>
        </div>
    </section>

    <section class="container py-4" id="products">
        <h2>Products of the Second</h2>
        <div class="row">
            <?php
            require CONTROLLER_DIR . DIRECTORY_SEPARATOR . "controller_product.php";
            foreach (ProductController::getRandomProducts(INDEX_PRODUCTS_AMOUNT) as $product)
            {
                require INCLUDE_DIR . DIRECTORY_SEPARATOR . "elem_product_card.inc.php";
            }
            ?>
        </div>
    </section>
</main>

<!-- footer -->
<?php require INCLUDE_DIR . DIRECTORY_SEPARATOR . "site_footer.inc.php" ?>

</body>
</html>
