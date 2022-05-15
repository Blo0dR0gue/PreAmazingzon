<?php
require_once "../../include/site_php_head.inc.php";

if (!isset($_SESSION["login"]) || !isset($_SESSION["isAdmin"]) || !$_SESSION["isAdmin"]) {
    header("LOCATION: " . ROOT_DIR);    //User is not allowed to be here.
    die();
}

//Load required Controllers
require_once CONTROLLER_DIR . DIRECTORY_SEPARATOR . 'controller_product.php';
require_once CONTROLLER_DIR . DIRECTORY_SEPARATOR . 'controller_category.php';

$isPost = strtolower($_SERVER["REQUEST_METHOD"]) === "post";

if (isset($_POST["title"]) && isset($_POST["cat"]) && isset($_POST["description"]) && isset($_POST["price"])
    && isset($_POST["shipping"]) && isset($_POST["stock"]) && $isPost) {

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
        $errors = ProductController::uploadImages($product->getId(), $_FILES["files"], $_POST["mainImgID"]);
        if (!$errors) {
            header("LOCATION: " . ADMIN_PAGES_DIR . DIRECTORY_SEPARATOR . 'page_products.php');  // go to admin products page
            //TODO success msg?
            die();
        }
    }

    $processingError = true;
}
?>

<!DOCTYPE html>
<html class="h-100" lang="en">
<head>
    <?php
    require_once INCLUDE_DIR . DIRECTORY_SEPARATOR . "site_html_head.inc.php";
    require INCLUDE_DIR . DIRECTORY_SEPARATOR . "modal_popup.inc.php";
    ?>
    <title><?= PAGE_NAME ?> - Admin - Product - Add</title>

    <!-- file specific includes-->
    <link rel="stylesheet" href="<?= STYLE_DIR . DIRECTORY_SEPARATOR . "style_admin_pages.css"; ?>">

</head>

<body class="d-flex flex-column h-100">
<!-- header -->
<?php require INCLUDE_DIR . DIRECTORY_SEPARATOR . "site_header.inc.php"; ?>

<!-- main body -->
<main class="m-auto w-100 px-3" style="max-width: 800px">


    <!--TODO validation-->
    <form action="" id="prodForm" method="POST" enctype="multipart/form-data" class="needs-validation" novalidate>
        <div class="card">
            <div class="card-header">
                <!-- title -->
                <h2 class="mb-2 mt-4">Add a new product</h2>
            </div>
            <div class="card-body">

                <div class="form-group position-relative">
                    <label for="title">Product Title</label>
                    <input type="text" value="" name="title" id="title" class="form-control"
                           placeholder="A New Product Title"
                           required pattern="[a-zäöüA-ZÄÖÜ ,.'-]+">
                    <div class="invalid-tooltip opacity-75">Please enter a valid Product name!</div>
                </div>


                <div class="form-group position-relative">

                    <label for="selectedRadio">Category</label>
                    <div class="row">
                        <div class="col-md-7" style="display: flex">
                            <input id="selectedRadio" type="text" style="width: 450px" required disabled
                                   placeholder="Please select a category!">
                            <div class="invalid-tooltip opacity-75">Please select a Category!
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="p-0 dropdown">
                                <a class="btn btn-secondary dropdown-toggle" href="#" role="button" id="priceFilter"
                                   data-bs-toggle="dropdown" aria-expanded="false">
                                    Categories
                                </a>
                                <ul class="dropdown-menu" aria-labelledby="priceFilter">
                                    <!--TODO Rework -> tree like?; Replace button next with selected-->
                                    <?php foreach (CategoryController::getAll() as $category) { ?>
                                        <li>
                                            <div class="dropdown-item">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="cat"
                                                           id="categoryRadios<?php echo $category->getId() ?>"
                                                           value="<?php echo $category->getId() ?>" <?php if (isset($cat)) if (in_array($category->getId(), $cat)) echo "checked"; ?>
                                                           required onclick="handleRadioUpdate(this)"
                                                           data-name="<?= $category->getName() ?>">
                                                    <div class="p-0">
                                                        <label class="form-check-label"
                                                               for="categoryRadios<?php echo $category->getId() ?>">
                                                            <?= $category->getName(); ?>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                    <?php } ?>
                                </ul>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="form-group position-relative">
                    <label for="description">Product Description</label>
                    <textarea class="form-control" id="description" name="description" rows="3"
                              placeholder="My New Cool Product" required></textarea>
                    <div class="invalid-tooltip opacity-75">Please add a Product text!</div>
                </div>

                <div class="form-group position-relative">
                    <label for="price">Price</label>
                    <div class="input-group p-0">
                        <input type="number" id="price" name="price" value="0.00" step='0.01' class="form-control"
                               required pattern="^([1-9][0-9]*|0)(\.[0-9]{2})?$">
                        <span class="input-group-text"><?= CURRENCY_SYMBOL ?></span>
                        <div class="invalid-tooltip opacity-75">Please choose a correct price!</div>
                    </div>


                    <div class="form-group position-relative">
                        <label for="shipping">Shipping Cost</label>
                        <div class="input-group p-0">
                            <input type="number" id="shipping" name="shipping" value="0.00" step='0.01'
                                   class="form-control" required pattern="^([1-9][0-9]*|0)(\.[0-9]{2})?$">
                            <span class="input-group-text"><?= CURRENCY_SYMBOL ?></span>
                            <div class="invalid-tooltip opacity-75">Please choose a correct price!</div>
                        </div>
                    </div>

                    <div class="form-group position-relative">
                        <label for="stock">Stock</label>
                        <div class="input-group p-0">
                            <div class="input-group p-0">
                                <input type="number" id="stock" name="stock" class="form-control" value="0" required
                                       pattern="[1-9][0-9]*|0">
                                <span class="input-group-text">Pcs.</span>
                                <div class="invalid-tooltip opacity-75">Please choose a correct stock amount!</div>
                            </div>
                        </div>

                    </div>

                    <div class="form-group">

                        <label for="pictures" class="form-label fs-4">Product Images</label>
                        <div id="dropZone" class="drop-zone" ondrop="dropHandler(event, <?= MAX_IMAGE_PER_PRODUCT ?>)"
                             ondragover="dragOverHandler(event)">
                            <div class="drop-texts" id="dropTexts">
                                <span class="drop-text">Click here or drag and drop file</span>
                            </div>
                            <input class="file-input" type="file" id="files" name="files[]" multiple
                                   onchange="filesChanged(this, <?= MAX_IMAGE_PER_PRODUCT ?>)">

                            <section class="container py-3" id="imgContainer">
                                <div id="imgRow" class="row jcenter">
                                </div>
                            </section>
                        </div>

                        <input name="mainImgID" id="mainImgID" type="hidden" value="0">
                    </div>

                    <br>

                    <div class="card-footer">
                        <a href="<?= ROOT_DIR ?>" class="btn btn-danger">Abort</a>
                        <button class="btn btn-success">Save</button>
                    </div>
                </div>

                <div>
    </form>

</main>

<template id="imgBoxTemplate">
    <div class="img-box">
        <img src="<?= IMAGE_DIR . DIRECTORY_SEPARATOR . 'products' . DIRECTORY_SEPARATOR . 'notfound.jpg' ?>"
             class="tbl-img" alt="product_img">
        <button type="button" class="btn btn-warning btn-sm" onclick="deleteImg(this)" data-id="-1">Delete</button>
        <button type="button" name="setMainBtn" class="btn btn-danger btn-sm" onclick="setMainImg(this)" data-id="-1">Set Main</button>
    </div>
</template>

<!-- footer -->
<?php require INCLUDE_DIR . DIRECTORY_SEPARATOR . "site_footer.inc.php" ?>

<!-- load custom form validation script -->
<script src="<?= SCRIPT_DIR . DIRECTORY_SEPARATOR . "form_validation.js" ?>"></script>
<!-- enable tooltips on this page (by default disabled for performance)-->
<script src="<?= SCRIPT_DIR . DIRECTORY_SEPARATOR . "tooltip_enable.js" ?>"></script>
<!-- enable admin page tools-->
<script src="<?= SCRIPT_DIR . DIRECTORY_SEPARATOR . "admin_pages.js" ?>"></script>

<!-- show error popup -->
<?php
if (isset($processingError)) {   // processing error
    show_popup(
        "Add Product Error",
        "ALARM" //TODO
    );
}
?>

</body>
</html>

