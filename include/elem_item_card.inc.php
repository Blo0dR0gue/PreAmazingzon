<?php
if (isset($product) && $product instanceof Product):
?>
    <div class="card">
        <div class="card-title">
            <?= $product->getTitle(); ?>
        </div>
        <img src="<?= $product->getMainImg(); ?>" class="card-img-top" alt="product">
        <div class="card-body">
            <?= $product->getDescription(); ?>
            <hr>
            <strong><?= $product->getPrice(); ?> €</strong>
        </div>
        <div class="card-footer">
            <a href="#" class="btn btn-primary btn-sm">Details</a>
            <a href="#" class="btn btn-success btn-sm">In den Warenkorb</a>
        </div>
    </div>
<?php
endif;
?>