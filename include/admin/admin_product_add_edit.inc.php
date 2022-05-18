<!--TODO COMMENTS-->
<form action="" id="prodForm" method="POST" enctype="multipart/form-data" class="needs-validation" novalidate>
    <div class="card">
        <div class="card-header">
            <!-- title -->
            <h2 class="mb-2 mt-4">Add a new product</h2>
        </div>
        <div class="card-body">

            <div class="form-group position-relative">
                <label for="title">Product Title</label>
                <input type="text" value="<?php
                if (isset($product) && $product instanceof Product) {
                    echo $product->getTitle();
                }
                ?>" name="title" id="title" class="form-control"
                       placeholder="A New Product Title"
                       required pattern="[a-zäöüA-ZÄÖÜ0-9 ,.'-]+">
                <div class="invalid-tooltip opacity-75">Please enter a valid Product name!</div>
            </div>


            <div class="form-group position-relative">

                <label for="selectedRadio">Category</label>
                <select class="form-select" aria-label="Default select example" name="cat" required>
                    <!--TODO Rework -> tree like?; Replace button next with selected-->
                    <option value="">Open this select menu</option>
                    <?php foreach (CategoryController::getAll() as $tmpCategory): ?>
                        <option value="<?= $tmpCategory->getId(); ?>" <?php
                        if (isset($cat)) {
                            if (in_array($tmpCategory->getId(), $cat))
                                echo "selected";
                        } else if (isset($category) && $category instanceof Category)
                            if ($tmpCategory->getId() == $category->getId())
                                echo "selected";
                        ?>> <?= $tmpCategory->getName(); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <div class="invalid-tooltip opacity-75">
                    Please select a Category!
                </div>

            </div>

            <div class="form-group position-relative">
                <label for="description">Product Description</label>
                <textarea class="form-control" id="description" name="description" rows="3"
                          placeholder="My New Cool Product" required><?php
                    if (isset($product) && $product instanceof Product) {
                        echo $product->getDescription();
                    }
                    ?></textarea>
                <div class="invalid-tooltip opacity-75">Please add a Product text!</div>
            </div>

            <div class="form-group position-relative">
                <label for="price">Price</label>
                <div class="input-group p-0">
                    <input type="number" id="price" name="price" value="<?php
                    if (isset($product) && $product instanceof Product) {
                        echo $product->getPrice();
                    }
                    ?>" step='0.01' class="form-control"
                           required pattern="^([1-9][0-9]*|0)(\.[0-9]{2})?$" placeholder="10.00">
                    <span class="input-group-text"><?= CURRENCY_SYMBOL ?></span>
                    <div class="invalid-tooltip opacity-75">Please choose a correct price!</div>
                </div>


                <div class="form-group position-relative">
                    <label for="shipping">Shipping Cost</label>
                    <div class="input-group p-0">
                        <input type="number" id="shipping" name="shipping" placeholder="3.50" step='0.01'
                               class="form-control" required pattern="^([1-9][0-9]*|0)(\.[0-9]{2})?$" value="<?php
                        if (isset($product) && $product instanceof Product) {
                            echo $product->getShippingCost();
                        }
                        ?>">
                        <span class="input-group-text"><?= CURRENCY_SYMBOL ?></span>
                        <div class="invalid-tooltip opacity-75">Please choose a correct price!</div>
                    </div>
                </div>

                <div class="form-group position-relative">
                    <label for="stock">Stock</label>
                    <div class="input-group p-0">
                        <div class="input-group p-0">
                            <input type="number" id="stock" name="stock" class="form-control" placeholder="42" required
                                   pattern="[1-9][0-9]*|0" value="<?php
                            if (isset($product) && $product instanceof Product) {
                                echo $product->getStock();
                            }
                            ?>">
                            <span class="input-group-text">Pcs.</span>
                            <div class="invalid-tooltip opacity-75">Please choose a correct stock amount!</div>
                        </div>
                    </div>

                </div>

                <div class="form-group">

                    <label for="pictures" class="form-label fs-4">Product Images</label>
                    <div id="dropZone" class="drop-zone" ondrop="dropHandler(event, <?= MAX_IMAGE_PER_PRODUCT ?>)"
                         ondragover="dragOverHandler(event)">
                        <?php
                        if (isset($product) && $product instanceof Product) {
                            $mainImg = $product->getMainImg();
                            $allIMGsArray = $product->getAllImgsOrNull();
                            if ($allIMGsArray != null)
                                $allIMGs = array_slice($allIMGsArray, 0, MAX_IMAGE_PER_PRODUCT);
                        }
                        ?>
                        <div class="drop-texts" id="dropTexts"

                            <?php
                            //Hide the text, if we add images to the dropZone
                            if (isset($allIMGs) && sizeof($allIMGs) > 0): ?>
                                style="display: none"
                            <?php endif; ?> >
                            <span class="drop-text">Click here or drag and drop file</span>
                        </div>
                        <input class="file-input" type="file" id="files" name="files[]" multiple
                               onchange="filesChanged(this, <?= MAX_IMAGE_PER_PRODUCT ?>)">

                        <section class="container py-3" id="imgContainer">
                            <div id="imgRow" class="row jcenter">

                                <?php
                                if (isset($allIMGs)) {
                                    //Set the variable isNewImg to false, which is used by the template to define, if a tag is set.
                                    $isNewImg = false;
                                    foreach ($allIMGs as $img) {
                                        $imgPaths = explode(DS, $img);
                                        $imgID = end($imgPaths);
                                        if (isset($mainImg) && $img == $mainImg) {
                                            $isMainImg = true;
                                        }
                                        require INCLUDE_DIR . DS . "admin" . DS . "admin_product_img.inc.php";
                                        $isMainImg = null;
                                    }
                                    //Reset the variable, which is used by the template.
                                    $isNewImg = null;
                                }
                                ?>

                            </div>
                        </section>
                    </div>

                    <input name="mainImgID" id="mainImgID" type="hidden">
                    <!--Only relevant in edit mode. contains all deleted image indexes  -->
                    <input name="deletedImgIDs[]" id="deletedImgIDs" type="hidden">
                </div>

                <br>

                <div class="card-footer">
                    <a href="javascript:history.back()" class="btn btn-danger">Abort</a>
                    <button class="btn btn-success">Save</button>
                </div>
            </div>

            <div>
</form>

<!-- load custom form validation script -->
<script src="<?= SCRIPT_DIR . DS . "form_validation.js" ?>"></script>
<!-- enable tooltips on this page (by default disabled for performance)-->
<script src="<?= SCRIPT_DIR . DS . "tooltip_enable.js" ?>"></script>
<!-- enable admin page tools-->
<script src="<?= SCRIPT_DIR . DS . "admin_pages.js" ?>"></script>