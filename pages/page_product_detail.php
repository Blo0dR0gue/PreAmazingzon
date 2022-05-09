<!-- TODO COMMENT -->
<?php require_once "../include/site_php_head.inc.php" ?>

<?php
require_once CONTROLLER_DIR . DIRECTORY_SEPARATOR . 'controller_product.php';
require_once CONTROLLER_DIR . DIRECTORY_SEPARATOR . 'controller_review.php';

$productID = $_GET["id"];   //TODO html special chars?
if (isset($productID) && is_numeric($productID)) {
    $product = ProductController::getProductById(intval($productID));

    if (!isset($product)) {
        header("LOCATION: " . ROOT_DIR);   //Redirect, if no product is found.
        die();
    }
} else {
    header("LOCATION: " . ROOT_DIR);   //Redirect, if no number is passed.
    die();
}
?>

<!DOCTYPE html>
<html class="h-100" lang="en">
<head>
    <?php require_once INCLUDE_DIR . DIRECTORY_SEPARATOR . "site_html_head.inc.php"; ?>
    <title><?= PAGE_NAME ?> - <?= $product->getTitle(); ?></title>

    <!-- file specific includes-->
    <link rel="stylesheet" href="<?= STYLE_DIR . DIRECTORY_SEPARATOR . "style_product_detail.css"; ?>">
    <script src="<?= SCRIPT_DIR . DIRECTORY_SEPARATOR . "page_product_detail.js"; ?>"></script>
</head>

<body class="d-flex flex-column h-100">
<!--header -->
<?php require INCLUDE_DIR . DIRECTORY_SEPARATOR . "site_header.inc.php"; ?>

<!-- main body -->
<main class="flex-shrink-0">
    <!-- back button -->
    <a href="<?= ROOT_DIR ?>" class="fa fa-angle-double-left btn bg-transparent btn-sm ms-2" style="font-size:36px"></a>

    <div class="container mt-1 mb-5 card shadow">
        <div class="row g-0">
            <!-- LEFT -->
            <div class="col-lg-6 border-end">
                <div class="d-flex flex-column justify-content-center">
                    <!-- main img -->
                    <div class="main_image">
                        <img src="<?= $product->getMainImg() ?>" id="main_product_image"  alt="main product image">
                    </div>
                    <!-- sub img -->
                    <div class="thumbnail_images d-flex align-content-center justify-content-center flex-wrap">
                        <!--TODO show all images-->
                        <img onclick="changeImage(this)" src="https://i.imgur.com/TAzli1U.jpg" alt="">
                        <img onclick="changeImage(this)" src="https://i.imgur.com/w6kEctd.jpg" alt="">
                        <img onclick="changeImage(this)" src="https://i.imgur.com/L7hFD8X.jpg" alt="">
                        <img onclick="changeImage(this)" src="https://i.imgur.com/6ZufmNS.jpg" alt="">
                        <img onclick="changeImage(this)" src="https://i.imgur.com/6ZufmNS.jpg" alt="">
                        <img onclick="changeImage(this)" src="https://i.imgur.com/6ZufmNS.jpg" alt="">
                    </div>
                </div>
            </div>
            <!-- RIGHT -->
            <div class="col-lg-6 p-3 right-side align-content-center h-100">
                <!-- category -->
                <p class="small mb-2"><a href="#" class="text-muted">Electronics > Laptops</a></p>
                <!-- TODO insert category -->
                <!-- title -->
                <h2><?= $product->getTitle() ?></h2>
                <!-- description -->
                <p class="mt-1 pr-3 content"><?= $product->getDescription() ?></p>
                <!-- price -->
                <h6 class="text-danger mb-0 pb-0"><s><?= $product->getOriginalPriceFormatted() ?>€</s></h6>
                <div class="d-flex align-items-start">
                    <h2 class="mb-0 col-auto me-2"><?= $product->getPriceFormatted(); ?>€</h2>
                    <h6 class="col-auto mt-auto mb-1">+ <?= $product->getShippingCostFormatted(); ?>€ Shipping</h6>
                </div>

                <!-- stars -->
                <div class="ratings d-flex flex-row align-items-center mt-3">
                    <p>
                        <?= ReviewController::getAvgRating($product->getId()) ?> Stars
                        <?php ReviewController::calcAndIncAvgProductStars($product->getId()) ?>
                        (<?= ReviewController::getNumberOfReviews($product->getId()) ?> reviews)
                    </p>
                </div>
                <!-- stock & buttons -->
                <div class="buttons d-flex flex-row gap-3">
                    <input class="form-control w-25" type="number" id="quantity" name="quantity" value="1" min="1" max="<?=$product->getStock()?>">
                    <button class="btn btn-dark">Add to Cart</button>
                    <label for="quantity"></label>                <!-- TODO make add to cart work-->
                </div>
                <p class="mb-0 ms-2 text-muted"><span class="fw-bold"><?= $product->getStock() ?></span> in Stock</p>
            </div>
        </div>
    </div>

    <!--TODO Reviews-->
    <!--TODO Tags/Categories-->
</main>

<!-- footer -->
<?php require INCLUDE_DIR . DIRECTORY_SEPARATOR . "site_footer.inc.php"; ?>

</body>
</html>