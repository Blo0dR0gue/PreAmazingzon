<?php
require_once "../site_php_head.inc.php";

require_once CONTROLLER_DIR . DS . "controller_user.php";

//Check if current logged-in user is an admin
UserController::redirectIfNotAdmin();

if(!isset($_POST["title"]) || !isset($_POST["rating"]) || !isset($_POST["description"])){
    header("Location: " . PAGES_DIR . DS . "page_products.php");    //TODO redirect back to where we came from
    die();
}

