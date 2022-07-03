<?php
// Deletes a review
require_once "../site_php_head.inc.php";

UserController::redirectIfNotAdmin();

if (!isset($_GET["productId"]) || !is_numeric($_GET["productId"])) {

    logData("Delete Review", "Value productId is missing or does not have the correct datatype!", CRITICAL_LOG);

    // Go back to previous page, if it got set, else to the index.php
    if (isset($_SERVER["HTTP_REFERER"])) {
        header("Location: " . $_SERVER["HTTP_REFERER"]);
    } else {
        header("LOCATION: " . ROOT_DIR);
    }
    die();
}

if (!isset($_GET["id"]) || !is_numeric($_GET["id"])) {

    logData("Delete Review", "Value id is missing or does not have the correct datatype!", CRITICAL_LOG);

    // Go back to previous page, if it got set, else go back to the page_product_detail.php page
    if (isset($_SERVER["HTTP_REFERER"])) {
        header("Location: " . $_SERVER["HTTP_REFERER"]); // In this way, we can keep all set get parameters in the url
    } else {
        header("Location: " . PAGES_DIR . "page_product_detail.php?id=" . $_GET["productId"]);
    }
    die();
}

$review = ReviewController::getById($_GET["id"]);

if (!isset($review)) {

    logData("Delete Review", "Review with id: " . $_GET["id"] . " not found!", CRITICAL_LOG);

    // Go back to previous page, if it got set, else go back to the page_product_detail.php page
    if (isset($_SERVER["HTTP_REFERER"])) {
        header("Location: " . $_SERVER["HTTP_REFERER"]); // In this way, we can keep all set get parameters in the url
    } else {
        header("Location: " . PAGES_DIR . "page_product_detail.php?id=" . $_GET["productId"]);
    }
    die();
}

$suc = ReviewController::delete($review);

if (!$suc) {
    logData("Delete Review", "Review with id: " . $review->getId() . " could not be deleted!", CRITICAL_LOG);
    header("LOCATION: " . PAGES_DIR . 'page_error.php?errorCode=500');
    die();
}

logData("Delete Review", "Review with id: " . $_GET["id"] . "deleted!");

// Go back to previous page, if it got set, else go back to the page_product_detail.php page
if (isset($_SERVER["HTTP_REFERER"])) {
    header("Location: " . $_SERVER["HTTP_REFERER"]); // In this way, we can keep all set get parameters in the url
} else {
    header("Location: " . PAGES_DIR . "page_product_detail.php?id=" . $_GET["productId"]);
}
die();