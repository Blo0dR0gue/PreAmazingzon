<!-- TODO COMMENT-->

<?php require_once "../../include/site_php_head.inc.php"; ?>

<?php
UserController::redirectIfNotAdmin();   //User is not allowed to be here.

// pagination stuff
$page = (isset($_GET['page']) && is_numeric($_GET['page'])) ? $_GET['page'] : 1;  // Current pagination page number
$offset = ($page - 1) * LIMIT_OF_SHOWED_ITEMS;                                    // Calculate offset for pagination
$userCount = UserController::getAmountOfUsers(null);                             // Get the total amount of users
$totalPages = ceil($userCount / LIMIT_OF_SHOWED_ITEMS);                     // Calculate the total amount of pages

$users = UserController::getUsersInRange($offset)

?>
<!-- TODO if deleted pagination triggered modal each time -->
<!DOCTYPE html>
<html class="h-100" lang="en">
<head>
    <?php require_once INCLUDE_DIR . DS . "site_html_head.inc.php"; ?>
    <title><?= PAGE_NAME ?> - Admin - Users</title>

    <!-- file specific includes-->
    <link rel="stylesheet" href="<?= STYLE_DIR . DS . "style_admin_pages.css"; ?>">
    <script src="<?= SCRIPT_DIR . DS . "admin_user_page.js" ?>"></script>
    <?php require_once INCLUDE_DIR . DS . "modal_popup.inc.php"; ?>
</head>

<body class="d-flex flex-column h-100">
<!-- header -->
<?php require_once INCLUDE_DIR . DS . "site_header.inc.php"; ?>

<!-- main body -->
<main class="flex-shrink-0 container">

    <!-- page header -->
    <div class="d-flex align-items-end">
        <h1 class="mt-4 ms-2 mb-0 mr-auto">All categories</h1>
        <!-- add button -->
        <a type="button" class="btn btn-warning ms-auto" href="<?= ADMIN_PAGES_DIR . DS . "page_user_add.php" ?>">
            <i class="fa fa-plus"></i> Add User
        </a>
    </div>
    <hr class="mt-2">

    <!-- category table -->
    <table class="table">
        <!-- table head -->
        <thead class="thead-light">
        <tr>
            <th scope="col" style="width: 5%"></th>
            <th scope="col" style="width: 5%">#</th>
            <th scope="col" style="width: 30%; text-align: center">E-Mail</th>
            <th scope="col" style="width: 20%;">Firstname</th>
            <th scope="col" style="width: 20%">Lastname</th>
            <th scope="col" style="width: 10%">Primary Address ID</th>
            <th scope="col" style="width: 10%">Active</th>
        </tr>
        </thead>

        <!-- table body -->
        <tbody>
        <?php foreach ($users as $user): ?>
            <tr>
                <td class="align-middle" data-th="">
                    <button
                            class="btn btn-sm mb-1 <?= $user->isActive() ? "btn-success" : "btn-warning" ?> <?= $user->getId() == $_SESSION["uid"]?"disabled":"" ?>"
                            data-toggle="tooltip" data-placement="left"
                            title="Enable / Disable User"
                            onclick="onToggleUserActivation(this, <?= $user->getId(); ?>)">
                        <i class="fa fa-pencil"></i>
                    </button>
                    <!--TODO-->
                    <a class="btn btn-secondary btn-sm" data-toggle="tooltip" data-placement="left"
                       title="Delete user"
                       onclick="openConfirmModal(<?= "'Do you really want to delete the user: \'" . $user->getFormattedName() . "\', with ID: " . $user->getId() . " and all his information?'" ?>,
                               'Delete Product?',
                               '<?= str_replace(DS, "/", INCLUDE_HELPER_DIR . DS . "helper_delete_user.inc.php?id=" . $user->getId()); ?>')">
                        <i class="fa fa-trash "></i>
                    </a>
                </td>

                <td data-th="#">
                    <b><?= $user->getID(); ?></b>
                </td>

                <td style="text-align: center" data-th="E-Mail">
                    <?= $user->getEmail(); ?>
                </td>

                <td data-th="Firstname">
                    <?= $user->getFirstName(); ?>
                </td>

                <td data-th="Lastname">
                    <?= $user->getLastName(); ?>
                </td>

                <td data-th="Primary Address">
                    <?= $user->getDefaultAddressId() ?? "Not Set"; ?>
                </td>

                <td data-th="Active" data-id="<?= $user->getId(); ?>">
                    <?= $user->isActive() ? "Yes" : "No"; ?>
                </td>

            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</main>

<!-- enable tooltips on this page -->
<script src="<?= SCRIPT_DIR . DS . "tooltip_enable.js" ?>"></script>

<!-- confirm modal -->
<?php require_once INCLUDE_DIR . DS . "modal_confirm.inc.php"; ?>

<!-- pagination -->
<?php require INCLUDE_DIR . DS . "dyn_pagination.inc.php" ?>

<!-- footer -->
<?php require_once INCLUDE_DIR . DS . "site_footer.inc.php"; ?>

<!-- show info popup -->
<?php
if (isset($_GET["deleted"]) || isset($_GET["other"])) {   // success messages
    $msg = "";
    if (isset($_GET["deleted"])) {
        $msg = "The category got deleted!";
    } else if (isset($_GET["other"])) {
        $msg = "test";  //TODO remove
    }

    show_popup("Categories", $msg);
}
?>

</body>
</html>
