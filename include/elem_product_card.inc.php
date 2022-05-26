<?php

if (isset($product) && $product instanceof Product): ?>
    <div class="col-xl-3 col-lg-4 col-md-6 mb-3 d-flex align-items-stretch">
        <!-- PRODUCT -->
        <div class="card border-0 shadow w-100">
            <!-- main image -->
            <a href="<?= PAGES_DIR . DS . "page_product_detail.php?id=" . $product->getId();
            // TODO GLOBAL dont use manual queries, http_build_query instead    ?>"
               class="d-flex justify-content-center align-items-center overflow-hidden" style="height: 250px">
                <img src="<?= $product->getMainImg(); ?>" class="card-img-top mh-100 mw-100 w-auto" alt="main image"/>
            </a>

            <div class="card-body border-top pb-1 px-3">
                <!-- first row -->
                <div class="d-flex justify-content-between mb-1">
                    <!-- title -->
                    <a href="<?= PAGES_DIR . DS . "page_product_detail.php?id=" . $product->getId(); ?>"
                       class="mb-0 h5 text-decoration-none text-black web"
                       style="display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden;">
                        <?= $product->getTitle() ?>
                    </a>
                </div>

                <!-- second row -->
                <div class="d-flex mt-auto">
                    <!-- category -->
                    <p class="small mb-2 me-auto">
                        <?php
                        $cat = CategoryController::getNameById($product->getCategoryID());

                        if ($cat !== "No Category") {
                            echo "<a href='#' class='text-muted'>{$cat}</a>";   // TODO insert cat link
                        } else {
                            echo "<a class='text-decoration-none'><i class='text-muted'>{$cat}</i></a>";
                        }
                        ?>
                    </p>
                    <!-- 'discount' -->
                    <p class="small text-danger mb-2"><s><?= $product->getOriginalPriceFormatted() ?></s></p>
                    <!-- price -->
                    <h5 class="text-dark mb-0 ms-1"><?= $product->getPriceFormatted() ?></h5>
                </div>

                <!-- third row -->
                <div class="d-flex justify-content-between align-content-end mb-2">
                    <!-- stock -->
                    <p class="text-muted mb-0"><span class="fw-bold"><?= $product->getStock() ?></span> in Stock</p>
                    <!-- rating -->
                    <div>
                        <?php
                        echo ReviewController::getAvgRating($product->getId());
                        ReviewController::calcAndIncAvgProductStars($product->getId());     // TODO change to echo too for better overview
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php endif ?>