<?php
if (isset($product) && $product instanceof Product):
    ?>
    <div class="card">
        <img src="<?= $product->getMainImg(); ?>" class="card-img-top" alt="product">
        <div class="card-body">

            <a href="<?= PAGES_DIR . DIRECTORY_SEPARATOR . 'page_product_detail.php?id=' . $product->getId(); ?>"
               data-abc="true" style="text-decoration: none"><?= $product->getTitle(); ?></a>
            <hr>
            <strong><?= $product->getPriceFormatted(); ?> €</strong>
            <hr>
            <div class="ratings">
                <i class="fa fa-star rating-color"></i>
                <i class="fa fa-star rating-color"></i>
                <i class="fa fa-star rating-color"></i>
                <i class="fa fa-star rating-color"></i>
                <i class="fa fa-star"></i>
            </div>
        </div>
        <div class="card-footer">
            <a href="#" class="btn btn-success btn-sm">Add to cart</a>
        </div>
    </div>
<?php
endif;
?>