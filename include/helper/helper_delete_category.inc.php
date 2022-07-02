<?php
// deletes a category
require_once "../site_php_head.inc.php";

UserController::redirectIfNotAdmin();

if (!isset($_GET["id"]) || !is_numeric($_GET["id"])) {

    logData("Delete Category", "Value id is missing or does not have the correct datatype!", CRITICAL_LOG);

    // Go back to previous page, if it got set, else to the index.php
    if (isset($_SERVER["HTTP_REFERER"])) {
        header("Location: " . $_SERVER["HTTP_REFERER"]);
    } else {
        header("LOCATION: " . ROOT_DIR);
    }
    die();
}

$category = CategoryController::getById($_GET["id"]);

if (!isset($category)) {

    logData("Delete Category", "Category with id: " . $_GET["id"] . " not found!", CRITICAL_LOG);

    // Go back to previous page, if it got set, else go back to the page_categories.php page
    if (isset($_SERVER["HTTP_REFERER"])) {
        header("Location: " . $_SERVER["HTTP_REFERER"]); // this way, we can keep all set get parameters in the url
    } else {
        header("Location: " . ADMIN_PAGES_DIR . "page_categories.php");
    }
    die();
}

$suc = CategoryController::delete($category);

if (!$suc) {
    logData("Delete Category", "Category with id: " . $category->getId() . " could not be deleted!", CRITICAL_LOG);
}

logData("Delete Category", "Category with id: " . $_GET["id"] . "deleted!");

// Go back to previous page, if it got set, else go back to the page_categories.php page
if (isset($_SERVER["HTTP_REFERER"])) {
    header("Location: " . $_SERVER["HTTP_REFERER"]); // this way, we can keep all set get parameters in the url
} else {
    header("Location: " . ADMIN_PAGES_DIR . "page_categories.php");
}
die();