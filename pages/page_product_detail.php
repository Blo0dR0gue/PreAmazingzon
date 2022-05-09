<!-- TODO COMMENT -->
<?php
require_once "../include/site_php_head.inc.php";

require_once CONTROLLER_DIR . DIRECTORY_SEPARATOR . 'controller_product.php';
require_once CONTROLLER_DIR . DIRECTORY_SEPARATOR . 'controller_review.php';

$productID = $_GET["id"];   //TODO htmlspecialchars?
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
    <title><?= PAGE_NAME ?> - Product Details - <?= $product->getTitle(); ?></title>

    <link rel="stylesheet" href="<?= STYLE_DIR . DIRECTORY_SEPARATOR . "style_product_detail.css"; ?>">
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
                        <img src="<?= $product->getMainImg(); ?>" class="card-img-top" alt="produkt">
                        <!--TODO show all images-->

                    </div>
                    <div class="col-8">
                        <?= ReviewController::getAvgRating($product->getId()) ?> Stars
                        <?php ReviewController::calcAndIncAvgProductStars($product->getId()) ?>
                        <hr/>
                        <div>Price: <b><?= $product->getPriceFormatted(); ?> €</b></div>
                        <div>Shipping Cost: <b><?= $product->getShippingCostFormatted(); ?> €</b></div>
                        <hr/>
                        <div><?= $product->getDescription() ?></div>
                        <hr/>
                        <div><?= $product->getStock() ?> Items in Stock</div>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <a href="<?= ROOT_DIR ?>" class="btn btn-primary btn-sm">Back to the Shop</a>
                <a href="#" class="btn btn-success btn-sm">Add to Card</a> <!--TODO amount?-->
            </div>

            <!--TODO Reviews-->
            <!--TODO Tags/Categories-->
<!--            TODO show categories? -->
        </div>
    </section>


    <script>
        function changeImage(element) {
            const main_product_image = document.getElementById('main_product_image');
            main_product_image.src = element.src;
        }
    </script>


    <div class="container mt-5 mb-5">
        <div class="card">
            <div class="row g-0">
                <div class="col-lg-6 border-end">
                    <div class="d-flex flex-column justify-content-center">
                        <div class="main_image">
                            <img src="https://i.imgur.com/TAzli1U.jpg" id="main_product_image" width="350">
                        </div>
                        <div class="thumbnail_images">
                            <ul id="thumbnail">
                                <li><img onclick="changeImage(this)" src="https://i.imgur.com/TAzli1U.jpg" width="70"></li>
                                <li><img onclick="changeImage(this)" src="https://i.imgur.com/w6kEctd.jpg" width="70"></li>
                                <li><img onclick="changeImage(this)" src="https://i.imgur.com/L7hFD8X.jpg" width="70"></li>
                                <li><img onclick="changeImage(this)" src="https://i.imgur.com/6ZufmNS.jpg" width="70"></li>
                                <li><img onclick="changeImage(this)" src="https://i.imgur.com/6ZufmNS.jpg" width="70"></li>
                                <li><img onclick="changeImage(this)" src="https://i.imgur.com/6ZufmNS.jpg" width="70"></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="p-3 right-side h-100">
                        <div class="d-flex justify-content-between align-items-center">
                            <h2>IIana</h2>
                        </div>
                        <p class="mt-2 pr-3 content">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua</p>
                        <h3>$430.99</h3>
                        <h6>+ $430.99 Shipping</h6>
                        <div class="ratings d-flex flex-row align-items-center">
                            <p>
                                <?= ReviewController::getAvgRating($product->getId()) ?> Stars
                                <?php ReviewController::calcAndIncAvgProductStars($product->getId()) ?>
                                xyz Reviews
                            </p>

                        </div>
                        <p>xzy in stock</p>
                        <div class="buttons d-flex flex-row gap-3">
                            <input class="form-control w-25" type="number" id="quantity" name="quantity" value="1" min="1">
                            <button class="btn btn-dark">Add to Basket</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<!-- footer -->
<?php require INCLUDE_DIR . DIRECTORY_SEPARATOR . "site_footer.inc.php"; ?>

</body>
</html>