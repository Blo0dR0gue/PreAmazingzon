<!-- TODO COMMENT -->
<?php require_once "../include/site_php_head.inc.php" ?>

<!-- TODO if already logged in redirect to e.g. profile -->

<!DOCTYPE html>
<html class="h-100" lang="en">
<head>
    <?php require_once INCLUDE_DIR . DIRECTORY_SEPARATOR . "site_html_head.inc.php"; ?>
    <title>Amazingzon - Login</title>
</head>

<body class="text-center bg-light align-items-center h-100 d-flex">

<main class="m-auto w-100 px-3" style="max-width: 370px;">
    <!-- TODO make the login work-->
    <form novalidate>
        <a href="/" class="mb-0">
            <img class="mb-4" src="<?= IMAGE_DIR . DIRECTORY_SEPARATOR . "logo/logo_long.svg" ?>" alt="Company Logo" width="" height="64">
        </a>
        <h3 class="mb-3 fw-normal">Please login</h3>

        <div class="form-floating">
            <input type="email" class="form-control" id="floatingInput" placeholder="name@example.com"
                   style="border-bottom-left-radius: 0; border-bottom-right-radius: 0; margin-bottom: -1px">
            <label for="floatingInput">Email address</label>
        </div>
        <div class="form-floating">
            <input type="password" class="form-control" id="floatingPassword" placeholder="Password"
                   style="border-top-left-radius: 0; border-top-right-radius: 0">
            <label for="floatingPassword">Password</label>
        </div>
        <p class="text-muted"><small>By logging in, you accept the terms of use.</small></p>

        <!-- TODO do we need remember me?-->
        <div class="checkbox mb-3">
            <label>
                <input type="checkbox" value="remember-me"> Remember me
            </label>
        </div>

        <button class="w-100 btn btn-lg btn-primary" type="submit">Login</button>

        <p class="my-1 text-muted">or</p>

        <a href="/pages/page_register.php" class="w-100 btn btn-lg btn-secondary">Create an account</a>

        <p class="mt-4 mb-3 text-muted">© 2022 Amazingzon Inc.</p>
    </form>
</main>

</body>
</html>

