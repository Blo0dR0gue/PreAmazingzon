<!--Add a product page-->

<?php
require_once "../../include/site_php_head.inc.php";

//Is the user allowed to be here?
UserController::redirectIfNotAdmin();

//Is the request a post
$isPost = strtolower($_SERVER["REQUEST_METHOD"]) === "post";

//Handle form inputs
if (isset($_POST["title"]) && isset($_POST["cat"]) && isset($_POST["description"]) && isset($_POST["price"])
    && isset($_POST["shipping"]) && isset($_POST["stock"]) && $isPost) {

    //Create the product
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
        //The product got created
        logData("Add Product", "Product with id " . $product->getId() . " got created!");

        $errors = ProductController::uploadImages($product->getId(), $_FILES["files"], $_POST["mainImgID"]);

        if (!$errors) {
            //Images got uploaded
            logData("Add Product", "Images got uploaded for product with id: " . $product->getId());
            header("LOCATION: " . ADMIN_PAGES_DIR . 'page_products.php?message=Product%20created');  // go to admin products page
            die();
        }
    }

    $processingError = true;
} else if ($isPost) {
    //it was a post request but values are missing
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