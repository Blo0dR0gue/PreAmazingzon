<?php require_once "../../include/site_php_head.inc.php" ?>

<?php
//if not logged in redirect to home ?
UserController::redirectIfNotLoggedIn();

// get user
$user = UserController::getById($_SESSION["uid"]);
if (!$user) {   // user could be found?
    logData("Profile", "User not found! Id: " . $_SESSION["uid"], CRITICAL_LOG);
    show_popup(
        "Error",
        "An error occurred loading your data. Please try again later and excuse the inconvenience."
    );
    header("LOCATION: " . ROOT_DIR);    // redirect to home page if user not found.
    die();
}
?>

<!-- form processing script -->
<?php
if (!empty($_POST["type"])) {   // data set?
    if (isset($_POST["first_name"]) && isset($_POST["last_name"]) && isset($_POST["email"]) && isset($_POST["password"]) && isset($_POST["address"])) {

        //Is the email available?
        if (UserController::emailAvailable($_POST["email"]) || $user->getEmail() == $_POST["email"]) {

            $address = AddressController::getById($_POST["address"]);

            //Address found?
            if (isset($address)) {

                //Does the address belong to the user?
                if ($address->getUserId() == $user->getId()) {

                    // update user
                    $user = UserController::update(
                        $user,
                        $_POST["first_name"],
                        $_POST["last_name"],
                        $_POST["email"],
                        $_POST["password"],
                        null,
                        $_POST["address"]
                    );

                    //User could be updated.
                    if (isset($user)) {
                        UserController::login($user, $_POST["password"]);   // login user (update session infos)

                        logData("Profile", "User with id: " . $user->getId() . "got updated.", DEBUG_LOG);

                        //Reset latest messages
                        $_GET["message"] = "";

                        $updatedUser = 1;
                    } else {
                        //Update failed.
                        logData("Profile", "User could not be updated! (update user)", CRITICAL_LOG);
                        $updateError = 1;
                    }
                } else {
                    //Address does not belong to the user.
                    logData("Profile", "Address with id " . $address->getId() . " does not belong to user with id: " . $user->getId(), CRITICAL_LOG);
                    $addressNotUser = 1;
                }

            } else {
                //Address not found.
                logData("Profile", "Address with id " . $_POST["address"] . " not found.", CRITICAL_LOG);
                $addressError = 1;
            }

        } else {
            //E-Mail already used.
            logData("Profile", "E-Mail-Address: " . $_POST["email"] . " already used.");
            $emailError = 1;
        }
    } else {
        //Values are missing
        logData("Profile", "Value is missing or does not have the correct datatype! (update user)", CRITICAL_LOG);
        $valueError = 1;
    }
}
?>

<!--Load form data-->
<?php

//get all addresses
$addresses = AddressController::getAllByUser($user->getId());

$primaryAddress = AddressController::getById($user->getDefaultAddressId());

if (!$primaryAddress) { // user default address could not be found?
    $addressInfo = 1;
}

?>

<!DOCTYPE html>
<html class="h-100" lang="en">
<head>
    <?php require_once INCLUDE_DIR . "site_html_head.inc.php"; ?>
    <title><?= PAGE_NAME ?> - Profile</title>
</head>

<body class="d-flex flex-column h-100">
<!-- header -->
<?php require_once INCLUDE_DIR . "site_header.inc.php"; ?>

<!-- main body -->
<main class="m-auto w-100 px-3" style="max-width: 800px">
    <!-- title -->
    <h2 class="mb-2 mt-4">Update your Information</h2>
    <p class="text-muted mb-4">You want to change or update some of your information? Let us know.</p>

    <hr>

    <form action="" method="post" class="needs-validation text-start" novalidate>
        <h4 class="mb-2">Personal Information</h4>
        <input type="hidden" id="type" name="type" value="personal_info">

        <!-- region name row -->
        <div class="form-row row">
            <div class="col-md-6 mb-3 px-2 position-relative">
                <label for="first_name">First Name</label>
                <input type="text" class="form-control" id="first_name" name="first_name" placeholder="First Name"
                       required pattern="[a-zäöüA-ZÄÖÜ ,.'-]+" value="<?= $user->getFirstName() ?>">
                <div class="invalid-tooltip opacity-75">Please enter a valid Name!</div>
            </div>
            <div class="col-md-6 mb-3 px-2 position-relative">
                <label for="last_name">Last Name</label>
                <input type="text" class="form-control" id="last_name" name="last_name" placeholder="Last Name"
                       required pattern="[a-zäöüA-ZÄÖÜ ,.'-]+" value="<?= $user->getLastName() ?>">
                <div class="invalid-tooltip opacity-75">Please enter a valid Name!</div>
            </div>
        </div>
        <!-- endregion -->

        <!-- region email row -->
        <div class="form-row row">
            <div class="col-md mb-3 px-2 position-relative">
                <label for="email">Email Address</label>
                <input type="email" class="form-control" id="email" name="email" placeholder="Email" required
                       value="<?= $user->getEmail() ?>">
                <div class="invalid-tooltip opacity-75">Please enter a valid Email Address!</div>
            </div>
        </div>
        <!-- endregion -->

        <!-- region password row -->
        <div class="form-row row">
            <div class="col-md mb-3 px-2 position-relative" data-toggle="tooltip" data-placement="top"
                 title="At least one digit, lowercase-, uppercase-, special-char. At least 8, but no more than 32 char.">
                <label for="password">Password</label>
                <input type="password" class="form-control" id="password" name="password" placeholder="Password"
                       required
                       pattern="(?=.*[0-9])(?=.*[a-zäöü])(?=.*[A-ZÄÖÜ])(?=.*[*.!@$%^&(){}[\]:;<>,.?\/~_+\-=|]).{8,32}">
                <div class="invalid-tooltip opacity-75">Please enter a valid Password!</div>
            </div>
        </div>
        <!-- endregion -->

        <h4 class="mb-2 mt-3">Address Information</h4>
        <?php if (isset($addresses)) { // Are addresses available ?>
            <!-- region address 1 row -->
            <div class="form-group position-relative">

                <?php if (count($addresses) > 0): ?>
                    <?php foreach ($addresses as $address): ?>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="address"
                                   data-user="<?= UserController::getFormattedName($user) ?>"
                                   data-street="<?= $address->getStreet() . " " . $address->getNumber() ?>"
                                   data-city="<?= $address->getCity() . ", " . $address->getZip() ?>"
                                   value="<?= $address->getId(); ?>"
                                   required
                                <?php
                                //Select the default address
                                if (isset($primaryAddress) && $address->getId() === $primaryAddress->getId())
                                    echo "checked";
                                ?>
                            >
                            <label class="form-check-label">
                                <?= "<b>" . UserController::getFormattedName($user) . "</b> " . $address->getStreet() . " " . $address->getNumber() .
                                ", " . $address->getCity() . ", " . $address->getZip() ?>
                            </label>

                            <a class="btn btn-primary btn-sm" data-toggle="tooltip" data-placement="left"
                               title="Edit address" style="padding-inline: 10px"
                               onclick="openAddressModal(
                                       '<?= $address->getId() ?>',
                                       '<?= $address->getZip() ?>',
                                       '<?= $address->getCity() ?>',
                                       '<?= $address->getStreet() ?>',
                                       '<?= $address->getNumber() ?>'
                                       );"
                            >
                                <em class="fa fa-pencil"></em>
                            </a>

                            <a class="btn btn-warning btn-sm" data-toggle="tooltip" data-placement="left"
                               title="Delete order"
                               onclick="openConfirmModal(<?= "'Do you really want to delete this Address?'" ?>,
                                       'Delete Address?',
                                       '<?= str_replace(DS, "/", INCLUDE_HELPER_DIR . "helper_delete_address.inc.php?addressId=" . $address->getId() . "&userId=" . $user->getId()); ?>')">
                                <em class="fa fa-trash "></em> <!--TODO-->
                            </a>

                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <h5 class='text-muted mb-5'><em>There are no addresses in your profile.</em></h5>
                    <input type="hidden" name="delivery" value="" required>
                <?php endif; ?>
                <div class="invalid-tooltip opacity-75">Please choose a delivery address.</div>
            </div>
            <!-- endregion -->
        <?php } else { ?>
            <p><em class="mb-3">No Information about your addresses are available.</em></p>
        <?php } ?>

        <br>

        <div class="btn btn-sm btn-primary w-100" onclick="openAddressModal();"
             style="cursor: pointer;">
            Add a new address
        </div>

        <hr>

        <button class="w-100 btn btn-lg btn-success mb-5" type="submit">Save</button>
    </form>
</main>

<!-- footer -->
<?php require_once INCLUDE_DIR . "site_footer.inc.php" ?>

<!-- load custom form validation script -->
<script src="<?= SCRIPT_DIR . "form_validation.js" ?>"></script>
<!-- enable tooltips on this page (by default disabled for performance)-->
<script src="<?= SCRIPT_DIR . "tooltip_enable.js" ?>"></script>

<!-- confirm modal -->
<?php require_once INCLUDE_MODAL_DIR . "modal_confirm.inc.php"; ?>

<!-- Add popup modal -->
<?php require_once INCLUDE_MODAL_DIR . "modal_popup.inc.php"; ?>

<!-- Add modal to create a new address -->
<?php require_once INCLUDE_MODAL_DIR . "modal_add_edit_address.inc.php"; ?>

<!-- show error popup -->
<?php

if (isset($addressInfo)) {
    show_popup(
        "Information",
        "We could not find any information about your default address. Go to the corresponding settings to set your default address."
    );
}

if (isset($updateError)) {
    show_popup(
        "Error while Update",
        "An error occurred during the update. Please make sure you filled out the form correctly. Otherwise, please try again later and excuse the inconvenience."
    );
}

if (isset($emailError)) {
    show_popup(
        "Email unavailable",
        "The given email address is already connected with an account. Please use a different email or login with the existing account."
    );
}

if (isset($valueError)) {
    show_popup(
        "Values missing",
        "Not all required values are transmitted. Please try again and excuse the inconvenience."
    );
}

if (isset($updatedUser)) {
    show_popup(
        "Information",
        "Your personal information got updated."
    );
}

if (!empty($_GET["message"])) {
    show_popup(
        "Information",
        $_GET["message"]
    );
}

?>
</body>
</html>
