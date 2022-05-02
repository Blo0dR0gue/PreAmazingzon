<!-- TODO COMMENT -->
<?php require_once "../include/site_php_head.inc.php" ?>

<!-- TODO if already logged in redirect to e.g. profile -->

<!DOCTYPE html>
<html class="h-100" lang="en">
<head>
    <?php require_once INCLUDE_DIR . "/site_html_head.inc.php"; ?>
    <title>Amazingzon - Register</title>
</head>

<body class="text-center bg-light align-items-center h-100 d-flex">

<main class="m-auto w-100 px-3" style="max-width: 600px">
    <!-- TODO make the register work-->

    <a href="/" class="mb-0">
        <img class="mb-4" src="<?= IMAGE_DIR . "/logo/logo_long.svg" ?>" alt="Company Logo" width="" height="64">
    </a>
    <h3 class="mb-2">Create an Account</h3>
    <p class="text-muted mb-4">Give us some more information about you, so we can get to know you.</p>

    <form class="needs-validation text-start" novalidate>
        <!-- region name row -->
        <div class="form-row row">
            <div class="col-md-6 mb-3 px-2">
                <label for="first_name">First Name</label>
                <input type="text" class="form-control" id="first_name" placeholder="First Name" required>
                <div class="valid-feedback">
                    Looks good!
                </div>
            </div>
            <div class="col-md-6 mb-3 px-2">
                <label for="last_name">Last Name</label>
                <input type="text" class="form-control" id="last_name" placeholder="Last Name" required>
                <div class="valid-feedback">
                    Looks good!
                </div>
            </div>
        </div>
        <!-- endregion -->

        <!-- region email row -->
        <div class="form-row row">
            <div class="col-md mb-3 px-2">
                <label for="email">Email Address</label>
                <input type="email" class="form-control" id="email" placeholder="Email" required>
                <div class="valid-feedback">
                    Looks good!
                </div>
            </div>
        </div>
        <!-- endregion -->

        <!-- region password row -->
        <div class="form-row row">
            <div class="col-md mb-3 px-2">
                <label for="password">Password</label>
                <input type="password" class="form-control" id="password" placeholder="Password" required>
                <div class="valid-feedback">
                    Looks good!
                </div>
            </div>
        </div>
        <!-- endregion -->

        <hr class="my-1 mb-3"/>

        <!-- region address 1 row -->
        <div class="form-row row">
            <div class="col-md-4 mb-3 px-2">
                <label for="zip">Zip</label>
                <input type="text" class="form-control" id="zip" placeholder="Zip" required>
                <div class="invalid-feedback">
                    Please provide a valid zip.
                </div>
            </div>
            <div class="col-md-8 mb-3 px-2">
                <label for="city">City</label>
                <input type="text" class="form-control" id="city" placeholder="City" required>
                <div class="invalid-feedback">
                    Please provide a valid city.
                </div>
            </div>
        </div>
        <!-- endregion -->

        <!-- region address 2 row -->
        <div class="form-row row">
            <div class="col-md-8 mb-3 px-2">
                <label for="street">Street</label>
                <input type="text" class="form-control" id="street" placeholder="Street" required>
                <div class="invalid-feedback">
                    Please provide a valid city.
                </div>
            </div>
            <div class="col-md-4 mb-4 px-2">
                <label for="number">No.</label>
                <input type="text" class="form-control" id="number" placeholder="Number" required>
                <div class="invalid-feedback">
                    Please provide a valid zip.
                </div>
            </div>
        </div>
        <!-- endregion -->

        <!-- region legal stuff-->
        <div class="form-group">
            <div class="form-check text-muted">
                <input class="form-check-input" type="checkbox" value="" id="conditions" required>
                <label class="form-check-label" for="conditions">Agree to terms and conditions</label>
                <div class="invalid-feedback">
                    You must agree before submitting.
                </div>
            </div>
        </div>
        <!-- endregion -->

        <button class="w-100 btn btn-lg btn-primary mt-3" type="submit">Register</button>
    </form>

    <p class="mt-4 mb-3 text-muted">© 2022 Amazingzon Inc.</p>
</main>

<!-- load custom form validation script -->
<script src="<?= SCRIPT_DIR . "/form_validation.js" ?>"></script>

</body>
</html>
