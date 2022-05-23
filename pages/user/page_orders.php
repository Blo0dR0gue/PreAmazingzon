<!-- TODO COMMENT -->
<?php
require_once "../../include/site_php_head.inc.php";

UserController::redirectIfNotLoggedIn();

$page = (isset($_GET['page']) && is_numeric($_GET['page'])) ? $_GET['page'] : 1;    // Current pagination page number
$offset = ($page - 1) * LIMIT_OF_SHOWED_ITEMS;      // Calculate offset for pagination
$orderCount = OrderController::getAmountForUser($_SESSION["uid"]);      // Get the total amount of order for the user
$totalPages = ceil($orderCount / LIMIT_OF_SHOWED_ITEMS);        // Calculate the total amount of pages //TODO maybe other limits for orders?

$orders = OrderController::getAllForUserInRange($_SESSION["uid"], $offset);

?>

<!DOCTYPE html>
<html class="h-100" lang="en">
<head>
    <?php require_once INCLUDE_DIR . DS . "site_html_head.inc.php"; ?>
    <title><?= PAGE_NAME ?> - Orders</title>
</head>

<body class="d-flex flex-column h-100">
<!-- header -->
<?php require_once INCLUDE_DIR . DS . "site_header.inc.php"; ?>

<!-- main body -->
<main class="w-100 px-3">

    <div class="container" style="text-align: center">
        <!--Heading-->
        <div>
            <h2 class="mb-2 mt-4">Your Orders</h2>
        </div>

        <div class="row mt-4">
            <!-- Orders -->
            <div class="col-md-12">

                <?php
                if ($orderCount > 0):
                    foreach ($orders as $order):

                        require INCLUDE_DIR . DS . "elem_order_card.inc.php";

                    endforeach;
                else:
                    echo "<h5 class='text-center text-muted mb-5'><i>No Orders found.</i></h5>";
                endif;
                ?>

            </div>
        </div>
    </div>

</main>

<!-- pagination -->
<?php require INCLUDE_DIR . DS . "dyn_pagination.inc.php" ?>

<!-- footer -->
<?php require_once INCLUDE_DIR . DS . "site_footer.inc.php"; ?>

</body>
</html>
