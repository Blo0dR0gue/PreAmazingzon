<?php
// Create a new review
require_once "../site_php_head.inc.php";

// Check if no user is logged-in or the logged-in user got blocked
UserController::redirectIfNotLoggedIn();

if (!isset($_POST["productId"]) || !is_numeric($_POST["productId"])) {
    logData("Write Review", "Value productId missing or does not have the correct datatype!", CRITICAL_LOG);
    header("LOCATION: " . ROOT_DIR);
    die();
}

if (!isset($_POST["title"]) || !isset($_POST["rating"]) || !isset($_POST["description"]) ||
    !is_string($_POST["title"]) || !is_numeric($_POST["rating"]) || !is_string($_POST["description"])) {
    logData("Write Review", "Value is missing or does not have the correct datatype!", CRITICAL_LOG);
    header("Location: " . PAGES_DIR . "page_product_detail.php?id=" . $_POST["productId"]);
    die();
}

$review = ReviewController::insert(
    htmlspecialchars($_POST["title"]),
    htmlspecialchars($_POST["description"]),
    $_POST["rating"],
    $_SESSION["uid"],
    $_POST["productId"]
);

if (!isset($review)) {
    logData("Write Review", "Review for user: " . $_SESSION["uid"] . " for product " . $_POST["productId"] . " could not be created!", CRITICAL_LOG);
    header("LOCATION: " . PAGES_DIR . 'page_error.php?errorCode=500');
    die();
}

logData("Write Review", "User with id " . $_SESSION["uid"] . " wrote the review " . $review->getId() . ".");
header("Location: " . PAGES_DIR . "page_product_detail.php?id=" . $_POST["productId"]);