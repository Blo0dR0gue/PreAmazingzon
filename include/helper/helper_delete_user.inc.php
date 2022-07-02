<?php
// Deletes a user
require_once "../site_php_head.inc.php";

UserController::redirectIfNotAdmin();

if (!isset($_GET["id"]) || !is_numeric($_GET["id"])) {

    logData("Delete User", "Value id is missing or does not have the correct datatype!", LOG_CRITICAL);

    // Go back to previous page, if it got set, else to the index.php
    if (isset($_SERVER["HTTP_REFERER"])) {
        header("Location: " . $_SERVER["HTTP_REFERER"]);
    } else {
        header("LOCATION: " . ROOT_DIR);
    }
    die();
}

$user = UserController::getById($_GET["id"]);

if (!isset($user)) {

    logData("Delete User", "User wit id: " . $_GET["id"] . " not found!", LOG_CRITICAL);

    // Go back to previous page, if it got set, else go back to the page_users.php page
    if (isset($_SERVER["HTTP_REFERER"])) {
        header("Location: " . $_SERVER["HTTP_REFERER"]); // In this way, we can keep all set get parameters in the url
    } else {
        header("Location: " . ADMIN_PAGES_DIR . "page_users.php");
    }
    die();
}

$suc = UserController::delete($user);

if(!$suc){
    logData("Delete User", "User wit id: " . $user->getId() . " could not be deleted!", LOG_CRITICAL);
}

logData("Delete User", "User with id: " . $_GET["id"] . "deleted!");

// Go back to previous page, if it got set, else go back to the page_users.php page
if (isset($_SERVER["HTTP_REFERER"])) {
    header("Location: " . $_SERVER["HTTP_REFERER"]); // In this way, we can keep all set get parameters in the url
} else {
    header("Location: " . ADMIN_PAGES_DIR . "page_users.php");
}
die();