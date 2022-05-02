<?php
if (isset($product)):
?>
    <div class="card">
        <div class="card-title">
            <?= $product['title'] ?>
        </div>
        <img src="<?= $product['mainImg'] ?>" class="card-img-top" alt="product">
        <div class="card-body">
            <?= $product['description'] ?>
            <hr>
            <strong><?= $product['price'] ?> €</strong>
        </div>
        <div class="card-footer">
            <a href="#" class="btn btn-primary btn-sm">Details</a>
            <a href="#" class="btn btn-success btn-sm">In den Warenkorb</a>
        </div>
    </div>
<?php
endif;
?>