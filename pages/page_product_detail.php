<!-- TODO COMMENT -->
<?php require_once "../include/site_php_head.inc.php" ?>

<!-- TODO do includes uniformly? in head? -->
<?php
require_once CONTROLLER_DIR . DS . 'controller_product.php';
require_once CONTROLLER_DIR . DS . 'controller_review.php';
require_once CONTROLLER_DIR . DS . 'controller_category.php';
require_once CONTROLLER_DIR . DS . 'controller_user.php';
?>

<?php // get product
$productID = $_GET["id"];   //TODO html special chars?
if (isset($productID) && is_numeric($productID)) {
    $product = ProductController::getByID(intval($productID));

    if (!isset($product)) {
        header("LOCATION: " . ROOT_DIR);   //Redirect, if no product is found.
        die();
    }
} else {
    header("LOCATION: " . ROOT_DIR);   //Redirect, if no number is passed.
    die();
}

//TODO vereinheitlichen und in dyn_pagination.inc.php auslagern
$page = (isset($_GET['page']) && is_numeric($_GET['page'])) ? $_GET['page'] : 1;    // Current pagination page number
$offset = ($page - 1) * LIMIT_OF_SHOWED_ITEMS;      // Calculate offset for pagination
$reviewCount = ReviewController::getAmountOfReviewsForProduct($product->getId());      // Get the total Amount of Reviews
$totalPages = ceil($reviewCount / LIMIT_OF_SHOWED_ITEMS);        // Calculate the total amount of pages

?>

<!DOCTYPE html>
<html class="h-100" lang="en">
<head>
    <?php require_once INCLUDE_DIR . DS . "site_html_head.inc.php"; ?>
    <title><?= PAGE_NAME ?> - <?= $product->getTitle(); ?></title>

    <!-- file specific includes -->
    <link rel="stylesheet" href="<?= STYLE_DIR . DS . "style_product_detail.css"; ?>">
    <script src="<?= SCRIPT_DIR . DS . "page_product_detail.js"; ?>"></script>
</head>

<body class="d-flex flex-column h-100">
<!-- header -->
<?php require INCLUDE_DIR . DS . "site_header.inc.php"; ?>

<!-- main body -->
<main class="flex-shrink-0">
    <!-- back button -->
    <a href="javascript:history.back()" class="fa fa-angle-double-left btn bg-transparent btn-sm ms-2"
       style="font-size:36px"></a>

    <div class="container mt-1 mb-5 card shadow">
        <div class="row g-0 border-bottom">
            <!-- LEFT -->
            <div class="col-lg-6 border-end">
                <div class="d-flex flex-column justify-content-center">
                    <!-- main img -->
                    <div class="main_image">
                        <img src="<?= $product->getMainImg() ?>" id="main_product_image" alt="main product image">
                    </div>
                    <!-- sub img -->
                    <div class="thumbnail_images d-flex align-content-center justify-content-center flex-wrap">
                        <?php
                        $allIMGs = array_slice($product->getAllImgs(), 0, MAX_IMAGE_PER_PRODUCT);

                        foreach ($allIMGs as $img) {
                            echo "<div class='thumbnail_image'><img onclick='changeImage(this)' src='{$img}' alt=''></div>";
                        }
                        ?>
                    </div>
                </div>
            </div>
            <!-- RIGHT -->
            <div class="col-lg-6 p-3 right-side align-content-center h-100">
                <!-- category -->
                <p class="small mb-2"><a href="#"
                                         class="text-muted"><?= CategoryController::getPathToCategoryL($product->getCategoryID()); ?></a>
                    <!-- TODO make link work -->
                    <!-- TODO no category string? -->
                </p>
                <!-- title -->
                <h2><?= $product->getTitle() ?></h2>

                <!-- description -->
                <p class="mt-1 pr-3 content"><?= $product->getDescription() ?></p>

                <!-- price -->
                <h6 class="text-danger mb-0 pb-0"><s><?= $product->getOriginalPriceFormatted() ?></s></h6>
                <div class="d-flex align-items-start">
                    <h2 class="mb-0 col-auto me-2"><?= $product->getPriceFormatted() ?></h2>
                    <h6 class="col-auto mt-auto mb-1">+ <?= $product->getShippingCostFormatted() ?> Shipping</h6>
                </div>

                <!-- stars -->
                <div class="ratings d-flex flex-row align-items-center mt-3">
                    <p>
                        <?= ReviewController::getAvgRating($product->getId()) ?> Stars
                        <?php ReviewController::calcAndIncAvgProductStars($product->getId()) ?>
                        (<?= ReviewController::getNumberOfReviewsForProduct($product->getId()) ?> reviews)
                    </p>
                </div>

                <!-- stock & buttons -->
                <form method="get"
                      action="<?= INCLUDE_HELPER_DIR . DS . "helper_shoppingcart.inc.php" ?>">
                    <!-- helper values -->
                    <input type="hidden" name="action" value="add">
                    <input type="hidden" name="productId" value="<?= $product->getId() ?>">

                    <div class="buttons d-flex flex-row gap-3">
                        <label class="d-none" for="quantity"></label>
                        <input class="form-control w-25" type="number" id="quantity" name="quantity" value="1" min="1"
                               max="<?= $product->getStock() ?>">
                        <button type="submit" class="btn btn-warning">Add to Cart</button>
                    </div>
                    <p class="mb-0 ms-2 text-muted"><span class="fw-bold"><?= $product->getStock() ?></span> in Stock
                    </p>
                </form>
            </div>
        </div>

        <!-- Related products-->
        <div class="row g-0 border-bottom p-3">
            <h4 class="mt-2">Related products to this article</h4>
        </div>

        <!-- Reviews -->
        <div class="row g-0 border-bottom p-3">
            <!-- LEFT -->
            <div class="col-lg-3 border-end">
                <h4 class="mt-2" id="review_header">Customer Reviews</h4>

            </div>
            <!-- RIGHT -->
            <div class="col-lg-9 p-3 right-side align-content-center h-100">

                <?php if (isset($_SESSION["login"]) && $_SESSION["login"] && isset($_SESSION["uid"])): ?>  <!--TODO check if user bought this item-->

                    <div class="p-3 right-side align-content-center h-100 border-bottom">
                        <button class="btn btn-sm btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#collapseRating" aria-expanded="false" aria-controls="collapseExample">
                            Write a review
                        </button>
                        <form action="<?= INCLUDE_HELPER_DIR . DS . "helper_write_review.inc.php"; ?>" method="post" class="collapse" id="collapseRating">

                            <div class="form-group position-relative">
                                <label for="title">Title</label>
                                <input type="text" value="" name="title" id="title" class="form-control">
                            </div>

                            <div class="form-group position-relative">
                                <label>Rating</label>
                                <div id="ratings d-flex flex-row align-items-center mt-3">
                                    <div class="rating-group">
                                        <input class="rating__input rating__input--none" name="rating" id="rating-none" value="0" type="radio">
                                        <label aria-label="No rating" class="rating__label" for="rating-none"><i class="rating__icon rating__icon--none fa fa-ban"></i></label>
                                        <label aria-label="1 star" class="rating__label" for="rating-1"><i class="rating__icon rating-color fa fa-star"></i></label>
                                        <input class="rating__input" name="rating" id="rating-1" value="1" type="radio">
                                        <label aria-label="2 stars" class="rating__label" for="rating-2"><i class="rating__icon rating-color fa fa-star"></i></label>
                                        <input class="rating__input" name="rating" id="rating-2" value="2" type="radio">
                                        <label aria-label="3 stars" class="rating__label" for="rating-3"><i class="rating__icon rating-color fa fa-star"></i></label>
                                        <input class="rating__input" name="rating" id="rating-3" value="3" type="radio" checked>
                                        <label aria-label="4 stars" class="rating__label" for="rating-4"><i class="rating__icon rating-color fa fa-star"></i></label>
                                        <input class="rating__input" name="rating" id="rating-4" value="4" type="radio">
                                        <label aria-label="5 stars" class="rating__label" for="rating-5"><i class="rating__icon rating-color fa fa-star"></i></label>
                                        <input class="rating__input" name="rating" id="rating-5" value="5" type="radio">
                                    </div>
                                </div>
                            </div>

                            <div class="form-group position-relative">
                                <label for="description">Description</label>
                                <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                            </div>
                            <br>
                            <button class="w-100 btn btn-sm btn-primary" type="submit">Save Review</button>

                        </form>
                    </div>

                <?php endif; ?>


                <?php if ($reviewCount > 0): ?>
                    <?php foreach (ReviewController::getReviewsForProductInRange($product->getId(), $offset, LIMIT_OF_SHOWED_ITEMS) as $review): ?>
                        <?php $user = UserController::getById($review->getUserId()); ?>
                        <div class="p-3 right-side align-content-center h-100 border-bottom">
                            <p class="mt-1 pr-3 content">Author: <?= UserController::getFormattedName($user); ?></p>
                            <div class="ratings d-flex flex-row align-items-center mt-3">
                                <p>
                                    Rating: <?php ReviewController::calcAndIncProductStars($review) ?>
                                </p>
                            </div>

                            <h4 class=""><u><?= $review->getTitle(); ?></u></h4>
                            <p class="mt-1 pr-3 content"><?= $review->getText(); ?></p>

                        </div>

                    <?php endforeach; ?>
                <?php else: ?>

                    <h5 class='text-center text-muted mb-5'><i>No reviews found. Be the first.</i></h5>

                <?php endif; ?>
                <!-- pagination -->
                <div class="p-3">
                    <?php require INCLUDE_DIR . DS . "dyn_pagination.inc.php" ?>
                </div>
            </div>
        </div>
    </div>

</main>

<!-- footer -->
<?php require INCLUDE_DIR . DS . "site_footer.inc.php"; ?>

</body>
</html>