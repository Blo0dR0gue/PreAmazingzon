<!-- Page to show all categories -->
<!-- TODO comment -->

<?php require_once "../include/site_php_head.inc.php"; ?>

<?php
// get category
if (isset($_GET["id"]) && is_numeric($_GET["id"])) {
    $category = CategoryController::getByID(intval($_GET["id"]));

    if (!isset($category)) {
        header("LOCATION: " . ROOT_DIR);   // redirect, if no category is found.
        die();
    }
} else {
    $category = new Category(-1, "Root", "", null);
}

// get sub categories
$subCategories = CategoryController::getSubCategories($category->getId());

// get products
$page = (isset($_GET['page']) && is_numeric($_GET['page'])) ? $_GET['page'] : 1;                // Current pagination page number
$offset = ($page - 1) * LIMIT_OF_SHOWED_ITEMS;                                                  // Calculate offset for pagination
$productCount = ProductController::getAmountOfActiveProductsInCategory($category->getId());     // Get the total Amount of products in category
$totalPages = ceil($productCount / LIMIT_OF_SHOWED_ITEMS);                                      // Calculate the total amount of pages

$products = ProductController::getProductsByCategoryIDInRange($category->getId(), $offset, LIMIT_OF_SHOWED_ITEMS);
?>

<!DOCTYPE html>
<html class="h-100" lang="en">
<head>
    <?php require_once INCLUDE_DIR . "site_html_head.inc.php"; ?>
    <title><?= PAGE_NAME ?></title>
</head>

<body class="d-flex flex-column h-100">
<!-- header -->
<?php require_once INCLUDE_DIR . "site_header.inc.php"; ?>

<!-- main body -->
<main class="flex-shrink-0">
    <section class="container py-4">
        <!-- path -->
        <?php if($category->getId() != -1){
            echo "<i class='text-muted mb-2'>" . CategoryController::getPathToCategory($category->getId()) . "</i>";
        } ?>

        <!-- category row -->
        <?php if ($page == 1) { ?>
            <div class="row mb-4">
                <h3>Categories in '<?= $category->getName() ?>'</h3>
                <hr>
                <?php
                if(count($subCategories) > 0){
                    foreach ($subCategories as $subCategory){
                        require INCLUDE_ELEMENTS_DIR . "elem_subcategory_card.inc.php";
                    }
                } else {
                    echo "<h5 class='text-center text-muted mb-5'><i>no subcategories found</i></h5>";
                }
                ?>
            </div>
        <?php } ?>

        <!-- product row -->
        <div class="row">
            <h3>Products in '<?= $category->getName() ?>'</h3>
            <hr>
            <?php
            if (count($products) > 0) {
                foreach ($products as $product) {
                    require INCLUDE_ELEMENTS_DIR . "elem_product_card.inc.php";
                }
            } else {
                echo "<h5 class='text-center text-muted mb-5'><i>no products found</i></h5>";
            }
            ?>
        </div>
    </section>
</main>

<!-- pagination -->
<?php require INCLUDE_DIR . "dyn_pagination.inc.php" ?>

<!-- footer -->
<?php require_once INCLUDE_DIR . "site_footer.inc.php" ?>

</body>
</html>
