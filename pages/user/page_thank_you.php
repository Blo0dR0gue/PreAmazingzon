<!-- TODO COMMENT -->
<?php require_once "../../include/site_php_head.inc.php" ?>

<!DOCTYPE html>
<html class="h-100" lang="en">
<head>
    <?php require_once INCLUDE_DIR . "site_html_head.inc.php"; ?>
    <title><?= PAGE_NAME ?> - Thank You</title>
</head>

<body class="d-flex flex-column h-100">
<!-- header -->
<?php require_once INCLUDE_DIR . "site_header.inc.php"; ?>

<!-- main body -->
<main class="m-auto w-100 px-3" style="max-width: 900px">
    <div>
        <div class="jumbotron text-center">
            <h1 class="display-2">Thank you for your order!</h1>
            <p class="lead">Please check your orders inside your profile to see the invoice.</p>
            <hr>
            <p class="text-muted">Having trouble? <a <?= PAGES_DIR . "page_about.php" ?>>Contact us</a></p>
            <p class="lead">
                <a class="btn btn-primary" href="<?= ROOT_DIR ?>" role="button">Continue shopping</a>
            </p>
        </div>
    </div>
</main>

<!-- footer -->
<?php require_once INCLUDE_DIR . "site_footer.inc.php"; ?>

</body>
</html>
