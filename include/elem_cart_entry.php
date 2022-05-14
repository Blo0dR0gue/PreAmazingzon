<!-- TODO comment -->
<!-- TODO MAKE IT WORK-->

<?php
require_once "site_php_head.inc.php";

if (isset($cartProduct) && $cartProduct instanceof CartProduct){
    $product = ProductController::getByID($cartProduct->getProdId());
?>
    <tr>
        <td data-th="Product">
            <div class="row">
                <div class="col-md-3 d-flex justify-content-center">
                    <img src="<?= $product->getMainImg() ?>" alt="Image" class="img-fluid d-none d-md-block rounded shadow align-self-center">
                </div>
                <div class="col-md-9 text-left mt-sm-2">
                    <h4><?= $product->getTitle() ?></h4>
                    <p class="font-weight-light mb-2 overflow-hidden small">
                        <?= substr($product->getDescription(), 0, 250) . " ..." ?>
                    </p>
                </div>
            </div>
        </td>
        <td data-th="Price"><?= $product->getPriceFormatted() ?></td>
        <td data-th="Quantity">
            <div class="d-flex justify-content-center">
                <a href="<?= INCLUDE_HELPER_DIR . DIRECTORY_SEPARATOR . "helper_cart_amount.inc.php?" . http_build_query(["step" => "dec", "productId" => $product->getId()]) ?>"
                   class="text-decoration-none mx-2">
                    <i class="fa fa-minus link-warning"></i>
                </a>
                <p class="border px-2 rounded text-muted"><?= $cartProduct->getAmount() ?></p>
                <a href="<?= INCLUDE_HELPER_DIR . DIRECTORY_SEPARATOR . "helper_cart_amount.inc.php?" . http_build_query(["step" => "inc", "productId" => $product->getId()]) ?>"
                   class="text-decoration-none mx-2">
                    <i class="fa fa-plus link-warning"></i>
                </a>
            </div>
        </td>
        <td data-th="Total"><?= $product->getPriceFormatted($cartProduct->getAmount()) ?></td>
        <td data-th="" class="actions">
            <button class="btn btn-close btn-md mb-2"></button>
        </td>
    </tr>
<?php } ?>