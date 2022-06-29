<!-- TODO COMMENT-->

<?php require_once "../../include/site_php_head.inc.php"; ?>

<?php
UserController::redirectIfNotAdmin();   // User is not allowed to be here.

// pagination stuff
$page = (isset($_GET['page']) && is_numeric($_GET['page'])) ? $_GET['page'] : 1;  // Current pagination page number
$offset = ($page - 1) * LIMIT_OF_SHOWED_ITEMS;                                    // Calculate offset for pagination
$userCount = UserController::getAmountOfUsers();                                  // Get the total amount of users
$totalPages = ceil($userCount / LIMIT_OF_SHOWED_ITEMS);                      // Calculate the total amount of pages

$users = UserController::getUsersInRange($offset)
?>
<!-- TODO if deleted pagination triggered modal each time -->

<!DOCTYPE html>
<html class="h-100" lang="en">
<head>
    <?php require_once INCLUDE_DIR . "site_html_head.inc.php"; ?>
    <title><?= PAGE_NAME ?> - Admin - Users</title>

    <!-- file specific includes-->
    <link rel="stylesheet" href="<?= STYLE_DIR . "style_admin_pages.css"; ?>">
    <script src="<?= SCRIPT_DIR . "admin_user_page.js" ?>"></script>
    <?php require_once INCLUDE_DIR . "modal_popup.inc.php"; ?>
</head>

<body class="d-flex flex-column h-100">
<!-- header -->
<?php require_once INCLUDE_DIR . "site_header.inc.php"; ?>

<!-- main body -->
<main class="flex-shrink-0 container">
    <!-- page header -->
    <div class="d-flex align-items-end">
        <h1 class="mt-4 ms-2 mb-0 mr-auto">All Users</h1>
    </div>
    <hr class="mt-2">

    <!-- user table -->
    <table class="table" aria-label="User Table">
        <!-- table head -->
        <thead class="thead-light">
        <tr>
            <th scope="col" style="width: 8%"></th>
            <th scope="col" style="width: 5%">#</th>
            <th scope="col" style="width: 25%">E-Mail</th>
            <th scope="col" style="width: 15%;">Firstname</th>
            <th scope="col" style="width: 15%">Lastname</th>
            <th scope="col" style="width: 10%">Primary Address ID</th>
            <th scope="col" style="width: 5%">Active</th>
        </tr>
        </thead>

        <!-- table body -->
        <tbody>
        <?php foreach ($users as $user): ?>
            <tr>
                <td class="align-middle" data-th="">
                    <button
                            class="btn btn-sm <?= $user->isActive() ? "btn-success" : "btn-warning" ?> <?= $user->getId() == $_SESSION["uid"] ? "disabled" : "" ?>"
                            data-toggle="tooltip" data-placement="left"
                            title="(De-) Activate User"
                            onclick="onToggleUserActivation(this, <?= $user->getId(); ?>)">
                        <em class="fa fa-toggle-on"></em>
                    </button>
                    <!--TODO make admin button?-->
                    <!--TODO user delete -->
                    <a class="btn btn-secondary btn-sm" data-toggle="tooltip" data-placement="left"
                       title="Delete user"
                       onclick="openConfirmModal(<?= "'Do you really want to delete the user: \'" . $user->getFormattedName() . "\', with ID: " . $user->getId() . " and all his information?'" ?>,
                               'Delete User?',
                               '<?= str_replace(DS, "/", INCLUDE_HELPER_DIR . "helper_delete_user.inc.php?id=" . $user->getId()); ?>')">
                        <em class="fa fa-trash "></em>
                    </a>
                </td>

                <td data-th="#">
                    <strong><?= $user->getID(); ?></strong>
                </td>

                <td data-th="E-Mail">
                    <?= $user->getEmail(); ?>
                </td>

                <td data-th="Firstname">
                    <?= $user->getFirstName(); ?>
                </td>

                <td data-th="Lastname">
                    <?= $user->getLastName(); ?>
                </td>

                <td data-th="Primary Address ID">
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
<script src="<?= SCRIPT_DIR . "tooltip_enable.js" ?>"></script>

<!-- confirm modal -->
<?php require_once INCLUDE_DIR . "modal_confirm.inc.php"; ?>

<!-- pagination -->
<?php require INCLUDE_DIR . "dyn_pagination.inc.php" ?>

<!-- footer -->
<?php require_once INCLUDE_DIR . "site_footer.inc.php"; ?>

<!-- show info popup -->
<?php
if (isset($_GET["deleted"]) || isset($_GET["other"])) {   // success messages
    $msg = "";
    if (isset($_GET["deleted"])) {
        $msg = "The user got deleted!";
    } else if (isset($_GET["other"])) {
        $msg = "test";  // TODO remove
    }

    show_popup("Users", $msg);
}
?>

</body>
</html>
