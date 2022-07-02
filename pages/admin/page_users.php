<?php require_once "../../include/site_php_head.inc.php"; ?>

<?php
UserController::redirectIfNotAdmin();   // User is not allowed to be here.

// pagination stuff
$page = (isset($_GET['page']) && is_numeric($_GET['page'])) ? $_GET['page'] : 1;  // Current pagination page number
$offset = ($page - 1) * LIMIT_OF_SHOWED_ITEMS;                                    // Calculate offset for pagination
$userCount = UserController::getAmountOfUsers();                                  // Get the total amount of users
$totalPages = ceil($userCount / LIMIT_OF_SHOWED_ITEMS);                      // Calculate the total amount of pages

$users = UserController::getUsersInRange($offset);

//Get roles
$adminUserRole = UserRoleController::getAdminUserRole();
$defaultUserRole = UserRoleController::getDefaultUserRole();
?>

<!DOCTYPE html>
<html class="h-100" lang="en">
<head>
    <?php require_once INCLUDE_DIR . "site_html_head.inc.php"; ?>
    <title><?= PAGE_NAME ?> - Admin - Users</title>

    <!-- file specific includes-->
    <link rel="stylesheet" href="<?= STYLE_DIR . "style_admin_pages.css"; ?>">
    <!--Add page script-->
    <script src="<?= SCRIPT_DIR . "admin_users_page.js" ?>"></script>
    <!-- Add php modal functionality -->
    <?php require_once INCLUDE_MODAL_DIR . "modal_popup.inc.php"; ?>
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
            <th scope="col" style="width: 10%"></th>
            <th scope="col" style="width: 5%">#</th>
            <th scope="col" style="width: 20%">E-Mail</th>
            <th scope="col" style="width: 15%;">Firstname</th>
            <th scope="col" style="width: 15%">Lastname</th>
            <th scope="col" style="width: 15%">Primary Address ID</th>
            <th scope="col" style="width: 5%">Active</th>
            <th scope="col" style="width: 5%">Role</th>
        </tr>
        </thead>

        <!-- table body -->
        <tbody>
        <?php if (isset($users) && count($users) > 0) {
            foreach ($users as $user) { ?>
                <!-- table row -->
                <tr>
                    <!-- buttons -->
                    <td data-th="" class="align-middle">
                        <!--Enable/Disable user-->
                        <button
                                class="btn btn-sm <?= $user->isActive() ? "btn-success" : "btn-warning" ?> <?= $user->getId() == $_SESSION["uid"] ? "disabled" : "" ?>"
                                data-toggle="tooltip" data-placement="left" title="(De-) Activate User"
                                onclick="onToggleUserActivation(this, <?= $user->getId(); ?>)">
                            <em class="fa <?= $user->isActive() ? "fa-toggle-on" : "fa-toggle-off" ?>"
                                id="activeBtnImg<?= $user->getId() ?>"></em>
                        </button>

                        <!--Toggle admin status-->
                        <button
                                class="btn btn-sm <?= $user->getRoleId() == $adminUserRole->getId() ? "btn-success" : "btn-warning" ?> <?= $user->getId() == $_SESSION["uid"] ? "disabled" : "" ?>"
                                data-toggle="tooltip" data-placement="left" title="Toggle User Admin"
                                onclick="onToggleUserRole(this, <?= $user->getId(); ?>)">
                            <em class="fa <?= $user->getRoleId() == $adminUserRole->getId() ? "fa-toggle-on" : "fa-toggle-off" ?>"
                                id="adminBtnImg<?= $user->getId() ?>"></em>
                        </button>

                        <!--Delete user-->
                        <a class="btn btn-secondary btn-sm" data-toggle="tooltip" data-placement="left"
                           title="Delete user"
                           onclick="openConfirmModal(<?= "'Do you really want to delete the user: \'" . $user->getFormattedName() . "\', with ID: " . $user->getId() . " and all his information?'" ?>,
                                   'Delete User?',
                                   '<?= str_replace(DS, "/", INCLUDE_HELPER_DIR . "helper_delete_user.inc.php?id=" . $user->getId()); ?>')">
                            <em class="fa fa-trash "></em>
                        </a>
                    </td>

                    <td data-th="#" class="align-middle">
                        <strong><?= $user->getID(); ?></strong>
                    </td>

                    <td data-th="E-Mail" class="align-middle">
                        <?= $user->getEmail(); ?>
                    </td>

                    <td data-th="Firstname" class="align-middle">
                        <?= $user->getFirstName(); ?>
                    </td>

                    <td data-th="Lastname" class="align-middle">
                        <?= $user->getLastName(); ?>
                    </td>

                    <td data-th="Primary Address ID" class="align-middle">
                        <?= $user->getDefaultAddressId() ?? "Not Set"; ?>
                    </td>

                    <td data-th="Active" class="align-middle" data-activeUserId="<?= $user->getId(); ?>">
                        <?= $user->isActive() ? "Yes" : "No"; ?>
                    </td>

                    <td data-th="Role" class="align-middle" data-roleUserId="<?= $user->getId(); ?>">
                        <?= $user->getRoleId() == $adminUserRole->getId() ? $adminUserRole->getName() : $defaultUserRole->getName(); ?>
                    </td>
                </tr>
            <?php }
        } else { ?>
            <tr>
                <td colspan="8" style="text-align: center">
                    <p><em class="mb-3">No users are available.</em></p>
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
