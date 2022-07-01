<!-- TODO COMMENT -->
<?php require_once "../../include/site_php_head.inc.php" ?>

<?php
UserController::redirectIfNotAdmin();   // User is not allowed to be here.

// pagination stuff
$page = (isset($_GET['page']) && is_numeric($_GET['page'])) ? $_GET['page'] : 1;  // Current pagination page number
$offset = ($page - 1) * LIMIT_OF_SHOWED_ITEMS;                                    // Calculate offset for pagination
$userCount = OrderController::getAmountOfUsers();                              // Get the total amount of users
$totalPages = ceil($userCount / LIMIT_OF_SHOWED_ITEMS);                      // Calculate the total amount of pages

$orders = OrderController::getAllInRange($offset, LIMIT_OF_SHOWED_ITEMS);

$orderStates = OrderStateController::getAll();

?>

<!DOCTYPE html>
<html class="h-100" lang="en">
<head>
    <?php require_once INCLUDE_DIR . "site_html_head.inc.php"; ?>
    <title><?= PAGE_NAME ?> - Admin - Orders</title>

    <?php require_once INCLUDE_DIR . "modal_popup.inc.php"; ?>
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
            <th scope="col" style="width: 8%"></th>
            <th scope="col" style="width: 5%">#</th>
            <th scope="col" style="width: 8%">User ID</th>
            <th scope="col" style="width: 25%;">Username</th>
            <th scope="col" style="width: 17%">Delivery Date</th>
            <th scope="col" style="width: 17%">Order Date</th>
            <th scope="col" style="width: 5%;">Paid?</th>
            <th scope="col" style="width: 15%;">State</th>
        </tr>
        </thead>

        <!-- table body -->
        <tbody>
        <?php
        if (isset($orders)):
            foreach ($orders as $order):
                if ($order instanceof Order):
                    ?>

                    <?php
                    $user = UserController::getById($order->getUserId());
                    $orderState = OrderStateController::getById($order->getOrderStateId());
                    ?>
                    <tr>
                        <td class="align-middle" data-th="">
                            <!--TODO user delete -->
                            <a class="btn btn-secondary btn-sm" data-toggle="tooltip" data-placement="left"
                               title="Delete order"
                               onclick="openConfirmModal(<?= "'Do you really want to delete the order with ID: \'" . $order->getId() . "\'and all its information?'" ?>,
                                       'Delete Order?',
                                       '<?= str_replace(DS, "/", INCLUDE_HELPER_DIR . "helper_delete_order.inc.php?id=" . $order->getId()); ?>')">
                                <em class="fa fa-trash "></em>
                            </a>
                        </td>

                        <td data-th="#">
                            <strong><?= $order->getID(); ?></strong>
                        </td>

                        <td data-th="User ID">
                            <?php
                            if (isset($user)) {
                                echo $user->getId();
                            } else {
                                echo "#";
                            }
                            ?>
                        </td>

                        <td data-th="Username">
                            <?php
                            if (isset($user)) {
                                echo $user->getFormattedName();
                            } else {
                                echo "User Not Found.";
                            }
                            ?>
                        </td>

                        <td data-th="Delivery Date">
                            <?= $order->getFormattedDeliveryDate(); ?>
                        </td>

                        <td data-th="Order Date">
                            <?= $order->getFormattedOrderDate(); ?>
                        </td>
                        <td data-th="Paid?">
                            <?= $order->isPaid() ? "Yes" : "No"; ?>
                        </td>
                        <td data-th="State">
                            <select class="form-select" name="stateSelector"
                                    onchange="onOrderStateChange(this, <?= $order->getId() ?>, <?= $order->getOrderStateId(); ?>)"><!--TODO onchange-->

                                <?php foreach ($orderStates as $orderStateItem): ?>
                                    <option value="<?= $orderStateItem->getId(); ?>"
                                        <?php
                                        if ($orderStateItem->getId() == $orderState->getId()) {
                                            echo "selected";
                                        } ?>
                                    >
                                        <?= $orderStateItem->getLabel(); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </td>
                    </tr>
                <?php
                endif;
            endforeach;
        else:
            ?>
            <tr>
                <td colspan="8" style="text-align: center">
                    <p><em class="mb-3 text-muted">No orders are available.</em></p>
                </td>
            </tr>
        <?php endif; ?>
        </tbody>
    </table>
</main>

<!-- enable tooltips on this page -->
<script src="<?= SCRIPT_DIR . "tooltip_enable.js" ?>"></script>

<!-- confirm modal -->
<?php require_once INCLUDE_DIR . "modal_confirm.inc.php"; ?>

<!-- dynamic popup modal -->
<?php require_once INCLUDE_DIR . "modal_popup_content.inc.php"; ?>

<!-- pagination -->
<?php require INCLUDE_DIR . "dyn_pagination.inc.php" ?>

<!-- footer -->
<?php require_once INCLUDE_DIR . "site_footer.inc.php"; ?>

</body>
</html>
