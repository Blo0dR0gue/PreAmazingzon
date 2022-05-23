<?php if (isset($order) && $order instanceof Order): ?>
    <div class="callout mb-5 border">
        <h5 class="mb-1">Order
            number <?= $order->getId() . " (" . OrderStateController::getById($order->getOrderStateId())->getLabel() . ")" ?></h5>
        <p class="mb-2">Order Date: <?= $order->getFormattedOrderDate(); ?></p>
        <p class="mb-2">Delivery Date: <?= $order->getFormattedDeliveryDate(); ?></p>
        <hr>
        <?php

        $sum = 0;
        $count = 0;
        $productOrders = ProductOrderController::getAllByOrder($order->getId());

        foreach ($productOrders as $orderItem) {
            $product = ProductController::getByID($orderItem->getProductId());
            ?>
            <div class="row mb-3">
                <div class="col-6 col-md-2">

                    <a href="<?= isset($product) ? PAGES_DIR . DS . "page_product_detail.php?id=" . $product->getId() : "#";
                    // TODO GLOBAL dont use manual queries, http_build_query instead  ?>"
                       class="d-flex justify-content-center align-items-center overflow-hidden">
                        <img src="<?= isset($product) ? $product->getMainImg() : IMAGE_PRODUCT_DIR . DS . "notfound.jpg"; ?>"
                             width="90"
                             height="90" alt="Image of product">
                    </a>

                </div>
                <div class="col-6 col-md-4 d-flex align-items-center justify-content-center">
                    <a href="<?= isset($product) ? PAGES_DIR . DS . "page_product_detail.php?id=" . $product->getId() : "#"; ?>"
                       class="mb-0 h5 text-decoration-none text-black web"
                       style="display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden;">
                        <?= isset($product) ? $product->getTitle() : "Product not found" ?>
                    </a>
                </div>
                <div class="d-sm-none d-md-block col-md-2">

                </div>
                <div class="col-6 col-md-2 d-flex align-items-center">
                    <?php
                    $count += $orderItem->getAmount();
                    echo $orderItem->getAmount() ?> pcs.
                </div>
                <div class="col-6 col-md-2 d-flex align-items-center">
                    <?php
                    $sum += $orderItem->getAmount() * $orderItem->getPrice();
                    echo number_format($orderItem->getAmount() * $orderItem->getPrice(), 2, ",", ""); ?> €
                </div>
            </div>
        <?php } ?>
        <hr>
        <!-- Total: -->
        <div class="row mb-3">
            <div class="d-sm-none d-md-block col-md-6">

            </div>
            <div class="col-4 col-md-2 d-flex align-items-center">
                <h6>Total:</h6>
            </div>
            <div class="col-4 col-md-2 d-flex align-items-center">
                <h6><?= $count ?> pcs.</h6>
            </div>
            <div class="col-4 col-md-2 d-flex align-items-center">
                <h6><?= number_format($sum, 2, ",", "") . CURRENCY_SYMBOL; ?> </h6>
            </div>
        </div>
        <!-- Invoice -->
        <div class="row">
            <div class="d-sm-none col-md-6">

            </div>
            <div class="d-flex align-items-center justify-content-between">
                <div class="col-6">
                    <a class="fs-6"
                       href="#">Download invoice</a>    <!--TODO-->
                </div>

                <?php if (!$order->isPaid()) { ?>
                    <button class="btn btn-primary col-6" onclick="#">
                        Pay <!--TODO-->
                    </button>
                <?php } else { ?>
                    <div class="col-6 alert alert-success text-center mb-0">
                        Paid
                    </div>
                <?php } ?>
            </div>
        </div>
        <br>
    </div>

<?php endif; ?>