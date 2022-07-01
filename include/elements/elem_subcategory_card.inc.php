<?php
if (isset($subCategory) && $subCategory instanceof Category) { ?>
    <div class="col-xl-3 col-lg-4 col-md-6 mb-3 d-flex align-items-stretch">
        <!-- subcategory -->
        <div class="card border-0 shadow w-100">
            <a href="<?= PAGES_DIR . 'page_categories.php?id=' . $subCategory->getId() ?>"
               class="card-body py-3 px-3 d-flex justify-content-between text-decoration-none">
                <!-- title -->
                <p class="mb-0 h5 text-decoration-none text-black "><?= $subCategory->getName() ?></p>
            </a>
        </div>
    </div>
<?php } ?>