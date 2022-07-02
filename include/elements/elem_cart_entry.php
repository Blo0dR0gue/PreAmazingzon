<?php
//Is the cart product set and an CartProduct object
if (isset($cartProduct) && $cartProduct instanceof CartProduct) {
    $product = ProductController::getByID($cartProduct->getProdId()); ?>
    <!-- shopping cart row -->
    <tr>
        <!--region product details-->
        <td data-th="Product">
            <div class="row">
                <!-- main product image -->
                <a href="<?= PAGES_DIR . "page_product_detail.php?" . http_build_query(["id" => $product->getId()]) ?>"
                   class="col-md-3 d-flex justify-content-center">
                    <img src="<?= $product->getMainImg() ?>" alt="Image"
                         class="img-fluid d-none d-md-block rounded align-self-center" style="max-height: 180px">
                </a>
                <!-- product information -->
                <div class="col-md-9 text-left mt-sm-2">
                    <a href="<?= PAGES_DIR . "page_product_detail.php?" . http_build_query(["id" => $product->getId()]) ?>"
                       class="text-decoration-none text-black">
                        <h4><?= $product->getTitle() ?></h4>
                    </a>

                    <p class="font-weight-light mb-2 overflow-hidden small">
                        <?= substr($product->getDescription(), 0, 250) . " ..." ?>
                    </p>
                </div>
            </div>
        </td>
        <!--endregion-->

        <!--region product price-->
        <td data-th="Price"><?= $product->getPriceTotalFormatted() ?></td>
        <!--endregion-->

        <!--region product amount-->
        <td data-th="Quantity">
            <div class="d-flex justify-content-center">
                <a href="<?= INCLUDE_HELPER_DIR . "helper_shopping_cart.inc.php?" . http_build_query(["action" => "dec", "productId" => $product->getId()]) ?>"
                   class="text-decoration-none mx-2">
                    <em class="fa fa-minus link-warning"></em>
                </a>
                <p class="border px-2 rounded text-muted"><?= $cartProduct->getAmount() ?></p>
                <a href="<?= INCLUDE_HELPER_DIR . "helper_shopping_cart.inc.php?" . http_build_query(["action" => "inc", "productId" => $product->getId()]) ?>"
                   class="text-decoration-none mx-2">
                    <em class="fa fa-plus link-warning"></em>
                </a>
            </div>
        </td>
        <!--endregion-->

        <!--Cost for the amount of this product-->
        <td data-th="Subtotal"><?= $product->getPriceTotalFormatted($cartProduct->getAmount()) ?></td>

        <!--Action buttons-->
        <td data-th="" class="actions">
            <a href="<?= INCLUDE_HELPER_DIR . "helper_shopping_cart.inc.php?" . http_build_query(["action" => "del", "productId" => $product->getId()]) ?>"
               class="btn btn-close btn-md mb-2"></a>
        </td>

    </tr>
    <?php
    // save price for super script calculating total
    $subtotal = $product->getPriceTotal($cartProduct->getAmount());
} else {
    //If product not found
    $subtotal = 0;
} ?>