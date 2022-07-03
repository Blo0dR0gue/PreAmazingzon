<form action="" id="catForm" method="POST" enctype="multipart/form-data" class="needs-validation" novalidate>
    <div class="card my-4">
        <!-- HEADER -->
        <div class="card-header">
            <!-- title -->
            <h2 class="mb-2 mt-4">
                <?php
                if (str_contains($_SERVER["REQUEST_URI"], "add")) {
                    echo "Add Category";
                } elseif (str_contains($_SERVER["REQUEST_URI"], "edit")) {
                    echo "Edit Category";
                } else {
                    echo "Category";
                }
                ?>
            </h2>
        </div>

        <!-- BODY -->
        <div class="card-body">
            <!-- category title -->
            <div class="form-group position-relative mb-1">
                <label for="title">Category Title</label>
                <input type="text" name="title" id="title" class="form-control" placeholder="A Category Title"
                       value="<?php if (isset($category) && $category instanceof Category) {
                           echo $category->getName();
                       } ?>" required pattern="[a-zäöüA-ZÄÖÜ0-9 ,.'-:]{5,}">
                <div class="invalid-tooltip opacity-75">Please enter a valid category name!</div>
            </div>

            <!-- super category select -->
            <div class="form-group position-relative mb-1">
                <label for="cat">Super Category</label>
                <select class="form-select" name="cat" id="cat">
                    <!-- fill options -->
                    <option value="-1" hidden>Select Super Category - leave empty for root</option>

                    <?php foreach (CategoryController::getCategoryTree() as $treeEntry) {
                        if (!isset($category) || !str_contains($treeEntry["path"], $category->getName())) { ?>
                            <option value="<?= $treeEntry["top"]; ?>"
                                <?php
                                if (isset($cat)) {
                                    if (in_array($treeEntry["top"], $cat)) {
                                        echo "selected";
                                    }
                                } else if (isset($category) && $category instanceof Category) {
                                    if ($treeEntry["top"] == $category->getParentID()) {
                                        echo "selected";
                                    }
                                } ?>>
                                <?= $treeEntry["path"]; ?>
                            </option>
                        <?php }
                    } ?>
                </select>
                <div class="invalid-tooltip opacity-75">Please select a super category!</div>
            </div>

            <!-- category description (text) -->
            <div class="form-group position-relative mb-1">
                <label for="description">Category Description</label>
                <!-- textarea value MUST be in one line, hence the placeholder does not work -->
                <textarea class="form-control" id="description" name="description" placeholder="My Category" required
                          rows="3"><?php if (isset($category) && $category instanceof Category) {
                        echo $category->getDescription();
                    } ?></textarea>
                <div class="invalid-tooltip opacity-75">Please add a category description!</div>
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