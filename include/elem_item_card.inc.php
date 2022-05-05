<?php

require_once CONTROLLER_DIR . DIRECTORY_SEPARATOR . "controller_review.php";

if (isset($product) && $product instanceof Product): ?>
    <div class="card">
        <img src="<?= $product->getMainImg(); ?>" class="card-img-top" alt="product">
        <div class="card-body">

            <a href="<?= PAGES_DIR . DIRECTORY_SEPARATOR . 'page_product_detail.php?id=' . $product->getId(); ?>"
               data-abc="true" style="text-decoration: none"><?= $product->getTitle(); ?></a>
            <hr>
            <strong><?= $product->getPriceFormatted(); ?> €</strong>
            <hr>

            <?php
            $avgRating = ReviewController::getAvgRating($product->getId());
            echo $avgRating . " Stars ";

            //Calculate and set the star rating using full and half stars.  //TODO move into review controller?
            for ($i = 1; $i <= 5; $i++)
            {
                $difference = $avgRating - $i;
                if ($difference >= 0)
                {
                    echo "<i class='fa fa-star rating-color'></i>";
                } elseif (0.25 < abs($difference) && abs($difference) < 0.75)
                {
                    echo "<i class='fa fa-star-half-full rating-color'></i>";
                } else {
                    echo "<i class='fa fa-star'></i>";
                }
            }
            ?>

        </div>
        <div class="card-footer">
            <a href="#" class="btn btn-success btn-sm">Add to cart</a>
        </div>
    </div>
<?php endif ?>