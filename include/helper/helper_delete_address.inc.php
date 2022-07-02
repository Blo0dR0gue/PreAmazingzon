<?php
// Deletes an address
require_once "../site_php_head.inc.php";

// Check if no user is logged-in or the logged-in user got blocked
UserController::redirectIfNotLoggedIn();

//Are all parameters set?
if (!isset($_GET["addressId"]) || !is_numeric($_GET["addressId"]) || !isset($_GET["userId"]) || !is_numeric($_GET["userId"])) {
    logData("Delete Address", "Value is missing or does not have the correct datatype!", CRITICAL_LOG);
    // Go back to previous page, if it got set, else to the index.php
    if (isset($_SERVER["HTTP_REFERER"])) {
        header("Location: " . $_SERVER["HTTP_REFERER"]);
    } else {
        header("LOCATION: " . ROOT_DIR);
    }
    die();
}

//Is the session equals the passed user.
if ($_SESSION["uid"] != $_GET["userId"]) {
    logData("Delete Address", "Session user id is not the passed user id.", CRITICAL_LOG);

    // Go back to previous page, if it got set, else to the index.php
    if (isset($_SERVER["HTTP_REFERER"])) {
        header("Location: " . $_SERVER["HTTP_REFERER"]);
    } else {
        header("LOCATION: " . ROOT_DIR);
    }

    die();
}

$user = UserController::getById($_GET["userId"]);

//Does the user exist.
if (!isset($user)) {
    logData("Delete Address", "User with id: " . $_GET["id"] . " not found!", CRITICAL_LOG);
    header("LOCATION: " . PAGES_DIR . 'page_error.php?errorCode=500');
    die();
}

$address = AddressController::getById($_GET["addressId"]);

//Does the address exist.
if (!isset($address)) {
    logData("Delete Address", "Address with id: " . $_GET["addressId"] . " not found!", CRITICAL_LOG);
    header("LOCATION: " . PAGES_DIR . 'page_error.php?errorCode=500');
    die();
}

//Does the address belongs to the user.
if ($user->getId() != $address->getUserId()) {
    logData("Delete Address", "Address with id: " . $_POST["addressId"] . " does not belong to user with id: " . $user->getId(), CRITICAL_LOG);
    header("LOCATION: " . PAGES_DIR . 'page_error.php?errorCode=500');
    die();
}


$addresses = AddressController::getAllByUser($user->getId());

//Does the user have at least one more address?
if (!isset($addresses) || count($addresses) <= 1) {
    logData("Delete Address", "Address with id: " . $address->getId() . " could not be deleted!", CRITICAL_LOG);
    header("Location: " . USER_PAGES_DIR . "page_profile.php?message=You%20cant%20delete%20your%20last%20address");
    die();
}

//Is the address, which should be deleted the current default address?
if ($address->getId() == $user->getDefaultAddressId()) {
    //Update the default address to another one.

    $newDefaultAddress = $addresses[0];

    if ($newDefaultAddress->getId() == $user->getDefaultAddressId()) {
        $newDefaultAddress = $addresses[1];
    }

    $user->setDefaultAddressId($newDefaultAddress->getId());
    $user = $user->update();

    //Does the user could be updated?
    if (!isset($user)) {
        logData("Delete Address", "Address with id: " . $newDefaultAddress->getId() . " could not be set as default address for user with id: " . $user->getId(), CRITICAL_LOG);
        header("LOCATION: " . PAGES_DIR . 'page_error.php?errorCode=500');
        die();
    }
}

//Deletes the address
$success = $address->delete();

//Was the delete successful?
if (!$success) {
    logData("Delete Address", "Address with id: " . $address->getId() . " could not be deleted!", CRITICAL_LOG);
    header("LOCATION: " . PAGES_DIR . 'page_error.php?errorCode=500');
    die();
}

logData("Delete Address", "Address with id: " . $address->getId() . " got deleted!");

//Go back to the profile page.
header("Location: " . USER_PAGES_DIR . "page_profile.php?message=Address%20deleted");
die();