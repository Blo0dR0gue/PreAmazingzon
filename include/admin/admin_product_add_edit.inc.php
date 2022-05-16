
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
                                <!-- TODO do link -->
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

<!-- load custom form validation script -->
<script src="<?= SCRIPT_DIR . DS . "form_validation.js" ?>"></script>
<!-- enable tooltips on this page (by default disabled for performance)-->
<script src="<?= SCRIPT_DIR . DS . "tooltip_enable.js" ?>"></script>
<!-- enable admin page tools-->
<script src="<?= SCRIPT_DIR . DS . "admin_pages.js" ?>"></script>