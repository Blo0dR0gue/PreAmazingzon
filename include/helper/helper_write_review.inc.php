<?php
require_once "../site_php_head.inc.php";

require_once CONTROLLER_DIR . DS . "controller_user.php";
require_once CONTROLLER_DIR . DS . "controller_review.php";

//Check if no user is logged-in or the logged-in user got blocked
UserController::redirectIfNotLoggedIn();

if(!isset($_POST["productId"]) || !is_numeric($_POST["productId"])){
    header("LOCATION: " . ROOT_DIR);
    die();
}

if (!isset($_POST["title"]) || !isset($_POST["rating"]) || !isset($_POST["description"]) ||
    !is_string($_POST["title"]) || !is_numeric($_POST["rating"]) || !is_string($_POST["description"])) {
    header("Location: " . PAGES_DIR . DS . "page_product_detail.php?id=".$_POST["productId"]);
    die();
}

$review = ReviewController::insert(
    htmlspecialchars($_POST["title"]),
    htmlspecialchars($_POST["description"]),
    $_POST["rating"],
    $_SESSION["uid"],
    $_POST["productId"]
);

if(!isset($review)){
    //TODO error
}

header("Location: " . PAGES_DIR . DS . "page_product_detail.php?id=".$_POST["productId"]);