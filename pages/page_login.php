<!-- TODO COMMENT -->
<?php require_once "../include/site_php_head.inc.php" ?>

<!DOCTYPE html>
<html class="h-100" lang="de">
<head>
    <?php require_once INCLUDE_DIR."/site_html_head.inc.php"; ?>
    <title>Amazingzon - Login</title>
</head>

<body class="text-center bg-light align-items-center h-100 d-flex">

<main class="m-auto w-100 px-3" style="max-width: 370px;">
<!-- TODO make the login work-->
    <form>
        <img class="mb-4" src="<?=IMAGE_DIR."/logo/logo_long.svg"?>" alt="Company Logo" width="" height="64">
        <h1 class="h3 mb-3 fw-normal">Please login</h1>

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
        <p class="text-muted">By logging in, you accept the terms of use.</p>

        <!-- TODO do we need remember me?-->
        <div class="checkbox mb-3">
            <label>
                <input type="checkbox" value="remember-me"> Remember me
            </label>
        </div>

        <!--  TODO link register page-->
        <a href="#" class="mb-3 link-primary">Create an account</a>
        <button class="w-100 btn btn-lg btn-primary" type="submit">Login</button>

        <p class="mt-5 mb-3 text-muted">© 2022 Amazingzon Inc.</p>
    </form>
</main>

</body>
</html>

