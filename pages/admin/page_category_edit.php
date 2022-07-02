<?php

// TODO COMMENT

require_once "../../include/site_php_head.inc.php";

UserController::redirectIfNotAdmin();   // User is not allowed to be here.

$categoryID = $_GET["id"];
if (isset($categoryID) && is_numeric($categoryID)) {
    $category = CategoryController::getByID(intval($categoryID));

    if (!isset($category)) {
        logData("Edit Category", "Category with id " . $categoryID . "not found!", WARNING_LOG);
        header("LOCATION: " . ADMIN_PAGES_DIR . "page_categories.php"); // Redirect, if no category is found.
        die();
    }
} else {
    logData("Edit Category", "Missing value!", WARNING_LOG);
    header("LOCATION: " . ADMIN_PAGES_DIR . "page_categories.php"); // Redirect, if no number is passed.
    die();
}

$isPost = strtolower($_SERVER["REQUEST_METHOD"]) === "post";

if (isset($_POST["title"]) && isset($_POST["cat"]) && isset($_POST["description"]) && $isPost) {

    $category = CategoryController::update(
        $category,
        $_POST["title"],
        $_POST["description"],
        $_POST["cat"]
    );

    if (isset($category)) {
        logData("Edit Category", "Category with id " . $category->getId() . "got updated.");
        header("LOCATION: " . ADMIN_PAGES_DIR . 'page_categories.php');  // go to admin categories page
        // TODO success msg?
        die();
    }
    $processingError = true;
}else if($isPost){
    logData("Edit Category", "Missing values!", WARNING_LOG);
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
    <title><?= PAGE_NAME ?> - Admin - Category - Edit</title>

    <!-- file specific includes-->
    <link rel="stylesheet" href="<?= STYLE_DIR . "style_admin_pages.css"; ?>">
</head>

<body class="d-flex flex-column h-100">
<!-- header -->
<?php require_once INCLUDE_DIR . "site_header.inc.php"; ?>

<!-- main body -->
<main class="m-auto w-100 px-3" style="max-width: 800px">
    <?php require_once INCLUDE_ADMIN_DIR . "admin_category_add_edit.inc.php"; ?>
</main>

<!-- footer -->
<?php require_once INCLUDE_DIR . "site_footer.inc.php" ?>

<!-- show error popup -->
<?php
if (isset($processingError)) {   // processing error
    show_popup(
        "Edit Category Error",
        "An error occurred while updating the category."
    );
}
?>

</body>
</html>

