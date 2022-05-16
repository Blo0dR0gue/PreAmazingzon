<?php
require_once "../../include/site_php_head.inc.php";

if (!isset($_SESSION["login"]) || !isset($_SESSION["isAdmin"]) || !$_SESSION["isAdmin"]) {
    header("LOCATION: " . ROOT_DIR);    //User is not allowed to be here.
    die();
}

//Load required Controllers
require_once CONTROLLER_DIR . DS . 'controller_product.php';
require_once CONTROLLER_DIR . DS . 'controller_category.php';

$isPost = strtolower($_SERVER["REQUEST_METHOD"]) === "post";

//TODO form val
?>

<!DOCTYPE html>
<html class="h-100" lang="en">
<head>
    <?php
    require_once INCLUDE_DIR . DS . "site_html_head.inc.php";
    require INCLUDE_DIR . DS . "modal_popup.inc.php";
    ?>
    <title><?= PAGE_NAME ?> - Admin - Product - Edit</title>

    <!-- file specific includes-->
    <link rel="stylesheet" href="<?= STYLE_DIR . DS . "style_admin_pages.css"; ?>">

</head>

<body class="d-flex flex-column h-100">
<!-- header -->
<?php require INCLUDE_DIR . DS . "site_header.inc.php"; ?>

<!-- main body -->
<main class="m-auto w-100 px-3" style="max-width: 800px">

    <?php require_once INCLUDE_DIR . DS . 'admin' . DS . "admin_product_add_edit.inc.php";?>

</main>

<!-- footer -->
<?php require INCLUDE_DIR . DS . "site_footer.inc.php" ?>

<!-- show error popup -->
<?php
if (isset($processingError)) {   // processing error
    show_popup(
        "Add Product Error",
        "ALARM" //TODO
    );
}
?>

</body>
</html>

