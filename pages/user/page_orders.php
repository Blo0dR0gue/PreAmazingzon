<!-- TODO COMMENT -->
<?php
require_once "../../include/site_php_head.inc.php";

UserController::redirectIfNotLoggedIn();

$page = (isset($_GET['page']) && is_numeric($_GET['page'])) ? $_GET['page'] : 1;    // Current pagination page number
$offset = ($page - 1) * LIMIT_OF_SHOWED_ITEMS;                                      // Calculate offset for pagination
$orderCount = OrderController::getAmountForUser($_SESSION["uid"]);                  // Get the total amount of order for the user
$totalPages = ceil($orderCount / LIMIT_OF_SHOWED_ITEMS);                            // Calculate the total amount of pages // TODO maybe other limits for orders?

// Redirect to order page without page get variable which means we are on page 1
if ($page > $totalPages && $totalPages > 0) { header("Location: " . USER_PAGES_DIR . "page_orders.php"); }

$orders = OrderController::getAllForUserInRange($_SESSION["uid"], $offset);
?>

<!DOCTYPE html>
<html class="h-100" lang="en">
<head>
    <?php require_once INCLUDE_DIR . "site_html_head.inc.php"; ?>
    <title><?= PAGE_NAME ?> - Orders</title>
</head>

<body class="d-flex flex-column h-100">
<!-- header -->
<?php require_once INCLUDE_DIR . "site_header.inc.php"; ?>

<!-- main body -->
<main class="w-100 px-3">
    <div class="container" style="text-align: center">
        <!-- Heading -->
        <div>
            <h2 class="mb-2 mt-4 text-start">Your Orders</h2>
        </div>

        <div class="mt-4">
            <!-- Orders -->
            <div class="col-md-12">
                <?php
                if ($orderCount > 0) {
                    foreach ($orders as $order) {
                        require INCLUDE_ELEMENTS_DIR . "elem_order_card.inc.php";
                    }
                } else {
                    echo "<h5 class='text-center text-muted mb-5'><i>No Orders found ... just buy something, and it will appear here.</i></h5>";
                }
                ?>
            </div>
        </div>
    </div>
</main>

<!-- pagination -->
<?php require INCLUDE_DIR . "dyn_pagination.inc.php" ?>

<!-- footer -->
<?php require_once INCLUDE_DIR . "site_footer.inc.php"; ?>

</body>
</html>
