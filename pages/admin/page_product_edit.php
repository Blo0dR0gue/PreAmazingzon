<?php
require_once "../../include/site_php_head.inc.php";

UserController::redirectIfNotAdmin();   // User is not allowed to be here.

$productID = $_GET["id"];
if (isset($productID) && is_numeric($productID)) {
    $product = ProductController::getByID(intval($productID));

    if (!isset($product)) {
        logData("Edit Product", "Product with id " . $productID . "not found!", WARNING_LOG);
        header("LOCATION: " . ADMIN_PAGES_DIR . "page_products.php"); // Redirect, if no product is found.
        die();
    }

    // Variable, which is used by the radio buttons // TODO do we use that?
    $category = CategoryController::getById($product->getCategoryID());
} else {
    logData("Edit Product", "Missing value!", WARNING_LOG);
    header("LOCATION: " . ADMIN_PAGES_DIR . "page_products.php"); // Redirect, if no number is passed.
    die();
}

$isPost = strtolower($_SERVER["REQUEST_METHOD"]) === "post";

if (isset($_POST["title"]) && isset($_POST["cat"]) && isset($_POST["description"]) && isset($_POST["price"]) && is_numeric($_POST["price"])
    && isset($_POST["shipping"]) && is_numeric($_POST["shipping"]) && isset($_POST["stock"]) && is_numeric($_POST["stock"]) && $isPost) {

    $product = ProductController::update(
        $product,
        $_POST["title"],
        $_POST["cat"],
        $_POST["description"],
        $_POST["price"],
        $_POST["shipping"],
        $_POST["stock"],
        isset($_POST["active"])
    );

    if (isset($product)) {

        logData("Edit Product", "Product with id " . $product->getId() . " got updated.");
        $error = ProductController::deleteSelectedImages($product->getId(), $_POST["deletedImgIDs"]);

        if (!$error) {
            logData("Edit Product", "Selected images got deleted.");
            $error = ProductController::updateMainImg($product->getId(), $_POST["mainImgID"]);

            if (!$error) {

                logData("Edit Product", "Main image got updated.");
                $error = ProductController::uploadImages($product->getId(), $_FILES["files"] ?? null, $_POST["mainImgID"]);

                if (!$error) {
                    logData("Edit Product", "Images got uploaded.");
                    logData("Edit Product", "Update product with id: " . $product->getId() . " done.");
                    header("LOCATION: " . ADMIN_PAGES_DIR . 'page_products.php');  // go to admin products page
                    // TODO success msg?
                    die();
                }
            }
        }
    }
    $processingError = true;
} else if ($isPost) {
    logData("Edit Product", "Missing value!", WARNING_LOG);
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
    <title><?= PAGE_NAME ?> - Admin - Product - Edit</title>

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

<!-- show error popup -->
<?php
if (isset($processingError) && $processingError) {
    show_popup();
}
?>

</body>
</html>

