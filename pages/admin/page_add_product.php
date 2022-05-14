<?php
require_once "../../include/site_php_head.inc.php";

if (!isset($_SESSION["login"]) || !isset($_SESSION["isAdmin"]) || !$_SESSION["isAdmin"]) {
    header("LOCATION: " . ROOT_DIR);    //User is not allowed to be here.
    die();
}

//Load required Controllers
require_once CONTROLLER_DIR . DIRECTORY_SEPARATOR . 'controller_product.php';
require_once CONTROLLER_DIR . DIRECTORY_SEPARATOR . 'controller_category.php';

if (!empty($_POST["title"]) && !empty($_POST["cat"]) && !empty($_POST["description"]) && !empty($_POST["price"])
    && !empty($_POST["shipping"]) && !empty($_POST["stock"])) {

    //TODO validation

    $product = ProductController::addNew(
        $_POST["title"],
        $_POST["cat"],
        $_POST["description"],
        $_POST["price"],
        $_POST["shipping"],
        $_POST["stock"]
    );

    if (isset($product)) {
        header("LOCATION: " . ADMIN_PAGES_DIR . DIRECTORY_SEPARATOR . 'page_products.php');  // go to admin products page
        die();
    }

    $processingError = true;
}
?>

<!DOCTYPE html>
<html class="h-100" lang="en">
<head>
    <?php require_once INCLUDE_DIR . DIRECTORY_SEPARATOR . "site_html_head.inc.php"; ?>
    <title><?= PAGE_NAME ?> - About</title>
</head>

<body class="d-flex flex-column h-100">
<!-- header -->
<?php require INCLUDE_DIR . DIRECTORY_SEPARATOR . "site_header.inc.php"; ?>

<!-- main body -->
<main class="m-auto w-100 px-3" style="max-width: 800px">


    <!--TODO validation -->
    <form action="" method="POST" enctype="multipart/form-data">
        <div class="card">
            <div class="card-header">
                <!-- title -->
                <h2 class="mb-2 mt-4">Add a new product</h2>
            </div>
            <div class="card-body">

                <div class="form-group">
                    <label for="title">Product Title</label>
                    <input type="text" value="" name="title" id="title" class="form-control"
                           placeholder="A New Product Title">
                </div>

                <div class="form-group">

                    <label for="category">Category</label>
                    <div class="col-12 col-md-4 p-0">
                        <div class="p-0 dropdown">
                            <a class="btn btn-secondary dropdown-toggle" href="#" role="button" id="priceFilter"
                               data-bs-toggle="dropdown" aria-expanded="false">
                                Categories
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="priceFilter">
                                <!--TODO Rework -> tree like?; Replace button next with selected -->
                                <?php foreach (CategoryController::getAll() as $category) { ?>
                                    <li>
                                        <div class="dropdown-item">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="cat"
                                                       id="categoryRadios<?php echo $category->getId() ?>"
                                                       value="<?php echo $category->getId() ?>" <?php if (isset($cat)) if (in_array($category->getId(), $cat)) echo "checked"; ?>>
                                                <div class="p-0">
                                                    <label class="form-check-label"
                                                           for="categoryRadios<?php echo $category->getId() ?>">
                                                        <?= $category->getName(); ?>
                                                    </label>
                                                </div>
                                                </input>
                                                <!-- FIXME Warning:(90, 49) Closing tag matches nothing-->
                                            </div>
                                        </div>
                                    </li>
                                <?php } ?>

                            </ul>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="description">Product Description</label>
                    <textarea class="form-control" id="description" name="description" rows="3"
                              placeholder="My New Cool Product"></textarea>
                </div>

                <div class="form-group">
                    <label for="price">Price</label>
                    <div class="input-group p-0">
                        <input type="number" id="price" name="price" value="0.00" step='0.01' class="form-control">
                        <span class="input-group-text"><?= CURRENCY_SYMBOL ?></span>
                    </div>


                    <div class="form-group">
                        <label for="shipping">Shipping Cost</label>
                        <div class="input-group p-0">
                            <input type="number" id="shipping" name="shipping" value="0.00" step='0.01'
                                   class="form-control">
                            <span class="input-group-text"><?= CURRENCY_SYMBOL ?></span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="price">Stock</label>
                        <div class="input-group p-0">
                            <div class="input-group p-0">
                                <input type="number" id="stock" name="stock" class="form-control" value="0">
                                <span class="input-group-text">Pcs.</span>
                            </div>
                        </div>

                    </div>

                    <div class="form-group">

                        <label for="pictures" class="form-label fs-4">Product Images</label>
                        <!--TODO-->

                    </div>

                    <br>

                    <div class="card-footer">
                        <a href="index.php" class="btn btn-danger">Abort</a>
                        <button class="btn btn-success">Save</button>
                    </div>
                </div>

                <div>
    </form>

</main>

<!-- footer -->
<?php require INCLUDE_DIR . DIRECTORY_SEPARATOR . "site_footer.inc.php" ?>

<!-- show error popup -->
<?php
if (isset($processingError)) // processing error
{
    show_popup(
        "Add Product Error",
        "ALARM" //TODO
    );
}
?>

</body>
</html>

