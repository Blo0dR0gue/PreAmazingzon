<!-- TODO COMMENT -->
<?php require_once "../include/site_php_head.inc.php" ?>

<?php
if(isset($_SESSION["login"]))   // if already logged in redirect to home
{
    header("LOCATION: " . ROOT_DIR);
    die();
}
?>

<!DOCTYPE html>
<html class="h-100" lang="en">
<head>
    <?php require_once INCLUDE_DIR . DIRECTORY_SEPARATOR . "site_html_head.inc.php"; ?>
    <title><?= PAGE_NAME ?> - Login</title>

    <!-- form processing script -->
    <?php
    require INCLUDE_DIR . DIRECTORY_SEPARATOR . "modal_popup.inc.php";
    require CONTROLLER_DIR . DIRECTORY_SEPARATOR . "controller_user.php";
    //TODO Remember me cookies?
    if (!empty($_POST["email"]) and !empty($_POST["password"]))   // data set?
    {
        if ($user = UserController::getByEmail($_POST["email"]))     // get user
        {
            if (UserController::login($user, $_POST["password"]))    // login user
            {
                header("LOCATION: " . ROOT_DIR);  // go back to home site
                die();
            }
        }
        $loginError = 1;    // show error msg later, to prevent errors displaying page
    }
    ?>
</head>

<body class="text-center bg-light align-items-center h-100 d-flex">

<!-- main body -->
<main class="m-auto w-100 px-3" style="max-width: 370px;">
    <form action="" method="post" class="needs-validation" novalidate>
        <a href="<?= ROOT_DIR ?>" class="mb-0">
            <img class="mb-4" src="<?= IMAGE_LOGO_DIR . DIRECTORY_SEPARATOR . "logo_long.svg" ?>" alt="Company Logo"
                 width="" height="64">
        </a>
        <h3 class="mb-3 fw-normal">Please login</h3>

        <div class="form-floating">
            <input type="email" name="email" class="form-control" id="emailInput" placeholder="name@example.com"
                   style="border-bottom-left-radius: 0; border-bottom-right-radius: 0; margin-bottom: -1px" required>
            <label for="emailInput">Email address</label>
            <div class="invalid-tooltip opacity-75">Please enter a valid Email!</div>
        </div>
        <div class="form-floating">
            <input type="password" name="password" class="form-control" id="passwordInput" placeholder="Password"
                   style="border-top-left-radius: 0; border-top-right-radius: 0" required>
            <label for="passwordInput">Password</label>
            <div class="invalid-tooltip opacity-75">Please enter a password!</div>
        </div>
        <p class="text-muted"><small>By logging in, you accept the terms of use.</small></p>

        <!-- TODO do we need remember me?-->
        <div class="checkbox mb-3">
            <label>
                <input type="checkbox" value="remember-me" name="remember"> Remember me
            </label>
        </div>

        <!-- buttons -->
        <button class="w-100 btn btn-lg btn-primary" type="submit">Login</button>
        <p class="my-1 text-muted">or</p>
        <a href="<?= PAGES_DIR . DIRECTORY_SEPARATOR . "page_register.php" ?>" class="w-100 btn btn-lg btn-secondary">
            Create an account</a>

        <!-- custom footer -->
        <p class="mt-4 mb-3 text-muted">© <?= PAGE_COPYRIGHT . " " . PAGE_NAME ?> Inc.</p>
    </form>
</main>

<!-- load custom form validation script -->
<script src="<?= SCRIPT_DIR . DIRECTORY_SEPARATOR . "form_validation.js" ?>"></script>

<!-- show error popup -->
<?php
if (isset($loginError)) // login error
{
    show_popup(
        "Login Error",
        "Your Email or Password is wrong, please retry with the correct credentials!"
    );
}
?>
</body>
</html>

