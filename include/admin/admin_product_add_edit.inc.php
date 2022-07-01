<!-- TODO comment -->

<form action="" id="prodForm" method="POST" enctype="multipart/form-data" class="needs-validation" novalidate>
    <div class="card my-4">
        <!-- HEADER -->
        <div class="card-header">
            <!-- title -->
            <h2 class="mb-2 mt-4">
                <?php
                if (str_contains($_SERVER["REQUEST_URI"], "add")) {
                    echo "Add Product";
                } elseif (str_contains($_SERVER["REQUEST_URI"], "edit")) {
                    echo "Edit Product";
                } else {
                    echo "Product";
                }
                ?>
            </h2>
        </div>

        <!-- BODY -->
        <div class="card-body">
            <!-- product title -->
            <div class="form-group position-relative mb-1">
                <label for="title">Product Title</label>
                <input type="text" name="title" id="title" class="form-control" placeholder="A New Product Title"
                       value="<?php if (isset($product) && $product instanceof Product) {
                           echo $product->getTitle();
                       } ?>"
                       required pattern="[a-zäöüA-ZÄÖÜ0-9 ,.'-:]{5,}">
                <div class="invalid-tooltip opacity-75">Please enter a valid Product name!</div>
            </div>

            <!-- category select -->
            <div class="form-group position-relative mb-1">
                <label for="selectedRadio">Category</label>
                <select class="form-select" name="cat">
                    <option value="-1" hidden>Select Category</option>

                    <?php foreach (CategoryController::getCategoryTree() as $treeEntry): ?>
                        <option value="<?= $treeEntry["top"]; ?>"
                            <?php
                            if (isset($cat)) {
                                if (in_array($treeEntry["top"], $cat)) {
                                    echo "selected";
                                }
                            } else if (isset($category) && $category instanceof Category) {
                                if ($treeEntry["top"] == $category->getId()) {
                                    echo "selected";
                                }
                            } ?>>
                            <?= $treeEntry["path"]; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <div class="invalid-tooltip opacity-75">Please select a category!</div>
            </div>

            <!-- product description (text) -->
            <div class="form-group position-relative mb-1">
                <label for="description">Product Description</label>
                <!-- textarea value MUST be in one line, hence the placeholder does not work -->
                <textarea class="form-control" id="description" name="description" placeholder="My Product" required
                          rows="3"><?php if (isset($product) && $product instanceof Product) {
                        echo $product->getDescription();
                    } ?></textarea>
                <div class="invalid-tooltip opacity-75">Please add a product description!</div>
            </div>

            <!-- price -->
            <div class="form-group position-relative mb-1">
                <label for="price">Price</label>
                <div class="input-group p-0">
                    <input type="number" id="price" name="price" step='0.01' class="form-control"
                           value="<?php if (isset($product) && $product instanceof Product) {
                               echo $product->getPrice();
                           } ?>"
                           required pattern="^([1-9][0-9]*|0)(\.[0-9]{2})?$" placeholder="10.00" min="0">
                    <span class="input-group-text rounded-end"><?= CURRENCY_SYMBOL ?></span>
                    <div class="invalid-tooltip opacity-75">Please choose a correct price!</div>
                </div>
            </div>

            <!-- shipping cost -->
            <div class="form-group position-relative mb-1">
                <label for="shipping">Shipping Cost</label>
                <div class="input-group p-0">
                    <input type="number" id="shipping" name="shipping" placeholder="3.50" min="0" step='0.01'
                           class="form-control" required pattern="^([1-9][0-9]*|0)(\.[0-9]{2})?$"
                           value="<?php if (isset($product) && $product instanceof Product) {
                               echo $product->getShippingCost();
                           } ?>">
                    <span class="input-group-text rounded-end"><?= CURRENCY_SYMBOL ?></span>
                    <div class="invalid-tooltip opacity-75">Please choose a correct shipping price!</div>
                </div>
            </div>

            <!-- stock amount -->
            <div class="form-group position-relative mb-1">
                <label for="stock">Stock</label>
                <div class="input-group p-0">
                    <input type="number" id="stock" name="stock" class="form-control" placeholder="42" min="0" required
                           pattern="[1-9][0-9]*|0"
                           value="<?php if (isset($product) && $product instanceof Product) {
                               echo $product->getStock();
                           } ?>">
                    <span class="input-group-text rounded-end">Pcs.</span>
                    <div class="invalid-tooltip opacity-75">Please choose a correct stock amount!</div>
                </div>
            </div>

            <div class="form-group position-relative mb-1">
                <label for="">Activate Product</label>
                <div class="input-group p-0">
                    <input class="form-check-input" type="checkbox" id="active" name="active" <?php
                    if (isset($product) && $product->isActive())
                        echo "checked";
                    else if (!isset($product))
                        echo "checked" ?>
                    >
                    <label class="form-check-label" for="active">
                        Active?
                    </label>
                </div>
            </div>

            <!-- image upload drop zone -->
            <div class="form-group">
                <label for="pictures" class="form-label fs-5 mt-2">Product Images</label>
                <!-- image drop zone -->
                <!-- TODO deprecated name event-->
                <div id="dropZone" class="drop-zone rounded border-secondary p-3"
                     ondrop="dropHandler(event, <?= MAX_IMAGE_PER_PRODUCT ?>)" ondragover="dragOverHandler(event)">
                    <!-- prepare to show uploaded images in edit mode -->
                    <?php
                    if (isset($product) && $product instanceof Product) {
                        $mainImg = $product->getMainImg();
                        $allIMGsArray = $product->getAllImgsOrNull();
                        if ($allIMGsArray != null) {
                            $allIMGs = array_slice($allIMGsArray, 0, MAX_IMAGE_PER_PRODUCT);
                        }
                    }
                    ?>

                    <!-- show instructions -->
                    <div class="drop-texts" id="dropTexts"
                        <?php if (isset($allIMGs) && sizeof($allIMGs) > 0) {
                            echo "style='display: none'";
                        } ?>>
                        <!-- Hide the text, if we add images to the dropZone (edit mode) -->
                        <span class="drop-text text-muted" style="pointer-events: none;">Click here or drag and drop images</span>
                        <!-- pointer-events: none makes the text 'click transparent' -->
                    </div>

                    <!-- embed the file explorer -->
                    <input class="file-input" type="file" id="files" name="files[]" multiple
                           onchange="filesChanged(this, <?= MAX_IMAGE_PER_PRODUCT ?>)">

                    <!-- images container -->
                    <section id="imgContainer">
                        <div id="imgRow" class="row justify-content-center">
                            <!-- show all uploaded images in edit mode -->
                            <?php
                            if (isset($allIMGs)) {
                                // Set the variable isNewImg to false, which is used by the template to define, that this image is already uploaded.
                                $isNewImg = false;
                                // For each image path
                                foreach ($allIMGs as $img) {
                                    $imgPaths = explode(DS, $img);
                                    $imgID = end($imgPaths);
                                    if (isset($mainImg) && $img == $mainImg) {
                                        $isMainImg = true;
                                    }

                                    require INCLUDE_ADMIN_DIR . "admin_product_img.inc.php";
                                    $isMainImg = null;
                                }
                                $isNewImg = null;   // Reset the variable, which is used by the template.
                            }
                            ?>
                        </div>
                    </section>
                </div>

                <!-- contains the id of the main image -->
                <input name="mainImgID" id="mainImgID" type="hidden">
                <!-- contains all deleted image indexes (only relevant in edit mode) -->
                <input name="deletedImgIDs[]" id="deletedImgIDs" type="hidden">
            </div>
        </div>

        <!-- FOOTER -->
        <div class="card-footer">
            <a href="javascript:history.back()" class="btn btn-secondary">Cancel</a>
            <button class="btn btn-success">Save</button>
        </div>
    </div>
</form>

<!-- load custom form validation script -->
<script src="<?= SCRIPT_DIR . "form_validation.js" ?>"></script>
<!-- enable tooltips on this page (by default disabled for performance) -->
<script src="<?= SCRIPT_DIR . "tooltip_enable.js" ?>"></script>
<!-- enable admin page tools -->
<script src="<?= SCRIPT_DIR . "admin_add_edit_product_pages.js" ?>"></script>