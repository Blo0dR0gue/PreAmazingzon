<?php

require_once CONTROLLER_DIR . DIRECTORY_SEPARATOR . "controller_review.php";

if (isset($product) && $product instanceof Product): ?>
    <div class="col-xl-3 col-lg-4 col-md-6 mb-3 d-flex align-items-stretch">
        <!-- PRODUCT -->
        <div class="card border-0 shadow">
            <!-- main image -->
            <a href="<?= PAGES_DIR . DIRECTORY_SEPARATOR . "page_product_detail.php?id=" . $product->getId(); ?>"
               class="d-flex justify-content-center align-items-center overflow-hidden" style="height: 250px">
                <img src="<?= $product->getMainImg(); ?>" class="card-img-top" alt="main image"/>
            </a>

            <div class="card-body border-top pb-1 px-3">
                <!-- first row-->
                <div class="d-flex justify-content-between">
                    <!-- category -->
                    <p class="small mb-2"><a href="#" class="text-muted">Laptops</a></p>
                    <!-- TODO insert category -->
                    <!-- 'discount' -->
                    <p class="small text-danger mb-2"><s><?= $product->getOriginalPriceFormatted() ?>€</s></p>
                </div>

                <!-- second row-->
                <div class="d-flex justify-content-between mb-3">
                    <!-- title-->
                    <a href="<?= PAGES_DIR . DIRECTORY_SEPARATOR . "page_product_detail.php?id=" . $product->getId(); ?>"
                       class="mb-0 h5 text-decoration-none text-black"><?= $product->getTitle() ?></a>
                    <!-- price-->
                    <h5 class="text-dark mb-0 ms-2"><?= $product->getPriceFormatted() ?>€</h5>
                </div>

                <!-- third row-->
                <div class="d-flex justify-content-between align-content-end mb-2">
                    <!-- stock -->
                    <p class="text-muted mb-0"><span class="fw-bold"><?= $product->getStock() ?></span> in Stock</p>
                    <!-- rating -->
                    <div>
                        <?php
                        echo ReviewController::getAvgRating($product->getId());
                        ReviewController::calcAndIncAvgProductStars($product->getId());
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php endif ?>