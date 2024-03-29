<!-- A detailed product -->

<?php require_once "../include/site_php_head.inc.php" ?>

<?php
// get product
$productID = $_GET["id"];
if (isset($productID) && is_numeric($productID)) {

    $product = ProductController::getByID(intval($productID));

    if (!isset($product)) {
        // Product with id does not exist
        logData("Detailed Product", "Product with id: " . $productID . " does not exist", DEBUG_LOG);
        header("LOCATION: " . ROOT_DIR);   // Redirect, if no product is found.
        die();
    }

    // Product not active
    if (!$product->isActive()) {
        // Session is not an admin
        if (!UserController::isCurrentSessionAnAdmin()) {
            logData("Detailed Product", "User with id: " . $_SESSION["uid"] . " tried to access inactive product with id: " . $productID, DEBUG_LOG);
            header("LOCATION: " . ROOT_DIR);   // Redirect, if product is inactive and the user is not an admin.
            die();
        }
    }
} else {
    // No product id passed.
    logData("Detailed Product", "No product id passed", DEBUG_LOG);
    header("LOCATION: " . ROOT_DIR);   // Redirect, if no number is passed.
    die();
}

// pagination init
$page = (isset($_GET['page']) && is_numeric($_GET['page'])) ? $_GET['page'] : 1;    // Current pagination page number
$offset = ($page - 1) * LIMIT_OF_SHOWED_ITEMS;      // Calculate offset for pagination
$reviewCount = ReviewController::getAmountOfReviewsForProduct($product->getId());      // Get the total Amount of Reviews
$totalPages = ceil($reviewCount / LIMIT_OF_SHOWED_ITEMS);        // Calculate the total amount of pages

// Load required data
$reviewStats = ReviewController::getStatsForEachStarForAProduct($product->getId());
$avgRating = ReviewController::getAvgRating($product->getId());
?>

<!DOCTYPE html>
<html class="h-100" lang="en">
<head>
    <?php require_once INCLUDE_DIR . "site_html_head.inc.php"; ?>
    <title><?= PAGE_NAME ?> - <?= $product->getTitle(); ?></title>

    <!-- file specific includes -->
    <link rel="stylesheet" href="<?= STYLE_DIR . "style_product_detail.css"; ?>">
    <script src="<?= SCRIPT_DIR . "page_product_detail.js"; ?>"></script>
</head>

<body class="d-flex flex-column h-100">
<!-- header -->
<?php require_once INCLUDE_DIR . "site_header.inc.php"; ?>

<!-- main body -->
<main class="flex-shrink-0">
    <!-- back button -->
    <a href="javascript:history.back()" class="fa fa-angle-double-left btn bg-transparent btn-sm ms-2"
       style="font-size:36px"></a>

    <div class="container mt-1 mb-5 card shadow">
        <!-- SECTION product info -->
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
                            echo "<div class='thumbnail_image'><img onclick='changeImage(this)' src='$img' alt=''></div>";
                        }
                        ?>
                    </div>
                </div>
            </div>
            <!-- RIGHT -->
            <div class="col-lg-6 p-3 right-side align-content-center h-100">
                <!-- category -->
                <p class="small mb-2">
                    <a href="<?= PAGES_DIR . 'page_categories.php?id=' . $product->getCategoryID() ?>"
                       class="text-muted"><?= CategoryController::getPathToCategory($product->getCategoryID()); ?></a>
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
                        <?= $avgRating ?> Stars
                        <?= ReviewController::calcAndIncAvgProductStars($product->getId()) ?>
                        (<?= ReviewController::getAmountOfReviewsForProduct($product->getId()) ?> reviews)
                    </p>
                </div>

                <!-- stock & buttons -->
                <form method="get" action="<?= INCLUDE_HELPER_DIR . "helper_shopping_cart.inc.php" ?>">
                    <!-- helper values -->
                    <input type="hidden" name="action" value="add">
                    <input type="hidden" name="productId" value="<?= $product->getId() ?>">

                    <div class="buttons d-flex flex-row gap-3">
                        <label class="d-none" for="quantity"></label>
                        <input class="form-control w-25" type="number" id="quantity" name="quantity" value="1" min="1"
                               max="<?= $product->getStock() ?>">
                        <button type="submit"
                                class="btn btn-warning"<?= $product->getStock() === 0 ? "disabled" : "" ?>>
                            Add to Cart
                        </button>
                    </div>
                    <p class="mb-0 ms-2 text-muted">
                        <span class="fw-bold"><?= $product->getStock() ?></span> in Stock
                    </p>
                </form>
            </div>
        </div>

        <!-- SECTION reviews -->
        <div class="row g-0 border-bottom">
            <!-- LEFT -->
            <div class="col-lg-3 border-end ps-3 pt-3">
                <h4 class="mt-2" id="review_header">Customer Reviews</h4>
                <!-- star distribution -->
                <div class="mb-4">
                    <?php for ($i = 5; $i > -1; $i--): // for each star rating ?>
                        <div class="row mx-1 px-0">
                            <div class="progress mt-1 col-8 px-0" data-bs-toggle="tooltip" data-bs-placement="right"
                                 title="<?php echo $reviewStats[$i]["amount"] . ' vote(s) = ' . $reviewStats[$i]["percentage"] . '%'; ?>">
                                <div class="progress-bar bg-warning" role="progressbar"
                                     style="width: <?php echo $reviewStats[$i]["percentage"]; ?>%"
                                     aria-valuenow="<?php echo $reviewStats[$i]["percentage"]; ?>" aria-valuemin="0"
                                     aria-valuemax="100">
                                </div>
                            </div>
                            <p class="col-sm text-decoration-none mb-0"><?= $i . ($i === 1 ? " Star" : " Stars") ?></p>
                        </div>
                    <?php endfor; ?>
                </div>
            </div>

            <!-- RIGHT -->
            <div class="col-lg-9 right-side align-content-center h-100 pt-2">
                <!-- Only allow to write a review, if the user is logged in, already bought this item once -->
                <?php if (UserController::isCurrentSessionLoggedIn() && ProductOrderController::doesUserBoughtThisProduct($_SESSION["uid"], $product->getId())): ?>

                    <div class="p-3 right-side align-content-center h-100">
                        <button class="btn btn-sm btn-secondary" type="button" data-bs-toggle="collapse"
                                data-bs-target="#collapseRating" aria-expanded="false"
                                aria-controls="collapseExample">
                            Write a review
                        </button>
                        <!-- collapsable form -->
                        <form action="<?= INCLUDE_HELPER_DIR . "helper_write_review.inc.php"; ?>" method="post"
                              class="collapse needs-validation" id="collapseRating" novalidate>
                            <input type="hidden" value="<?= $product->getId(); ?>" name="productId">

                            <!-- Review title -->
                            <div class="form-group position-relative mt-2">
                                <label for="title">Title</label>
                                <input type="text" value="" name="title" id="title" class="form-control" required
                                       pattern="[a-zäöüA-ZÄÖÜ0-9 ,.'-:]{5,}">
                                <div class="invalid-tooltip opacity-75">
                                    Please enter a valid Title! (min. 5 characters, no special characters)
                                </div>
                            </div>

                            <!-- Stare rating for the review -->
                            <div class="form-group position-relative">
                                <label>Rating</label>
                                <div id="ratings d-flex flex-row align-items-center mt-3">
                                    <div class="rating-group">
                                        <input class="rating__input rating__input--none" name="rating"
                                               id="rating-none" value="0" type="radio" required>
                                        <label aria-label="No rating" class="rating__label" for="rating-none">
                                            <em class="rating__icon rating__icon--none fa fa-ban"></em></label>
                                        <label aria-label="1 star" class="rating__label" for="rating-1">
                                            <em class="rating__icon rating-color fa fa-star"></em></label>
                                        <input class="rating__input" name="rating" id="rating-1" value="1" type="radio">
                                        <label aria-label="2 stars" class="rating__label" for="rating-2">
                                            <em class="rating__icon rating-color fa fa-star"></em></label>
                                        <input class="rating__input" name="rating" id="rating-2" value="2" type="radio">
                                        <label aria-label="3 stars" class="rating__label" for="rating-3">
                                            <em class="rating__icon rating-color fa fa-star"></em></label>
                                        <input class="rating__input" name="rating" id="rating-3" value="3" type="radio"
                                               checked>
                                        <label aria-label="4 stars" class="rating__label" for="rating-4">
                                            <em class="rating__icon rating-color fa fa-star"></em></label>
                                        <input class="rating__input" name="rating" id="rating-4" value="4" type="radio">
                                        <label aria-label="5 stars" class="rating__label" for="rating-5">
                                            <em class="rating__icon rating-color fa fa-star"></em></label>
                                        <input class="rating__input" name="rating" id="rating-5" value="5" type="radio">
                                    </div>
                                </div>
                            </div>

                            <!-- Review text -->
                            <div class="form-group position-relative">
                                <label for="description">Description</label>
                                <textarea class="form-control" id="description" name="description" rows="3"
                                          required></textarea>
                                <div class="invalid-tooltip opacity-75">Please enter a valid description!</div>
                            </div>
                            <br>

                            <button class="w-100 btn btn-sm btn-primary" type="submit">Save Review</button>

                        </form>
                    </div>
                <?php endif; ?>

                <?php if ($reviewCount > 0): ?>
                    <!-- Show all reviews in range -->
                    <?php foreach (ReviewController::getReviewsForProductInRange($product->getId(), $offset, LIMIT_OF_SHOWED_ITEMS) as $review): ?>
                        <?php $user = UserController::getById($review->getUserId()); ?>
                        <div class="p-3 right-side align-content-center h-100 border-bottom">
                            <div class="ratings d-flex flex-row align-items-center ">
                                <p class="mb-1 me-3"><u>Author</u>: <?= UserController::getFormattedName($user); ?></p>
                                <p class="mb-1 me-3">
                                    <u>Rating</u>: <?= ReviewController::calcAndIncProductStars($review) ?>
                                </p>
                                <?php if (UserController::isCurrentSessionAnAdmin()): ?>
                                    <!-- Admins can delete this review -->
                                    <a href="<?= INCLUDE_HELPER_DIR . "helper_delete_review.inc.php?id=" . $review->getId() . "&productId=" . $product->getId(); ?>"
                                       class="btn btn-danger btn-sm ms-auto">
                                        <em class="fa fa-trash "></em> Delete review
                                    </a>
                                <?php endif; ?>
                            </div>

                            <!-- Review title -->
                            <h4 class=""><?= $review->getTitle(); ?></h4>

                            <!-- Review text -->
                            <p class="mt-1 mb-0"><?= $review->getText(); ?></p>
                        </div>

                    <?php endforeach; ?>

                <?php elseif (UserController::isCurrentSessionLoggedIn() && ProductOrderController::doesUserBoughtThisProduct($_SESSION["uid"], $product->getId())): ?>
                    <!-- There is no review and the user bought this item -->
                    <h5 class='text-center text-muted my-3'>
                        <em>No reviews found. Be the first.</em>
                    </h5>
                <?php elseif (UserController::isCurrentSessionLoggedIn()): ?>
                    <h5 class='text-center text-muted my-3'>
                        <em>No reviews found.<br>You must have purchased the product at least once to write a review</em>
                    </h5>
                <?php else: ?>
                    <h5 class='text-center text-muted my-3'><em>No reviews found. Login and be the first.</em></h5>
                <?php endif; ?>

                <!-- pagination -->
                <div class="p-3">
                    <?php require INCLUDE_DIR . "dyn_pagination.inc.php" ?>
                </div>
            </div>
        </div>
    </div>

    <!-- load custom form validation script -->
    <script src="<?= SCRIPT_DIR . "form_validation.js" ?>"></script>
    <!-- enable tooltips on this page (by default disabled for performance)-->
    <script src="<?= SCRIPT_DIR . "tooltip_enable.js" ?>"></script>

</main>

<!-- footer -->
<?php require_once INCLUDE_DIR . "site_footer.inc.php"; ?>

</body>
</html>