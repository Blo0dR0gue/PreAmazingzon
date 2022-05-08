<!-- TODO COMMENT -->
<?php
require_once "../include/site_php_head.inc.php";

require_once CONTROLLER_DIR . DIRECTORY_SEPARATOR . 'controller_product.php';
require_once CONTROLLER_DIR . DIRECTORY_SEPARATOR . 'controller_review.php';

$productID = $_GET["id"];   //TODO htmlspecialchars?
if(isset($productID) && is_numeric($productID)){
    $product = ProductController::getProductById(intval($productID));

    if(!isset($product)) header("LOCATION: " . ROOT_DIR );   //Redirect, if no product is found.

}else{
    header("LOCATION: " . ROOT_DIR );   //Redirect, if no number is passed.
}

?>

<!DOCTYPE html>
<html class="h-100" lang="en">
<head>
    <?php require_once INCLUDE_DIR . DIRECTORY_SEPARATOR . "site_html_head.inc.php"; ?>
    <title><?= PAGE_NAME ?> - Product Details - <?=$product->getTitle();?></title>
</head>

<body class="d-flex flex-column h-100">
<!--header -->
<?php require INCLUDE_DIR . DIRECTORY_SEPARATOR . "site_header.inc.php"; ?>

<!-- main body -->
<main class="flex-shrink-0">

    <section class="container" id="productDetails">
        <div class="card">
            <div class="card-header">
                <h2><?= $product->getTitle(); ?></h2>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-4">
                        <img src="<?= $product->getMainImg(); ?>" class="card-img-top" alt="produkt"> <!--TODO show all images-->

                    </div>
                    <div class="col-8">
                        <?=ReviewController::getAvgRating($product->getId())?> Stars
                        <?=ReviewController::calcAndIncAvgProductStars($product->getId())?>
                        <hr/>
                        <div>Preis: <b><?= $product->getPriceFormatted(); ?> €</b></div>
                        <hr/>
                        <div><?= $product->getDescription() ?></div>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <a href="<?=ROOT_DIR?>" class="btn btn-primary btn-sm">Zurück zum Schop</a>
                <a href="#" class="btn btn-success btn-sm">In den Warenkorb</a>
            </div>
            <!--TODO Reviews-->
        </div>
    </section>

</main>

<!-- footer -->
<?php require INCLUDE_DIR . DIRECTORY_SEPARATOR . "site_footer.inc.php"; ?>

</body>
</html>