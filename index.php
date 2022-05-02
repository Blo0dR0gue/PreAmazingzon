<!-- TODO COMMENT -->
<?php require_once "include/site_php_head.inc.php" ?>

<!DOCTYPE html>
<html class="h-100" lang="en">
<head>
    <?php require_once INCLUDE_DIR . "/site_html_head.inc.php"; ?>
    <title>Amazingzon</title>
</head>

<body class="d-flex flex-column h-100">
<!--header -->
<?php require INCLUDE_DIR . "/site_header.inc.php"; ?>

<!-- main body -->
<main class="flex-shrink-0">
    <section class="container" id="products">
        <div class="row">
            <?php
                require CONTROLLER_DIR . DIRECTORY_SEPARATOR . "controller_product.php";
                $controller = new ProductController();
                foreach($controller->getAllProducts() as $product):
            ?>
            <div class="col-3">
                <?php require INCLUDE_DIR . "/elem_item_card.inc.php"; ?>
            </div>
            <?php endforeach; ?>
        </div>
    </section>
</main>

<!-- footer -->
<?php require INCLUDE_DIR . "/site_footer.inc.php" ?>

<!--TODO change to path references to organisation ?-->

</body>
</html>
