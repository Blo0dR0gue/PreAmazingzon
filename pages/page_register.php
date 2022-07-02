<?php require_once "../include/site_php_head.inc.php" ?>

<?php
//if already logged in redirect to home
if (UserController::isCurrentSessionLoggedIn()) {
    header("LOCATION: " . ROOT_DIR);
    die();
}
?>

<!DOCTYPE html>
<html class="h-100" lang="en">
<head>
    <?php require_once INCLUDE_DIR . "site_html_head.inc.php"; ?>
    <title><?= PAGE_NAME ?> - Register</title>

    <!-- form processing script -->
    <?php
    require_once INCLUDE_MODAL_DIR . "modal_popup.inc.php";

    if (!empty($_POST["email"]) && !empty($_POST["password"])) {    // data set (e.g. tested with email and password)?
        if (UserController::emailAvailable($_POST["email"])) {      // email available?
            $user = UserController::register(
                $_POST["first_name"],
                $_POST["last_name"],
                $_POST["email"],
                $_POST["password"],
                $_POST["zip"],
                $_POST["city"],
                $_POST["street"],
                $_POST["number"],
                UserRoleController::getDefaultUserRole()->getId()
            );

            if ($user) {    // user could be inserted?
                UserController::login($user, $_POST["password"]);   // login user
                header("LOCATION: " . ROOT_DIR);  // go back to home site
                die();
            } else { $registerError = 1; }
        } else { $emailError = 1; }
    }
    ?>
</head>

<body class="text-center bg-light align-items-center h-100 d-flex">

<!-- main body -->
<main class="m-auto w-100 px-3" style="max-width: 600px">
    <!-- title -->
    <a href="<?= ROOT_DIR ?>" class="mb-0">
        <img class="mb-4" src="<?= IMAGE_LOGO_DIR . "logo_long.svg" ?>" alt="Company Logo" width="" height="64">
    </a>
    <h3 class="mb-2">Create an Account</h3>
    <p class="text-muted mb-4">Give us some more information about you, so we can get to know you.</p>

    <form action="" method="post" class="needs-validation text-start" novalidate>
        <!-- region name row -->
        <div class="form-row row">
            <div class="col-md-6 mb-3 px-2 position-relative">
                <label for="first_name">First Name</label>
                <input type="text" class="form-control" id="first_name" name="first_name" placeholder="First Name"
                       required pattern="[a-zäöüA-ZÄÖÜ ,.'-]+">
                <div class="invalid-tooltip opacity-75">Please enter a valid Name!</div>
            </div>
            <div class="col-md-6 mb-3 px-2 position-relative">
                <label for="last_name">Last Name</label>
                <input type="text" class="form-control" id="last_name" name="last_name" placeholder="Last Name"
                       required pattern="[a-zäöüA-ZÄÖÜ ,.'-]+">
                <div class="invalid-tooltip opacity-75">Please enter a valid Name!</div>
            </div>
        </div>
        <!-- endregion -->

        <!-- region email row -->
        <div class="form-row row">
            <div class="col-md mb-3 px-2 position-relative">
                <label for="email">Email Address</label>
                <input type="email" class="form-control" id="email" name="email" placeholder="Email" required>
                <div class="invalid-tooltip opacity-75">Please enter a valid Email Address!</div>
            </div>
        </div>
        <!-- endregion -->

        <!-- region password row -->
        <div class="form-row row">
            <div class="col-md mb-3 px-2 position-relative" data-toggle="tooltip" data-placement="top"
                 title="At least one digit, lowercase-, uppercase-, special-char. At least 8, but no more than 32 char.">
                <label for="password">Password</label>
                <input type="password" class="form-control" id="password" name="password" placeholder="Password" required
                       pattern="(?=.*[0-9])(?=.*[a-zäöü])(?=.*[A-ZÄÖÜ])(?=.*[*.!@$%^&(){}[\]:;<>,.?\/~_+\-=|]).{8,32}">
                <div class="invalid-tooltip opacity-75">Please enter a valid Password!</div>
            </div>
        </div>
        <!-- endregion -->

        <hr class="my-1 mb-3"/>

        <!-- region address 1 row -->
        <div class="form-row row">
            <div class="col-md-4 mb-3 px-2 position-relative">
                <label for="zip">Zip</label>
                <input type="text" class="form-control" id="zip" name="zip" placeholder="Zip" required
                       pattern="\d{5}">
                <div class="invalid-tooltip opacity-75">Please enter a valid ZIP!</div>
            </div>
            <div class="col-md-8 mb-3 px-2 position-relative">
                <label for="city">City</label>
                <input type="text" class="form-control" id="city" name="city" placeholder="City" required
                       pattern="[a-zöäüA-ZÄÖÜ]+(?:[\s-][a-zöäüA-ZÖÄÜ]+)*">
                <div class="invalid-tooltip opacity-75">Please enter a valid City!</div>
            </div>
        </div>
        <!-- endregion -->

        <!-- region address 2 row -->
        <div class="form-row row">
            <div class="col-md-8 mb-3 px-2 position-relative">
                <label for="street">Street</label>
                <input type="text" class="form-control" id="street" name="street" placeholder="Street" required
                       pattern="[a-zöäüA-ZÄÖÜ]+(?:[\s-][a-zöäüA-ZÖÄÜ]+)*">
                <div class="invalid-tooltip opacity-75">Please enter a valid Street!</div>
            </div>
            <div class="col-md-4 mb-4 px-2 position-relative">
                <label for="number">No.</label>
                <input type="text" class="form-control" id="number" name="number" placeholder="Number" required
                       pattern="[1-9]\d*(?:[ -]?(?:[a-zA-Z]+|[1-9]\d*))?">
                <div class="invalid-tooltip opacity-75">Please enter a Number!</div>
            </div>
        </div>
        <!-- endregion -->

        <!-- region legal stuff -->
        <div class="form-group">
            <div class="form-check text-muted">
                <input class="form-check-input" type="checkbox" name="checkbox" id="conditions" required>
                <label class="form-check-label" for="conditions">Agree to terms and conditions</label>
            </div>
        </div>
        <!-- endregion -->

        <button class="w-100 btn btn-lg btn-primary mt-3" type="submit">Register</button>
    </form>

    <p class="mt-4 mb-1 text-muted">Already registered?
        <a class="text-muted" href="<?= PAGES_DIR . "page_login.php" ?>">Login</a>
    </p>
    <p class="mb-3 text-muted">© <?= PAGE_COPYRIGHT . " " . PAGE_NAME ?> Inc.</p>
</main>

<!-- load custom form validation script -->
<script src="<?= SCRIPT_DIR . "form_validation.js" ?>"></script>
<!-- enable tooltips on this page (by default disabled for performance)-->
<script src="<?= SCRIPT_DIR . "tooltip_enable.js" ?>"></script>

<!-- show message popups -->
<?php
if (isset($registerError)) {
    show_popup(
        "Error while Registration",
        "An error occurred during the registration. Please make sure you filled out the form correctly. Otherwise, please try again later and excuse the inconvenience."
    );
}

if (isset($emailError)) {
    show_popup(
        "Email unavailable",
        "The given email address is already connected with an account. Please use a different email for creating a new account. Or login with the existing account."
    );
}
?>
</body>
</html>
