<?php require_once "include/site_php_head.inc.php" ?>

<!DOCTYPE html>
<html class="h-100" lang="de">
<head>
    <?php require_once INCLUDE_DIR."/site_html_head.inc.php"; ?>
    <title>Amazingzon</title>
</head>

<body class="d-flex flex-column h-100">
<!--header -->
<?php require INCLUDE_DIR."/site_header.php"; ?>

<!-- main body -->
<main class="flex-shrink-0">
    <section class="container" id="products">
        <div class="row">
            <div class="col">
                <?php require INCLUDE_DIR."/elem_item_card.inc.php"; ?>
            </div>
            <div class="col">
                <?php require INCLUDE_DIR."/elem_item_card.inc.php"; ?>
            </div>
            <div class="col">
                <?php require INCLUDE_DIR."/elem_item_card.inc.php"; ?>
            </div>
            <div class="col">
                <?php require INCLUDE_DIR."/elem_item_card.inc.php"; ?>
            </div>
        </div>
    </section>
</main>

<!-- footer -->
<?php require INCLUDE_DIR."/site_footer.inc.php" ?>
<!--TODO change to path references to organisation ?-->

</body>
</html>
