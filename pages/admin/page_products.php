<!-- TODO COMMENT-->

<?php require_once "../../include/site_php_head.inc.php"; ?>

<?php
UserController::redirectIfNotAdmin();   // User is not allowed to be here.

// pagination stuff TODO do pagination uniformly
$page = (isset($_GET['page']) && is_numeric($_GET['page'])) ? $_GET['page'] : 1;  // Current pagination page number
$offset = ($page - 1) * LIMIT_OF_SHOWED_ITEMS;                                    // Calculate offset for pagination
$productCount = ProductController::getAmountOfProducts(null);           // Get the total amount of products   // TODO search?
$totalPages = ceil($productCount / LIMIT_OF_SHOWED_ITEMS);                  // Calculate the total amount of pages

$products = ProductController::getProductsInRange(false, $offset, LIMIT_OF_SHOWED_ITEMS);
?>

<!DOCTYPE html>
<html class="h-100" lang="en">
<head>
    <?php require_once INCLUDE_DIR . "site_html_head.inc.php"; ?>
    <title><?= PAGE_NAME ?> - Admin - Products</title>

    <!-- file specific includes-->
    <link rel="stylesheet" href="<?= STYLE_DIR . "style_admin_pages.css"; ?>">

    <!--Add page script-->
    <script src="<?= SCRIPT_DIR . "admin_products_page.js" ?>"></script>

    <?php require_once INCLUDE_MODAL_DIR . "modal_popup.inc.php"; ?>
</head>

<body class="d-flex flex-column h-100">
<!-- header -->
<?php require_once INCLUDE_DIR . "site_header.inc.php"; ?>

<!-- main body -->
<main class="flex-shrink-0 container">

    <!-- page header -->
    <div class="d-flex align-items-end">
        <h1 class="mt-4 ms-2 mb-0 mr-auto">All products</h1>
        <!-- add button -->
        <a type="button" class="btn btn-warning ms-auto" href="<?= ADMIN_PAGES_DIR . "page_product_add.php" ?>">
            <em class="fa fa-plus"></em> Add product
        </a>
    </div>
    <hr class="mt-2">

    <!-- product table -->
    <table class="table" aria-label="Product Table">
        <!-- table head -->
        <thead class="thead-light">
        <tr>
            <th scope="col" style="width: 3%"></th>
            <th scope="col" style="width: 5%">#</th>
            <th scope="col" style="width: 15%; text-align: center"><em class="fa fa-image"></em></th>
            <th scope="col" style="width: 40%">Title</th>
            <th scope="col" style="width: 7%">Price</th>
            <th scope="col" style="width: 7%">Shipping</th>
            <th scope="col" style="width: 13%">Category</th>
            <th scope="col" style="width: 5%">Stock</th>
            <th scope="col" style="width: 5%">Active</th>
        </tr>
        </thead>

        <!-- table body -->
        <tbody>
        <?php
        if (isset($products) && count($products) > 0):
            foreach ($products as $product): ?>
                <tr>
                    <td class="align-middle" data-th="">
                        <a href="<?= ADMIN_PAGES_DIR . "page_product_edit.php?id=" . $product->getId(); ?>"
                           class="btn btn-warning btn-sm mb-1" data-toggle="tooltip" data-placement="left"
                           title="Edit product" style="padding-inline: 10px">
                            <em class="fa fa-pencil"></em>
                        </a>
                        <button class="btn btn-sm <?= $product->isActive() ? "btn-success" : "btn-warning" ?>"
                                data-toggle="tooltip" data-placement="left" title="(De-) Activate Product"
                                onclick="onToggleProductActivation(this, <?= $product->getId(); ?>)">
                            <em class="fa <?= $product->isActive() ? "fa-toggle-on" : "fa-toggle-off" ?>"
                                id="activeBtnImg<?= $product->getId() ?>"></em>
                        </button>
                    </td>

                    <td data-th="#">
                        <strong><?= $product->getID(); ?></strong>
                    </td>

                    <td style="text-align: center" data-th="">
                        <div class="border rounded d-flex justify-content-center align-items-center overflow-hidden mb-1"
                             style="height: 150px;">
                            <img src="<?= $product->getMainImg(); ?>" class="mh-100 mw-100" alt="main img"/>
                        </div>
                    </td>

                    <td data-th="Title">
                        <a href="<?= ADMIN_PAGES_DIR . "page_product_edit.php?id=" . $product->getId(); ?>"
                           class="mb-0 h5 text-decoration-none text-blue"><?= $product->getTitle() ?></a>
                    </td>

                    <td data-th="Price">
                        <?= $product->getPriceFormatted(); ?>
                    </td>

                    <td data-th="Shipping">
                        <?= $product->getShippingCostFormatted(); ?>
                    </td>

                    <td data-th="Category">
                        <a href="<?= ADMIN_PAGES_DIR . "page_categories.php?id=" . ($product->getCategoryID() ?? "") ?>"
                           class="text-decoration-none text-blue">
                            <?= CategoryController::getNameById($product->getCategoryID()) ?>
                        </a>
                    </td>

                    <td data-th="Stock">
                        <?= $product->getStock(); ?>
                    </td>

                    <td data-th="Active" data-id="<?= $product->getId(); ?>">
                        <?= $product->isActive() ? 'Yes' : 'No'; ?>
                    </td>

                </tr>
            <?php endforeach;
        else: ?>
            <tr>
                <td colspan="9" style="text-align: center">
                    <p><em class="mb-3">No products are available.</em></p>
                </td>
            </tr>
        <?php endif; ?>
        </tbody>
    </table>
</main>

<!-- enable tooltips on this page -->
<script src="<?= SCRIPT_DIR . "tooltip_enable.js" ?>"></script>

<!-- confirm modal -->
<?php require_once INCLUDE_MODAL_DIR . "modal_confirm.inc.php"; ?>

<!-- dynamic popup modal -->
<?php require_once INCLUDE_MODAL_DIR . "modal_popup.inc.php"; ?>

<!-- pagination -->
<?php require INCLUDE_DIR . "dyn_pagination.inc.php" ?>

<!-- footer -->
<?php require_once INCLUDE_DIR . "site_footer.inc.php"; ?>

<!--Status messages-->
<?php

if (!empty($_GET["message"])) {
    show_popup(
        "Information",
        $_GET["message"]
    );
}

?>


</body>
</html>
