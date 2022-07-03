<?php
// Create / Edits an address.

require_once "../site_php_head.inc.php";

// Check if no user is logged-in or the logged-in user got blocked
UserController::redirectIfNotLoggedIn();

if (!isset($_POST["zip"]) || !is_string($_POST["zip"]) || strlen($_POST["zip"]) != 5 || !isset($_POST["city"]) ||
    !is_string($_POST["city"]) || !isset($_POST["street"]) || !is_string($_POST["street"]) || !isset($_POST["number"]) ||
    !is_string($_POST["number"]) || !isset($_POST["addressId"]) || !is_numeric($_POST["addressId"]) || !isset($_POST["userId"]) ||
    !is_numeric($_POST["userId"])) {
    logData("Add/Edit Address", "Value is missing or does not have the correct datatype!", CRITICAL_LOG);
    header("LOCATION: " . PAGES_DIR . 'page_error.php?errorCode=500');
    die();
}

if ($_POST["userId"] == -1) {
    logData("Add/Edit Address", "No valid user id passed. (-1)", WARNING_LOG);
    header("LOCATION: " . PAGES_DIR . 'page_error.php?errorCode=500');
    die();
}

$user = UserController::getById($_POST["userId"]);

if (!isset($user)) {
    logData("Add/Edit Address", "User with id: " . $_POST["userId"] . " not found.", WARNING_LOG);
    header("LOCATION: " . PAGES_DIR . 'page_error.php?errorCode=500');
    die();
}

if ($_POST["addressId"] == -1) {
    // Create a new address

    $addresses = AddressController::getAllByUser($user->getId());

    if (!isset($addresses)) {
        logData("Add Address", "No addresses were found for user with id: " . $user->getId(), WARNING_LOG);
        header("LOCATION: " . PAGES_DIR . 'page_error.php?errorCode=500');
        die();
    }

    if (count($addresses) >= MAX_AMOUNT_OF_ADDRESSES_PER_USER) {
        logData("Add Address", "User with id: " . $user->getId() . " reached the max address amount. Address not created.", DEBUG_LOG);
        header("Location: " . USER_PAGES_DIR . "page_profile.php?message=Max%20amount%20of%20addresses%20reached%20" . MAX_AMOUNT_OF_ADDRESSES_PER_USER);
        die();
    }

    $address = new Address(
        0,
        htmlspecialchars($_POST["street"], ENT_QUOTES, "UTF-8"),
        htmlspecialchars($_POST["number"], ENT_QUOTES, "UTF-8"),
        htmlspecialchars($_POST["zip"], ENT_QUOTES, "UTF-8"),
        htmlspecialchars($_POST["city"], ENT_QUOTES, "UTF-8"),
        $user->getId()
    );

    $address = $address->insert();

    // Create failed
    if (!isset($address)) {
        logData("Add Address", "New address could not be created!", CRITICAL_LOG);
        header("LOCATION: " . PAGES_DIR . 'page_error.php?errorCode=500');
        die();
    }

} else {
    // Update a address

    $address = AddressController::getById($_POST["addressId"]);

    if (!isset($address)) {
        logData("Edit Address", "Address with id: " . $_POST["addressId"] . " not found.", WARNING_LOG);
        header("LOCATION: " . PAGES_DIR . 'page_error.php?errorCode=500');
        die();
    }

    if ($address->getUserId() != $user->getId()) {
        logData("Edit Address", "Address with id: " . $_POST["addressId"] . " does not belong to user with id: " . $user->getId(), CRITICAL_LOG);
        header("LOCATION: " . PAGES_DIR . 'page_error.php?errorCode=500');
        die();
    }

    $address->setCity(htmlspecialchars($_POST["city"], ENT_QUOTES, "UTF-8"));
    $address->setZip(htmlspecialchars($_POST["zip"], ENT_QUOTES, "UTF-8"));
    $address->setNumber(htmlspecialchars($_POST["number"], ENT_QUOTES, "UTF-8"));
    $address->setStreet(htmlspecialchars($_POST["street"], ENT_QUOTES, "UTF-8"));

    $address = $address->update();

    // Update failed
    if (!isset($address)) {
        logData("Edit Address", "Address with id: " . $_POST["addressId"] . " could not be updated!", CRITICAL_LOG);
        header("LOCATION: " . PAGES_DIR . 'page_error.php?errorCode=500');
        die();
    }

}

if ($_POST["addressId"] == -1) {
    logData("Add Address", "Address with id: " . $address->getId() . " got created.");
} else {
    logData("Edit Address", "Address with id: " . $address->getId() . " got updated.");
}

header("Location: " . USER_PAGES_DIR . "page_profile.php?message=Address%20saved");