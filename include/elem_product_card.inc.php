<?php

require_once CONTROLLER_DIR . DIRECTORY_SEPARATOR . "controller_review.php";

if (isset($product) && $product instanceof Product): ?>
    <div class="col-xl-3 col-lg-4 col-md-6 mb-3 d-flex align-items-stretch">
        <div class="card">
            <a href="<?= PAGES_DIR . DIRECTORY_SEPARATOR . "page_product_detail.php?id=" . $product->getId(); ?>"
               class="border-bottom d-flex justify-content-center align-items-center"
               style="height: 250px; overflow: hidden">
                <img src="<?= $product->getMainImg(); ?>" class="card-img-top" alt="main image"/>
            </a>

            <div class="card-body pb-1 px-3">
                <div class="d-flex justify-content-between">
                    <p class="small mb-2"><a href="#" class="text-muted">Laptops</a></p>
                    <!-- TODO insert category -->
                    <p class="small text-danger mb-2"><s>$1099</s></p>
                    <!-- TODO insert "discount", random? -->
                </div>

                <div class="d-flex justify-content-between mb-3">
                    <a href="<?= PAGES_DIR . DIRECTORY_SEPARATOR . "page_product_detail.php?id=" . $product->getId(); ?>"
                       class="mb-0 h5 text-decoration-none text-black"><?= $product->getTitle() ?></a>
                    <h5 class="text-dark mb-0 ms-2"><?= $product->getPriceFormatted() ?>€</h5>
                </div>

                <div class="d-flex justify-content-between align-content-end mb-2">
                    <p class="text-muted mb-0"><span class="fw-bold">6</span> in Stock</p>
                    <!-- TODO insert stock -->
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