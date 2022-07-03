<!-- Admin order management page -->

<?php require_once "../../include/site_php_head.inc.php" ?>

<?php

// Is the user allowed to be here?
UserController::redirectIfNotAdmin();

// pagination stuff
$page = (isset($_GET['page']) && is_numeric($_GET['page'])) ? $_GET['page'] : 1;  // Current pagination page number
$offset = ($page - 1) * LIMIT_OF_SHOWED_ITEMS;                                    // Calculate offset for pagination
$userCount = OrderController::getAmountOfUsers();                                 // Get the total amount of users
$totalPages = ceil($userCount / LIMIT_OF_SHOWED_ITEMS);                      // Calculate the total amount of pages

//Get the orders in range. (From an offset a specific amount)
$orders = OrderController::getAllInRange($offset, LIMIT_OF_SHOWED_ITEMS);

//Get all order states.
$orderStates = OrderStateController::getAll();
?>

<!DOCTYPE html>
<html class="h-100" lang="en">
<head>
    <?php require_once INCLUDE_DIR . "site_html_head.inc.php"; ?>
    <title><?= PAGE_NAME ?> - Admin - Orders</title>

    <?php require_once INCLUDE_MODAL_DIR . "modal_popup.inc.php"; ?>
    <script src="<?= SCRIPT_DIR . "admin_orders_page.js" ?>"></script>
</head>

<body class="d-flex flex-column h-100">
<!-- header -->
<?php require_once INCLUDE_DIR . "site_header.inc.php"; ?>

<!-- main body -->
<main class="flex-shrink-0 container">
    <!-- page header -->
    <div class="d-flex align-items-end">
        <h1 class="mt-4 ms-2 mb-0 mr-auto">All Orders</h1>
    </div>
    <hr class="mt-2">

    <!-- order table -->
    <table class="table" aria-label="Orders Table">
        <!-- table head -->
        <thead class="thead-light">
        <tr>
            <th scope="col" style="width: 4%"></th>
            <th scope="col" style="width: 5%">#</th>
            <th scope="col" style="width: 8%">User ID</th>
            <th scope="col" style="width: 25%;">Username</th>
            <th scope="col" style="width: 17%">Delivery Date</th>
            <th scope="col" style="width: 17%">Order Date</th>
            <th scope="col" style="width: 5%;">Paid</th>
            <th scope="col" style="width: 12%;">State</th>
        </tr>
        </thead>

        <!-- table body -->
        <tbody>
        <?php if (isset($orders) && count($orders) > 0) {
            //Add each order to the table
            foreach ($orders as $order) {
                if ($order instanceof Order) {
                    $user = UserController::getById($order->getUserId());
                    $orderState = OrderStateController::getById($order->getOrderStateId());
                    ?>

                    <!-- action buttons -->
                    <tr>
                        <td data-th="" class="align-middle">
                            <!-- delete order button -->
                            <a class="btn btn-secondary btn-sm" data-toggle="tooltip" data-placement="left"
                               title="Delete order"
                               onclick="openConfirmModal(<?= "'Do you really want to delete the order with ID: \'" . $order->getId() . "\'and all its information?'" ?>,
                                       'Delete Order?',
                                       '<?= str_replace(DS, "/", INCLUDE_HELPER_DIR . "helper_delete_order.inc.php?id=" . $order->getId()); ?>')">
                                <em class="fa fa-trash "></em>
                            </a>
                        </td>

                        <!-- Order id -->
                        <td data-th="#" class="align-middle">
                            <strong><?= $order->getID(); ?></strong>
                        </td>

                        <!-- User id who created this order -->
                        <td data-th="User ID" class="align-middle">
                            <?php if (isset($user)) {
                                echo $user->getId();
                            } else {
                                echo "#";
                            } ?>
                        </td>

                        <!-- The name of the user -->
                        <td data-th="Username" class="align-middle">
                            <?php if (isset($user)) {
                                echo $user->getFormattedName();
                            } else {
                                echo "User Not Found.";
                            } ?>
                        </td>

                        <!-- The delivery date for the order -->
                        <td data-th="Delivery Date" class="align-middle">
                            <?= $order->getFormattedDeliveryDate(); ?>
                        </td>

                        <!-- The date on which this order was created -->
                        <td data-th="Order Date" class="align-middle">
                            <?= $order->getFormattedOrderDate(); ?>
                        </td>

                        <!-- Is the order paid? -->
                        <td data-th="Paid" class="align-middle">
                            <?= $order->isPaid() ? "Yes" : "No"; ?>
                        </td>

                        <!-- In which state is the order? -->
                        <td data-th="State" class="align-middle">
                            <label for="stateSelector" class="visually-hidden">Set Order State</label>
                            <select class="form-select" name="stateSelector" id="stateSelector"
                                    onchange="onOrderStateChange(this, <?= $order->getId() ?>, <?= $order->getOrderStateId(); ?>)">
                                <?php foreach ($orderStates as $orderStateItem) { ?>
                                    <option value="<?= $orderStateItem->getId(); ?>"
                                        <?php if ($orderStateItem->getId() == $orderState->getId()) {
                                            echo "selected";
                                        } ?>>
                                        <?= $orderStateItem->getLabel(); ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </td>

                    </tr>
                <?php }
            }
        } else { // no orders in array ?>
            <tr>
                <td colspan="8" style="text-align: center">
                    <p><em class="mb-3 text-muted">No orders are available.</em></p>
                </td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
</main>

<!-- enable tooltips on this page -->
<script src="<?= SCRIPT_DIR . "tooltip_enable.js" ?>"></script>

<!-- confirm modal -->
<?php require_once INCLUDE_MODAL_DIR . "modal_confirm.inc.php"; ?>

<!-- dynamic popup modal -->
<?php require_once INCLUDE_MODAL_DIR . "modal_popup.inc.php"; ?>

<!-- pagination -->
<?php require INCLUDE_DIR . "dyn_pagination.inc.php" ?>

<!-- footer -->
<?php require_once INCLUDE_DIR . "site_footer.inc.php"; ?>

</body>
</html>