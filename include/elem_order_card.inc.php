<?php if (isset($order) && $order instanceof Order): ?>
    <div class="callout mb-5 border">

        <!-- Head -->
        <h5 class="mb-2">Order
            number <?= $order->getId() . " (Status: " . OrderStateController::getById($order->getOrderStateId())->getLabel() . ")" ?></h5>
        <p class="mb-0">Paid: <?= $order->isPaid() ? "Paid" : "Not Paid" ?></p>
        <p class="mb-0">Order Date: <?= $order->getFormattedOrderDate(); ?></p>
        <p class="mb-0">Delivery Date: <?= $order->getFormattedDeliveryDate(); ?></p>
        <hr>
        <?php

        //Total price for all products
        $sum = 0;
        //Total count of all products
        $count = 0;
        //Get all products which got ordered
        $productOrders = ProductOrderController::getAllByOrder($order->getId());

        foreach ($productOrders as $orderItem) {
            //Get the product object for the current order product
            $product = ProductController::getByID($orderItem->getProductId());
            //Add the full price to the total sum
            $sum += $orderItem->getFullPrice();
            //Add the amount to the total amount
            $count += $orderItem->getAmount();
            ?>
            <div class="row mb-3">
                <div class="col-6 col-md-2">
                    <!--Image-->
                    <a href="<?= isset($product) ? PAGES_DIR . DS . "page_product_detail.php?id=" . $product->getId() : "#";
                    // TODO GLOBAL dont use manual queries, http_build_query instead     ?>"
                       class="d-flex justify-content-center align-items-center">
                        <img src="<?= isset($product) ? $product->getMainImg() : IMAGE_PRODUCT_DIR . DS . "notfound.jpg"; ?>"
                             width="90"
                             height="90" alt="Image of product">
                    </a>

                </div>
                <!--Title-->
                <div class="col-6 col-md-4 d-flex align-items-center justify-content-center">
                    <a href="<?= isset($product) ? PAGES_DIR . DS . "page_product_detail.php?id=" . $product->getId() : "#"; ?>"
                       class="mb-0 h5 text-decoration-none text-black web"
                       style="display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical;">
                        <?= isset($product) ? $product->getTitle() : "Product not found" ?>
                    </a>
                </div>

                <!--Space-->
                <div class="d-sm-none d-md-block col-md-2">

                </div>

                <!--Amount-->
                <div class="col-6 col-md-2 d-flex align-items-center">
                    <?= $orderItem->getAmount() ?> pcs.
                </div>

                <!--Price-->
                <div class="col-6 col-md-2 d-flex align-items-center">
                    <?= $orderItem->getFormattedFullPrice(); ?>
                </div>
            </div>
        <?php } ?>

        <hr>

        <!-- Total Stats -->
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
            <div class="d-flex align-items-center">
                <div class="col-6">
                    <!--TODO make it save so a user cant download a invoice of a other user?-->
                    <a class="btn btn-sm fs-6" download=""
                       href="<?= INVOICES_DIR . DS . $_SESSION["uid"] . DS . "invoice_" . $_SESSION["uid"] . "_" . $order->getId() . ".pdf" ?>">
                        Download invoice
                    </a>
                </div>

                <?php if (!$order->isPaid()) { ?>
                    <button class="btn btn-warning col-4 btn-sm" onclick="#">
                        Pay <!--TODO-->
                    </button>
                <?php } else { ?>
                    <div class="col-4 alert alert-success text-center mb-0">
                        Paid
                    </div>
                <?php } ?>
            </div>
        </div>
        <br>
    </div>

<?php endif; ?>