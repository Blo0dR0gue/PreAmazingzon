<!-- TODO Comment -->

<?php
require_once "../../include/site_php_head.inc.php";

UserController::redirectIfNotAdmin();   // User is not allowed to be here.

$isPost = strtolower($_SERVER["REQUEST_METHOD"]) === "post";

if (isset($_POST["title"]) && isset($_POST["cat"]) && isset($_POST["description"]) && $isPost) {

    $category = CategoryController::insert(
        $_POST["title"],
        $_POST["description"],
        $_POST["cat"]
    );

    if (isset($category)) {
        logData("Add Category", "Category with id " . $category->getId() . "got created.");
        header("LOCATION: " . ADMIN_PAGES_DIR . 'page_categories.php?message=Category%20updated');  // go to admin categories page
        die();
    }
    $processingError = true;
    logData("Add Category", "Category could not be created!", CRITICAL_LOG);
} else if ($isPost) {
    logData("Add Category", "Missing values!", WARNING_LOG);
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
    <title><?= PAGE_NAME ?> - Admin - Category - Add</title>

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

</body>
</html>