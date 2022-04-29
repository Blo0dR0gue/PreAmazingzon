<?php require_once "include/site_php_head.inc.php" ?>

<!DOCTYPE html>
<html class="h-100" lang="de">
<head>
    <?php require_once "include/site_html_head.inc.php"; ?>
    <title>Amazingzon</title>
</head>

<body class="d-flex flex-column h-100">
<header class="p-3 bg-dark text-white">
    <div class="container">
        <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start">
            <a href="/" class="d-flex align-items-center mb-2 mb-lg-0 text-white text-decoration-none">
                <svg class="bi me-2" width="40" height="32" role="img" aria-label="Bootstrap">
                    <use xlink:href="#bootstrap"/>
                </svg>
            </a>

            <ul class="nav col-12 col-lg-auto me-lg-auto mb-2 justify-content-center mb-md-0">
                <li><a href="#" class="nav-link px-2 text-secondary">Home</a></li>
                <li><a href="#" class="nav-link px-2 text-white">Features</a></li>
                <li><a href="#" class="nav-link px-2 text-white">Pricing</a></li>
                <li><a href="#" class="nav-link px-2 text-white">FAQs</a></li>
                <li><a href="#" class="nav-link px-2 text-white">About</a></li>
            </ul>

            <form class="col-12 col-lg-auto mb-3 mb-lg-0 me-lg-3">
                <input type="search" class="form-control form-control-dark" placeholder="Search..." aria-label="Search">
            </form>

            <div class="text-end">
                <button type="button" class="btn btn-outline-light me-2">Login</button>
                <button type="button" class="btn btn-warning">Sign-up</button>
            </div>
        </div>
    </div>
</header>

<main class="flex-shrink-0">
    <section class="container" id="products">
        <div class="row">
            <div class="col">
                <?php include "include/itemCard.inc.php"; ?>
            </div>
            <div class="col">
                <?php include "include/itemCard.inc.php"; ?>
            </div>
            <div class="col">
                <?php include "include/itemCard.inc.php"; ?>
            </div>
            <div class="col">
                <?php include "include/itemCard.inc.php"; ?>
            </div>
        </div>
    </section>
</main>

<!-- footer -->
<?php require "include/site_footer.inc.php" ?>
<!--TODO change to path references to organisation ?-->

</body>
</html>
