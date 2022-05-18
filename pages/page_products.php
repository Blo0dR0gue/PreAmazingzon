<!--Page to show all products and searched products -->
<!-- TODO comment -->

<?php
require_once "../include/site_php_head.inc.php";
require_once CONTROLLER_DIR . DS . 'controller_product.php';
require_once CONTROLLER_DIR . DS . 'controller_review.php';
require_once CONTROLLER_DIR . DS . 'controller_category.php';
?>

<?php
// Max amount of showed Items
$amount = LIMIT_OF_SHOWED_ITEMS;
// Current pagination page number
$page = (isset($_GET['page']) && is_numeric($_GET['page'])) ? $_GET['page'] : 1;
// Calculate offset for pagination
$offset = ($page - 1) * $amount;
// Get the total Amount of Products
$productCount = ProductController::getAmountOfProducts($_GET["search"]??null);
// Calculate the total amount of pages
$totalPages = ceil($productCount/$amount);

$products = [];

if (isset($_GET["search"])) {
    $products = ProductController::searchProducts($_GET["search"]);
} else {
    $products = ProductController::getProductsInRange($offset, $amount);
}
?>

<!DOCTYPE html>
<html class="h-100" lang="en">
<head>
    <?php require_once INCLUDE_DIR . DS . "site_html_head.inc.php"; ?>
    <title><?= PAGE_NAME ?></title>
</head>

<body class="d-flex flex-column h-100">
<!-- header -->
<?php require INCLUDE_DIR . DS . "site_header.inc.php"; ?>

<!-- main body -->
<main class="flex-shrink-0">
    <section class="container py-4" id="products">
        <div class="row">
            <?php
            foreach ($products as $product) {
                require INCLUDE_DIR . DS . "elem_product_card.inc.php";
            }//TODO show msg, if no product is available
            ?>
        </div>
    </section>
</main>

<!-- pagination -->
<?php require INCLUDE_DIR . DS . "dyn_pagination.inc.php" ?>

<!-- footer -->
<?php require INCLUDE_DIR . DS . "site_footer.inc.php" ?>

</body>
</html>
