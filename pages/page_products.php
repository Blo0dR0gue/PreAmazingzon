<!-- Page to show all products and searched products -->

<?php require_once "../include/site_php_head.inc.php"; ?>

<?php

// Pagination init
$page = (isset($_GET['page']) && is_numeric($_GET['page'])) ? $_GET['page'] : 1;                // Current pagination page number
$offset = ($page - 1) * LIMIT_OF_SHOWED_ITEMS;                                                  // Calculate offset for pagination
$productCount = ProductController::getAmountOfActiveProducts($_GET["search"] ?? null);      // Get the total Amount of Products
$totalPages = ceil($productCount / LIMIT_OF_SHOWED_ITEMS);                                 // Calculate the total amount of pages

// All products found
$products = [];

// Do we search for specific products?
if (isset($_GET["search"])) {
    $products = ProductController::searchProductsInRange($_GET["search"], true, $offset, LIMIT_OF_SHOWED_ITEMS);
} else {
    $products = ProductController::getProductsInRange(true, $offset, LIMIT_OF_SHOWED_ITEMS);
}
?>

<!DOCTYPE html>
<html class="h-100" lang="en">
<head>
    <?php require_once INCLUDE_DIR . "site_html_head.inc.php"; ?>
    <title><?= PAGE_NAME ?> - Products</title>
</head>

<body class="d-flex flex-column h-100">
<!-- header -->
<?php require_once INCLUDE_DIR . "site_header.inc.php"; ?>

<!-- main body -->
<main class="flex-shrink-0">
    <section class="container py-4" id="products">
        <!-- products row -->
        <div class="row">
            <h2>All Products</h2>
            <hr>
            <?php
            if (count($products) > 0) {
                // Add all found products to the page.
                foreach ($products as $product) {
                    require INCLUDE_ELEMENTS_DIR . "elem_product_card.inc.php";
                }
            } else {
                // No products found
                echo "<h5 class='text-center text-muted mb-5'><i>no products found</i></h5>";
            }
            ?>
        </div>
    </section>
</main>

<!-- pagination -->
<?php require INCLUDE_DIR . "dyn_pagination.inc.php" ?>

<!-- footer -->
<?php require_once INCLUDE_DIR . "site_footer.inc.php" ?>

</body>
</html>
