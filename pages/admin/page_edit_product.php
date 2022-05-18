<?php
require_once "../../include/site_php_head.inc.php";

if (!isset($_SESSION["login"]) || !isset($_SESSION["isAdmin"]) || !$_SESSION["isAdmin"]) {
    header("LOCATION: " . ROOT_DIR);    //User is not allowed to be here.
    die();
}

//Load required Controllers
require_once CONTROLLER_DIR . DS . 'controller_product.php';
require_once CONTROLLER_DIR . DS . 'controller_category.php';

$productID = $_GET["id"];
if (isset($productID) && is_numeric($productID)) {
    $product = ProductController::getByID(intval($productID));

    if (!isset($product)) {
        header("LOCATION: " . ADMIN_PAGES_DIR . DS . "page_products.php"); //Redirect, if no product is found.
        die();
    }

    $category = CategoryController::getById($product->getCategoryID());
    //Variable, which is used by the radio buttons
    //$cat = $category;

} else {
    header("LOCATION: " . ADMIN_PAGES_DIR . DS . "page_products.php"); //Redirect, if no number is passed.
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
    );

    if (isset($product)) {

        $error = ProductController::deleteSelectedImages($product->getId(), $_POST["deletedImgIDs"]);

        if (!$error) {
            $error = ProductController::updateMainImg($product->getId(), $_POST["mainImgID"]);

            if (!$error) {

                $error = ProductController::uploadImages($product->getId(), $_FILES["files"] ?? null, $_POST["mainImgID"]);

                if (!$error) {
                    header("LOCATION: " . ADMIN_PAGES_DIR . DS . 'page_products.php');  // go to admin products page
                    //TODO success msg?
                    die();
                }

            }
        }
    }
    $processingError = true;
}
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

    <?php require_once INCLUDE_DIR . DS . 'admin' . DS . "admin_product_add_edit.inc.php"; ?>

</main>

<!-- footer -->
<?php require INCLUDE_DIR . DS . "site_footer.inc.php" ?>

<!-- show error popup -->
<?php
if (isset($processingError)) {   // processing error
    show_popup(
        "Edit Product Error",
        "ALARM" //TODO
    );
}
?>

</body>
</html>
