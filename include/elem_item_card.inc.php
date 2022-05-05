<?php
if (isset($product) && $product instanceof Product):
?>
    <div class="card">
        <img src="<?= $product->getMainImg(); ?>" class="card-img-top" alt="product">
        <div class="card-body">
            <?= $product->getTitle(); ?>
            <hr>
            <strong><?= $product->getPriceFormatted(); ?> €</strong>
        </div>
        <div class="card-footer">
            <a href="#" class="btn btn-primary btn-sm">Details</a>
            <a href="#" class="btn btn-success btn-sm">Add to cart</a>
        </div>
    </div>
<?php
endif;
?>