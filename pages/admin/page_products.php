<!-- TODO COMMENT-->

<?php
require_once "../../include/site_php_head.inc.php";

if (!isset($_SESSION["login"]) || !isset($_SESSION["isAdmin"]) || !$_SESSION["isAdmin"]) {
    header("LOCATION: " . ROOT_DIR);    //User is not allowed to be here.
    die();
}

//Load required Controllers
require_once CONTROLLER_DIR . DS . 'controller_product.php';
require_once CONTROLLER_DIR . DS . 'controller_category.php';

// Max amount of showed Items
$amount = LIMIT_OF_SHOWED_ITEMS;
// Current pagination page number
$page = (isset($_GET['page']) && is_numeric($_GET['page'])) ? $_GET['page'] : 1;
// Calculate offset for pagination
$offset = ($page - 1) * $amount;
// Get the total Amount of Products
$productCount = ProductController::getAmountOfProducts(null);   //TODO search?
// Calculate the total amount of pages
$totalPages = ceil($productCount / $amount);

$products = ProductController::getProductsInRange($offset, $amount);

?>

<!DOCTYPE html>
<html class="h-100" lang="en">
<head>
    <?php require_once INCLUDE_DIR . DS . "site_html_head.inc.php"; ?>
    <?php require_once INCLUDE_DIR . DS . "modal_popup.inc.php"; ?>
    <title>Admin - Products</title>

    <!-- file specific includes-->
    <link rel="stylesheet" href="<?= STYLE_DIR . DS . "style_admin_pages.css"; ?>">
</head>

<body class="d-flex flex-column h-100">
<!-- header -->
<?php require_once INCLUDE_DIR . DS . "site_header.inc.php"; ?>

<!-- main body -->
<main class="flex-shrink-0">

    <h3>All products</h3>
    <hr>

    <!--Toolbar -->
    <div class="d-flex flex-wrap flex-row align-items-middle border-top border-bottom border-2 pt-3 pb-3"
         id="toolbar">
        <div class="btn-group" role="group" style="margin-left: 5px;">
            <a type="button" class="btn btn-success"
               href="<?= ADMIN_PAGES_DIR . DS . "page_add_product.php" ?>"><i class="fa fa-plus"></i> Add a product
            </a>
            <a type="button" class="btn btn-secondary">Middle</a>
            <a type="button" class="btn btn-secondary">Right</a>
        </div>
    </div>

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
        foreach ($products as $product):
            ?>
            <tr>
                <td style="vertical-align: middle;">
                    <a href="<?= ADMIN_PAGES_DIR . DS . "page_add_product.php" ?>" class="btn btn-success btn-sm"
                       data-toggle="tooltip" data-placement="left"
                       title="Add a new product">
                        <i class="fa fa-plus"></i>
                    </a>
                    <a class="btn btn-danger btn-sm" data-toggle="tooltip" data-placement="left"
                       title="Delete product"
                       onclick="openConfirmModal(<?= "'Do you really want to delete the Product: " . $product->getTitle() . ", with the ID: " . $product->getId() . "?'" ?>,
                               'Delete Product?',
                               '<?= str_replace(DS, "/", INCLUDE_HELPER_DIR . DS . "helper_delete_product.inc.php?id=" . $product->getId()); ?>')">
                        <!-- TODO set modal title or use custom modal -->
                        <i class="fa fa-trash "></i>
                        <!-- TODO do link -->
                    </a>
                </td>
                <th scope="row"><?= $product->getID(); ?></th>
                <td style="text-align: center">
                    <a href="<?= ADMIN_PAGES_DIR . DS . "page_edit_product.php?id=" . $product->getId(); ?>">
                        <img src="<?= $product->getMainImg(); ?>"
                             class="tbl-img" alt="main img" data-id="1"/>
                    </a>
                </td>
                <td>
                    <a href="<?= ADMIN_PAGES_DIR . DS . "page_edit_product.php?id=" . $product->getId(); ?>"
                       class="mb-0 h5 text-decoration-none text-blue"><?= $product->getTitle() ?></a>
                </td>
                <td>
                    <?= $product->getPriceFormatted(); ?>
                </td>
                <td>
                    <?= $product->getShippingCostFormatted(); ?>
                </td>
                <td>
                    <a href="<?= ADMIN_PAGES_DIR . DS . "page_categories.php?id=" . ($product->getCategoryID() ?? "") ?>"
                       class="text-decoration-none text-blue">
                        <?= CategoryController::getNameById($product->getCategoryID()) ?>
                    </a>
                </td>
                <td><?= $product->getStock(); ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

    <!-- confirm modal -->
    <?php require_once INCLUDE_DIR . DS . "modal_confirm.inc.php"; ?>

</main>

<!-- pagination -->
<?php require INCLUDE_DIR . DS . "dyn_pagination.inc.php" ?>

<!-- footer -->
<?php require INCLUDE_DIR . DS . "site_footer.inc.php"; ?>


<!-- show info popup -->
<?php
if (isset($_GET["deleted"]) || isset($_GET["other"])) {   // login error
    $msg = "";
    if (isset($_GET["deleted"])) {
        $msg = "The product got deleted!";
    } else if (isset($_GET["other"])) {
        $msg = "test";  //TODO remove
    }

    show_popup(
        "Products",
        $msg
    );
}
?>

</body>
</html>
