<?php

require_once CONTROLLER_DIR . DS . "controller_category.php";

if (isset($category) && $category instanceof Category) { ?>
    <div class="col-xl-3 col-lg-4 col-md-6 mb-3 d-flex align-items-stretch">
        <!-- CATEGORY -->
        <div class="card border-0 shadow w-100">
            <!-- image -->
            <a href="#"
               class="d-flex justify-content-center align-items-center overflow-hidden" style="height: 200px">
                <!-- TODO add link -->
                <img src="<?= $category->getImg(); ?>" class="card-img-top mh-100 mw-100 w-auto" alt="main image"/>
            </a>

            <div class="card-body border-top py-3 px-3">
                <!-- first row -->
                <div class="d-flex justify-content-between">
                    <!-- title -->
                    <a href="#"
                       class="mb-0 h5 text-decoration-none text-black"><?= $category->getName() ?></a>
                    <!-- TODO add link -->
                </div>
            </div>
        </div>
    </div>
<?php } ?>