<!-- TODO Comment -->

<?php
require_once "../../include/site_php_head.inc.php";

UserController::redirectIfNotAdmin();   // User is not allowed to be here.

$isPost = strtolower($_SERVER["REQUEST_METHOD"]) === "post";

if (isset($_POST["title"]) && isset($_POST["cat"]) && isset($_POST["description"]) && isset($_POST["price"])
    && isset($_POST["shipping"]) && isset($_POST["stock"]) && $isPost) {

    $product = ProductController::insert(
        $_POST["title"],
        $_POST["cat"],
        $_POST["description"],
        $_POST["price"],
        $_POST["shipping"],
        $_POST["stock"],
        isset($_POST["active"])
    );

    if (isset($product)) {
        $errors = ProductController::uploadImages($product->getId(), $_FILES["files"], $_POST["mainImgID"]);
        if (!$errors) {
            logData("Add Product", "Product with id " . $product->getId() . " got created!");
            header("LOCATION: " . ADMIN_PAGES_DIR . 'page_products.php?message=Product%20created');  // go to admin products page
            die();
        }
    }

    $processingError = true;
} else if ($isPost) {
    logData("Add Product", "Missing values!", WARNING_LOG);
    $processingError = true;
}
?>

<!DOCTYPE html>
<html class="h-100" lang="en">
<head>
    <?php
    require_once INCLUDE_DIR . "site_html_head.inc.php";
    require_once INCLUDE_MODAL_DIR . "modal_popup.inc.php";
    ?>
    <title><?= PAGE_NAME ?> - Admin - Product - Add</title>

    <!-- file specific includes-->
    <link rel="stylesheet" href="<?= STYLE_DIR . "style_admin_pages.css"; ?>">
</head>

<body class="d-flex flex-column h-100">
<!-- header -->
<?php require_once INCLUDE_DIR . "site_header.inc.php"; ?>

<!-- main body -->
<main class="m-auto w-100 px-3" style="max-width: 800px">
    <?php require_once INCLUDE_ADMIN_DIR . "admin_product_add_edit.inc.php"; ?>
</main>

<!-- footer -->
<?php require_once INCLUDE_DIR . "site_footer.inc.php" ?>

<?php
if (isset($processingError) && $processingError) {
    show_popup();
}
?>

</body>
</html>