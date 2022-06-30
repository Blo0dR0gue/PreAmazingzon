<!-- TODO COMMENT-->

<?php require_once "../../include/site_php_head.inc.php"; ?>

<?php
UserController::redirectIfNotAdmin();   // User is not allowed to be here.

// pagination stuff
$page = (isset($_GET['page']) && is_numeric($_GET['page'])) ? $_GET['page'] : 1;  // Current pagination page number
$offset = ($page - 1) * LIMIT_OF_SHOWED_ITEMS;                                    // Calculate offset for pagination
$categoryCount = CategoryController::getAmountOfCategories(null);                 // Get the total amount of categories
$totalPages = ceil($categoryCount / LIMIT_OF_SHOWED_ITEMS);                       // Calculate the total amount of pages

$categories = CategoryController::getCategoriesInRange($offset, LIMIT_OF_SHOWED_ITEMS);
?>

<!DOCTYPE html>
<html class="h-100" lang="en">
<head>
    <?php require_once INCLUDE_DIR . "site_html_head.inc.php"; ?>
    <title><?= PAGE_NAME ?> - Admin - Categories</title>

    <!-- file specific includes-->
    <link rel="stylesheet" href="<?= STYLE_DIR . "style_admin_pages.css"; ?>">
    <?php require_once INCLUDE_DIR . "modal_popup.inc.php"; ?>
</head>

<body class="d-flex flex-column h-100">
<!-- header -->
<?php require_once INCLUDE_DIR . "site_header.inc.php"; ?>

<!-- main body -->
<main class="flex-shrink-0 container">

    <!-- page header -->
    <div class="d-flex align-items-end">
        <h1 class="mt-4 ms-2 mb-0 mr-auto">All categories</h1>
        <!-- add button -->
        <a type="button" class="btn btn-warning ms-auto" href="<?= ADMIN_PAGES_DIR . "page_category_add.php" ?>">
            <em class="fa fa-plus"></em> Add category
        </a>
    </div>
    <hr class="mt-2">

    <!-- category table -->
    <table class="table" aria-label="Categories Table">
        <!-- table head -->
        <thead class="thead-light">
        <tr>
            <th scope="col" style="width: 5%"></th>
            <th scope="col" style="width: 10%">#</th>
            <th scope="col" style="width: 45%">Title</th>
            <th scope="col" style="width: 20%">Super</th>
        </tr>
        </thead>

        <!-- table body -->
        <tbody>
        <?php foreach ($categories as $category): ?>
            <tr>
                <td class="align-middle" data-th="">
                    <a href="<?= ADMIN_PAGES_DIR . "page_category_edit.php?id=" . $category->getId(); ?>"
                       class="btn btn-warning btn-sm mb-1" data-toggle="tooltip" data-placement="left"
                       title="Edit category">
                        <em class="fa fa-pencil"></em>
                    </a>
                <!-- TODO toggle active? or simply no delete? -->
                </td>

                <td data-th="#">
                    <strong><?= $category->getID(); ?></strong>
                </td>

                <td data-th="Title">
                    <a href="<?= ADMIN_PAGES_DIR . "page_category_edit.php?id=" . $category->getId(); ?>"
                       class="mb-0 h5 text-decoration-none text-blue"><?= $category->getName() ?></a>
                </td>

                <td data-th="Super">
                    <a href="<?= ADMIN_PAGES_DIR . "page_categories.php?id=" . ($category->getParentID() ?? "") ?>"
                       class="text-decoration-none text-blue text-black">
                        <?= CategoryController::getNameById($category->getParentID()) ?>
                    </a>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</main>

<!-- enable tooltips on this page -->
<script src="<?= SCRIPT_DIR . "tooltip_enable.js" ?>"></script>

<!-- confirm modal -->
<?php require_once INCLUDE_DIR . "modal_confirm.inc.php"; ?>

<!-- pagination -->
<?php require INCLUDE_DIR . "dyn_pagination.inc.php" ?>

<!-- footer -->
<?php require_once INCLUDE_DIR . "site_footer.inc.php"; ?>

</body>
</html>
