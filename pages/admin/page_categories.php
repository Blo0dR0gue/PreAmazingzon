<!-- TODO COMMENT-->

<?php require_once "../../include/site_php_head.inc.php"; ?>

<?php
UserController::redirectIfNotAdmin();   //User is not allowed to be here.

// TODO change pagination to categories
// pagination stuff
$page = (isset($_GET['page']) && is_numeric($_GET['page'])) ? $_GET['page'] : 1;  // Current pagination page number
$offset = ($page - 1) * LIMIT_OF_SHOWED_ITEMS;                                    // Calculate offset for pagination
$categoryCount = CategoryController::getAmountOfCategories(null);       // Get the total amount of categories   //TODO search?
$totalPages = ceil($categoryCount / LIMIT_OF_SHOWED_ITEMS);                  // Calculate the total amount of pages

$products = CategoryController::getCategoriesInRange($offset, LIMIT_OF_SHOWED_ITEMS);
?>

<!-- TODO if deleted pagination triggered modal each time -->

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
        <a type="button" class="btn btn-warning ms-auto" href="<?= ADMIN_PAGES_DIR . "page_product_add.php" // TODO change?>">
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
            <th scope="col" style="width: 20%; text-align: center"><em class="fa fa-image"></em></th>
            <th scope="col" style="width: 45%">Title</th>
            <th scope="col" style="width: 20%">Super</th>
        </tr>
        </thead>

        <!-- table body -->
        <tbody>
        <?php foreach ($products as $product): ?>
            <tr>
                <td class="align-middle" data-th="">
                    <a href="<?= ADMIN_PAGES_DIR . "page_product_edit.php?id=" . $product->getId(); ?>"
                       class="btn btn-warning btn-sm mb-1" data-toggle="tooltip" data-placement="left"
                       title="Edit category">
                        <em class="fa fa-pencil"></em>
                    </a>
                    <a class="btn btn-secondary btn-sm" data-toggle="tooltip" data-placement="left"
                       title="Delete category"
                       onclick="openConfirmModal(<?= "'Do you really want to delete the Category: '" . $product->getTitle() . "', with ID: " . $product->getId() . "?'" ?>,
                               'Delete Category?',
                               '<?= str_replace(DS, "/", INCLUDE_HELPER_DIR . "helper_delete_product.inc.php?id=" . $product->getId()); ?>')">
                        <em class="fa fa-trash "></em>
                    </a>
                </td>

                <td data-th="#">
                    <strong><?= $product->getID(); ?></strong>
                </td>

                <td style="text-align: center" data-th="">
                    <div class="border rounded d-flex justify-content-center align-items-center overflow-hidden mb-1"
                         style="height: 160px;">
                        <img src="<?= $product->getMainImg(); ?>" class="mh-100 mw-100" alt="main img"/>
                    </div>
                </td>

                <td data-th="Title">
                    <a href="<?= ADMIN_PAGES_DIR . "page_product_edit.php?id=" . $product->getId(); ?>"
                       class="mb-0 h5 text-decoration-none text-blue"><?= $product->getTitle() ?></a>
                </td>

                <td data-th="Super">
                    <a href="<?= ADMIN_PAGES_DIR . "page_categories.php?id=" . ($product->getCategoryID() ?? "") ?>"
                       class="text-decoration-none text-blue">
                        <?= CategoryController::getNameById($product->getCategoryID()) ?>
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

<!-- show info popup -->
<?php
if (isset($_GET["deleted"]) || isset($_GET["other"])) {   // success messages
    $msg = "";
    if (isset($_GET["deleted"])) {
        $msg = "The category got deleted!";
    }
    show_popup("Categories", $msg);
}
?>

</body>
</html>
