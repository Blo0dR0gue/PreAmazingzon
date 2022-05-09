<!-- TODO COMMENT -->
<?php require_once "../include/site_php_head.inc.php" ?>

<?php
if(!isset($_SESSION["login"]))   // if not logged in redirect to home
{
    header("LOCATION: " . ROOT_DIR);
    die();
}
?>

<!DOCTYPE html>
<html class="h-100" lang="en">
<head>
    <?php require_once INCLUDE_DIR . DIRECTORY_SEPARATOR . "site_html_head.inc.php"; ?>
    <title><?= PAGE_NAME ?> - Profile</title>

    <!-- form preload script -->
    <?php
    require_once INCLUDE_DIR . DIRECTORY_SEPARATOR . "modal_popup.inc.php";
    require_once CONTROLLER_DIR . DIRECTORY_SEPARATOR . "controller_user.php";
    require_once CONTROLLER_DIR . DIRECTORY_SEPARATOR . "controller_address.php";

    // get user
    $user = UserController::getById($_SESSION["uid"]);
    if (!$user)  // user could be found?
    {
        show_popup(
            "Error",
            "An error occurred loading your data. Please try again later and excuse the inconvenience."
        );
    }

    //TODO redirect, if user not found?

    // get address
    $address = AddressController::getById($user->getDefaultAddressId());
    if (!$address)  // user could be found?
    {
        show_popup(
            "Error",
            "An error occurred loading your default address. Please try again later and excuse the inconvenience."
        );
    }

    //TODO add/edit multiple addresses


    ?>

    <!-- form processing script -->
    <?php
    require_once INCLUDE_DIR . DIRECTORY_SEPARATOR . "modal_popup.inc.php";
    require_once CONTROLLER_DIR . DIRECTORY_SEPARATOR . "controller_user.php";
    require_once CONTROLLER_DIR . DIRECTORY_SEPARATOR . "controller_address.php";

    if (!empty($_POST["type"]))   // data set?
    {
        if ($_POST["email"] === $user->getEmail() or UserController::emailAvailable($_POST["email"]))     // email available?
        {
            // update user
            $user = UserController::update(
                $user,
                $_POST["first_name"],
                $_POST["last_name"],
                $_POST["email"],
                $_POST["password"],
            );

            // update default address
            $address = AddressController::getById($user->getDefaultAddressId());
            $address = AddressController::update(
                $address,
                $_POST["street"],
                $_POST["zip"],
                $_POST["number"],
                $_POST["city"]
            );

            if ($user and $address)  // user could be inserted?
            {
                UserController::login($user, $_POST["password"]);   // login user
                header("LOCATION: " . ROOT_DIR);  // go back to home site
                die();
            } else
            {
                show_popup(
                    "Error while Update",
                    "An error occurred during the update. Please make sure you filled out the form correctly. Otherwise, please try again later and excuse the inconvenience."
                );
            }
        } else
        {
            show_popup(
                "Email unavailable",
                "The given email address is already connected with an account. Please use a different email or login with the existing account."
            );
        }
    }
    ?>
</head>

<body class="d-flex flex-column h-100">
<!--header -->
<?php require INCLUDE_DIR . DIRECTORY_SEPARATOR . "site_header.inc.php"; ?>

<!-- main body -->
<main class="m-auto w-100 px-3" style="max-width: 800px">
    <!-- title -->
    <h2 class="mb-2 mt-4">Update your Information</h2>
    <p class="text-muted mb-4">You want to change or update some of your information? Let us know.</p>

    <form action="" method="post" class="needs-validation text-start" novalidate>
        <h4 class="mb-2">Personal Information</h4>
        <input type="hidden" id="type" name="type" value="personal_info">

        <!-- region name row -->
        <div class="form-row row">
            <div class="col-md-6 mb-3 px-2" style="position: relative">
                <label for="first_name">First Name</label>
                <input type="text" class="form-control" id="first_name" name="first_name" placeholder="First Name"
                       required pattern="[a-zäöüA-ZÄÖÜ ,.'-]+" value="<?= $user->getFirstName() ?>">
                <div class="invalid-tooltip opacity-75">Please enter a valid Name!</div>
            </div>
            <div class="col-md-6 mb-3 px-2" style="position: relative">
                <label for="last_name">Last Name</label>
                <input type="text" class="form-control" id="last_name" name="last_name" placeholder="Last Name"
                       required pattern="[a-zäöüA-ZÄÖÜ ,.'-]+" value="<?= $user->getLastName() ?>">
                <div class="invalid-tooltip opacity-75">Please enter a valid Name!</div>
            </div>
        </div>
        <!-- endregion -->

        <!-- region email row -->
        <div class="form-row row">
            <div class="col-md mb-3 px-2" style="position: relative">
                <label for="email">Email Address</label>
                <input type="email" class="form-control" id="email" name="email" placeholder="Email" required
                       value="<?= $user->getEmail() ?>">
                <div class="invalid-tooltip opacity-75">Please enter a valid Email Address!</div>
            </div>
        </div>
        <!-- endregion -->

        <!-- region password row -->
        <div class="form-row row">
            <div class="col-md mb-3 px-2" style="position: relative" data-toggle="tooltip" data-placement="top"
                 title="At least one digit, lowercase-, uppercase-, special-char. At least 8, but no more than 32 char.">
                <label for="password">Password</label>
                <input type="password" class="form-control" id="password" name="password" placeholder="Password" required
                       pattern="(?=.*[0-9])(?=.*[a-zäöü])(?=.*[A-ZÄÖÜ])(?=.*[*.!@$%^&(){}[\]:;<>,.?\/~_+\-=|]).{8,32}">
                <div class="invalid-tooltip opacity-75">Please enter a valid Password!</div>
            </div>
        </div>
        <!-- endregion -->

        <?php if(isset($address)): ?>   <!--Is the default address available?-->
        <h4 class="mb-2 mt-3">Default Address Information</h4>
        <!-- region address 1 row -->
        <div class="form-row row">
            <div class="col-md-4 mb-3 px-2" style="position: relative">
                <label for="zip">Zip</label>
                <input type="text" class="form-control" id="zip" name="zip" placeholder="Zip" required
                       pattern="\d{5}" value="<?= $address->getZip() ?>">
                <div class="invalid-tooltip opacity-75">Please enter a valid ZIP!</div>
            </div>
            <div class="col-md-8 mb-3 px-2" style="position: relative">
                <label for="city">City</label>
                <input type="text" class="form-control" id="city" name="city" placeholder="City" required
                       pattern="[a-zöäüA-ZÄÖÜ]+(?:[\s-][a-zöäüA-ZÖÄÜ]+)*" value="<?= $address->getCity() ?>">
                <div class="invalid-tooltip opacity-75">Please enter a valid City!</div>
            </div>
        </div>
        <!-- endregion -->

        <!-- region address 2 row -->
        <div class="form-row row">
            <div class="col-md-8 mb-3 px-2" style="position: relative">
                <label for="street">Street</label>
                <input type="text" class="form-control" id="street" name="street" placeholder="Street" required
                       pattern="[a-zöäüA-ZÄÖÜ]+(?:[\s-][a-zöäüA-ZÖÄÜ]+)*" value="<?= $address->getStreet() ?>">
                <div class="invalid-tooltip opacity-75">Please enter a valid Street!</div>
            </div>
            <div class="col-md-4 mb-4 px-2" style="position: relative">
                <label for="number">No.</label>
                <input type="text" class="form-control" id="number" name="number" placeholder="Number" required
                       pattern="[1-9]\d*(?:[ -]?(?:[a-zA-Z]+|[1-9]\d*))?" value="<?= $address->getNumber() ?>">
                <div class="invalid-tooltip opacity-75">Please enter a Number!</div>
            </div>
        </div>
        <?php endif?>
        <!-- endregion -->

        <button class="w-100 btn btn-lg btn-primary mb-5" type="submit">Save</button>
    </form>
</main>

<!-- footer -->
<?php require INCLUDE_DIR . DIRECTORY_SEPARATOR . "site_footer.inc.php" ?>

<!-- load custom form validation script -->
<script src="<?= SCRIPT_DIR . DIRECTORY_SEPARATOR . "form_validation.js" ?>"></script>
<!-- enable tooltips on this page (by default disabled for performance)-->
<script src="<?= SCRIPT_DIR . DIRECTORY_SEPARATOR . "tooltip_enable.js" ?>"></script>

</body>
</html>