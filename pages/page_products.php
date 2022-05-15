<!--Page to show all products and searched products -->
<?php
require_once "../include/site_php_head.inc.php";
require_once CONTROLLER_DIR . DIRECTORY_SEPARATOR . 'controller_product.php';
require_once CONTROLLER_DIR . DIRECTORY_SEPARATOR . 'controller_review.php';
require_once CONTROLLER_DIR . DIRECTORY_SEPARATOR . 'controller_category.php';

$products = [];

$offset = $_GET["offset"] ?? 0;
$amount = $_GET["amount"] ?? 8; // TODO remove hardcoded value?

if(isset($_GET["search"])){
    $products = ProductController::searchProducts($_GET["search"]);
} else {
    $products = ProductController::getProductsInRange($offset, $amount);
}

?>

<!DOCTYPE html>
<html class="h-100" lang="en">
<head>
    <?php require_once INCLUDE_DIR . DIRECTORY_SEPARATOR . "site_html_head.inc.php"; ?>
    <title><?= PAGE_NAME ?></title>
</head>

<body class="d-flex flex-column h-100">
<!-- header -->
<?php require INCLUDE_DIR . DIRECTORY_SEPARATOR . "site_header.inc.php"; ?>

<!-- main body -->
<main class="flex-shrink-0">
    <section class="container py-4" id="products">
        <div class="row">
            <?php
            foreach ($products as $product)
            {
                require INCLUDE_DIR . DIRECTORY_SEPARATOR . "elem_product_card.inc.php";
            }//TODO only show x amount
            ?>
        </div>
    </section>
</main>

<!-- footer -->
<?php require INCLUDE_DIR . DIRECTORY_SEPARATOR . "site_footer.inc.php" ?>

</body>
</html>
