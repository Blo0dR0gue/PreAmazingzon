<?php if (isset($order) && $order instanceof Order): ?>
    <div class="mb-4 border text-start rounded shadow">
        <!-- Head -->
        <div class="d-flex flex-wrap border-bottom mb-0">
            <h4 class="pb-2 mb-0 mt-1 ps-2">Order #<?= $order->getId() ?></h4>
        </div>

        <div class="d-flex flex-wrap mb-0 mt-2 h6">
            <p class="mb-0 col-2 ps-2">
                <span class="text-muted">Status: </span><?= OrderStateController::getById($order->getOrderStateId())->getLabel() ?>
            </p>
            <p class="mb-0 col-2" >
                <span class="text-muted">Paid: </span><span id="paidTxt<?=$order->getId();?>"><?= $order->isPaid() ? "Paid" : "Not Paid" ?></span>
            </p>
            <p class="mb-0 col-4">
                <span class="text-muted">Order Time: </span><?= $order->getFormattedOrderDate(); ?>
            </p>
            <p class="mb-0 col-4">
                <span class="text-muted">Delivery Date: </span><?= $order->getFormattedDeliveryDate(); ?>
            </p>
        </div>

        <hr class="my-2">

        <!-- Products -->
        <?php
        // Total price for all products
        $sum = 0;
        // Total count of all products
        $count = 0;
        // Get all products which got ordered
        $productOrders = ProductOrderController::getAllByOrder($order->getId());

        foreach ($productOrders as $orderItem) {
            // Get the product object for the current order product
            $product = ProductController::getByID($orderItem->getProductId());
            // Add the full price to the total sum
            $sum += $orderItem->getFullPrice();
            //Add the amount to the total amount
            $count += $orderItem->getAmount();
            ?>
            <div class="d-flex flex-wrap mb-2 align-items-center">
                <!-- Image -->
                <div class="col-2 d-flex justify-content-center align-items-center" style="height: 90px">
                    <img src="<?= isset($product) ? $product->getMainImg() : IMAGE_PRODUCT_DIR . "notfound.jpg"; ?>"
                         alt="Product Image" style="max-width: 100px; max-height: 100%">
                </div>

                <!-- Title -->
                <div class="col-6 pe-3">
                    <a href="<?= isset($product) ? PAGES_DIR . "page_product_detail.php?id=" . $product->getId() : "#"; ?>"
                       class="mb-0 h6 text-decoration-none text-black">
                        <?= isset($product) ? $product->getTitle() : "Product not found" ?>
                    </a>
                </div>

                <!-- Amount -->
                <div class="col-2">
                    <?= $orderItem->getAmount() ?> pcs.
                </div>

                <!-- Price -->
                <div class="col-2">
                    <?= $orderItem->getFormattedFullPrice(); ?>
                </div>
            </div>
        <?php } ?>

        <hr class="my-2">

        <!-- Total Stats -->
        <div class="d-flex flex-wrap mb-2 align-items-center">
            <!-- Buttons -->
            <div class="col-7 d-flex flex-wrap justify-content-around">
                <!-- TODO make it save so a user cant download a invoice of a other user? -->
                <a class="btn btn-light border col-5" download=""
                   href="<?= INVOICES_DIR . $_SESSION["uid"] . DS . "invoice_" . $_SESSION["uid"] . "_" . $order->getId() . ".pdf" ?>">
                    Invoice
                </a>

                    <?php if (!$order->isPaid()) { ?>
                        <button class="col-5 btn btn-warning"
                                onclick="onItemPayBtn(this, <?= $order->getId(); ?>, <?= $order->getUserId(); ?>)">Pay
                            <!--TODO--></button>
                    <?php } else { ?>
                        <button class="col-5 btn btn-success" disabled>Paid</button>
                    <?php } ?>
                </div>

            <div class="col-1 text-end pe-2">
                <h6><strong>Total:</strong></h6>
            </div>
            <div class="col-2">
                <h6><?= $count ?> pcs.</h6>
            </div>
            <div class="col-2">
                <h6><?= number_format($sum, 2, ",", "") . CURRENCY_SYMBOL; ?> </h6>
            </div>
        </div>
    </div>
<?php endif; ?>