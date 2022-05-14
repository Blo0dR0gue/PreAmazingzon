<!-- TODO COMMENT-->

<?php
require_once "../../include/site_php_head.inc.php";

if (!isset($_SESSION["login"]) || !isset($_SESSION["isAdmin"]) || !$_SESSION["isAdmin"]) {
    header("LOCATION: " . ROOT_DIR);    //User is not allowed to be here.
    die();
}

//Load required Controllers
require_once CONTROLLER_DIR . DIRECTORY_SEPARATOR . 'controller_product.php';
require_once CONTROLLER_DIR . DIRECTORY_SEPARATOR . 'controller_category.php';

?>

<!DOCTYPE html>
<html class="h-100" lang="en">
<head>
    <?php require_once INCLUDE_DIR . DIRECTORY_SEPARATOR . "site_html_head.inc.php"; ?>
    <title><?= PAGE_NAME ?> - Admin - Products</title>

    <!-- file specific includes -->
    <link rel="stylesheet" href="<?= STYLE_DIR . DIRECTORY_SEPARATOR . "style_admin_products.css"; ?>">
</head>

<body class="d-flex flex-column h-100">
<!-- header -->
<?php require INCLUDE_DIR . DIRECTORY_SEPARATOR . "site_header.inc.php"; ?>

<!-- main body -->
<main class="flex-shrink-0">

    <h3>All products</h3>
    <hr>

    <!--Toolbar -->
    <div class="d-flex flex-wrap flex-row align-items-middle border-top border-bottom border-2 pt-3 pb-3" id="filter"></div>

    <hr>

    <table class="table">
        <thead class="thead-light">
        <tr>
            <th scope="col" style="width: 5%">Action</th>
            <th scope="col" style="width: 3%">#</th>
            <th scope="col" style="width: 5%; text-align: center"><i class="fa fa-image"></i></th>
            <th scope="col" style="width: 45%">Title</th>
            <th scope="col" style="width: 7%">Price</th>
            <th scope="col" style="width: 7%">Shipping Cost</th>
            <th scope="col">Category</th>
            <th scope="col" style="width: 5%">Stock</th>
        </tr>
        </thead>
        <tbody>
        <?php
        $products = ProductController::getAllProducts();
        foreach ($products as $product):
            ?>
            <tr>
                <td style="vertical-align: middle;">
                    <a href="<?= ADMIN_PAGES_DIR . DIRECTORY_SEPARATOR . "page_add_product.php" ?>" class="btn btn-success btn-sm" data-toggle="tooltip" data-placement="left"
                       title="Add a new product">
                        <i class="fa fa-plus"></i>
                    </a>
                    <a href="#" class="btn btn-danger btn-sm" data-toggle="tooltip" data-placement="left"
                       title="Delete product">
                        <i class="fa fa-trash "></i>
                    </a>
                </td>
                <th scope="row"><?= $product->getID(); ?></th>
                <td style="text-align: center">
                    <a href="<?= ADMIN_PAGES_DIR . DIRECTORY_SEPARATOR . "page_product_edit.php?id=" . $product->getId(); ?>">
                        <img src="<?= $product->getMainImg(); ?>"
                             class="tbl-img" alt="main img"/>
                    </a>
                </td>
                <td>
                    <a href="<?= ADMIN_PAGES_DIR . DIRECTORY_SEPARATOR . "page_product_edit.php?id=" . $product->getId(); ?>"
                       class="mb-0 h5 text-decoration-none text-blue"><?= $product->getTitle() ?></a>
                </td>
                <td>
                    <?= $product->getPriceFormatted(); ?>
                </td>
                <td>
                    <?= $product->getShippingCostFormatted(); ?>
                </td>
                <td>
                    <a href="<?= ADMIN_PAGES_DIR . DIRECTORY_SEPARATOR . "page_categories.php?id=" . ($product->getCategoryID() ?? "") ?>"
                       class="text-decoration-none text-blue">
                        <?= CategoryController::getNameById($product->getCategoryID()) ?>
                    </a>
                </td>
                <td><?= $product->getStock(); ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

</main>

<!-- footer -->
<?php require INCLUDE_DIR . DIRECTORY_SEPARATOR . "site_footer.inc.php"; ?>

</body>
</html>
