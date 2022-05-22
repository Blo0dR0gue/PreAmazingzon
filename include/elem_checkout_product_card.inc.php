<?php

if (isset($cartProduct) && $cartProduct instanceof CartProduct):
    $product = ProductController::getByID($cartProduct->getProdId());
?>

    <div class="row p-2 bg-white rounded">
        <div class="col-md-2 mt-1"><img class="img-fluid img-responsive rounded product-image" src="<?= $product->getMainImg(); ?>"></div>
        <div class="col-md-6 mt-1">
            <h5><?= $product->getTitle(); ?></h5>
            <div class="mt-1 mb-1">Price: <?= $product->getPriceFormatted($cartProduct->getAmount()) . " + " . $product->getShippingCostFormatted(); ?></div>
            <div class="mt-1 mb-1">Amount: <?= $cartProduct->getAmount() ?></div>
        </div>
    </div>

<?php
endif;
?>