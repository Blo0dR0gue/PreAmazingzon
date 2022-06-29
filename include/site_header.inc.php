<!-- site header resumed all over the website -->

<!-- TODO comment -->

<header class="p-3 bg-dark text-white">
    <div class="container">
        <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start">
            <!-- logo -->
            <a href="<?= ROOT_DIR ?>" class="d-flex align-items-center mb-2 mb-lg-0">
                <img src="<?= IMAGE_LOGO_DIR . "logo_long_inv.svg" ?>" class="bi me-2" width="150"
                     height="40" alt="Company Logo">
            </a>

            <!-- nav -->
            <ul class="nav col-12 col-lg-auto me-lg-auto mb-2 justify-content-center mb-md-0">
                <li><a href="<?= ROOT_DIR ?>" class="nav-link px-2 text-white">Home</a></li>
                <li><a href="<?= PAGES_DIR . 'page_products.php' ?>" class="nav-link px-2 text-white">Products</a></li>
                <li><a href="<?= PAGES_DIR . 'page_categories.php' ?>" class="nav-link px-2 text-white">Categories</a></li>
                <li><a href="<?= PAGES_DIR . "page_about.php" ?>" class="nav-link px-2 text-white">About</a></li>
            </ul>

            <!-- search -->
            <!-- TODO make search work -->
            <form class="col-12 col-lg-auto mb-3 mb-lg-0 me-lg-3" method="get"
                  action="<?= PAGES_DIR . 'page_products.php' ?>">
                <div class="input-group rounded">
                    <input type="search" name="search" class="form-control rounded-start" placeholder="Search"
                           aria-label="Search" aria-describedby="search-addon"
                           value="<?= $_GET["search"] ?? "" ?>" minlength="3"/>
                    <button type="submit" class="input-group-text border-0" id="search-addon">
                        <i class="fa fa-search"></i>
                    </button>
                </div>
            </form>

            <!-- dependant on login state -->
            <?php if (!UserController::isCurrentSessionLoggedIn()) { ?>
                <!-- show login and register buttons -->
                <div class="text-end">
                    <a href="<?= PAGES_DIR . "page_login.php" ?>" class="btn btn-warning me-2">Login</a>
                    <a href="<?= PAGES_DIR . "page_register.php" ?>" class="btn btn-outline-light">Sign-up</a>
                </div>
            <?php } else { ?>
                <!-- shopping cart -->
                <a href="<?= USER_PAGES_DIR . "page_shopping_cart.php" ?>"
                   class="d-flex align-items-center text-decoration-none me-3">
                    <i class='fa fa-shopping-cart link-warning' style='font-size:38px'></i>
                </a>

                <!-- show profile action -->
                <div class="dropdown text-end">
                    <a href="" class="d-block link-light text-decoration-none dropdown-toggle" id="dropdownUser"
                       data-bs-toggle="dropdown" aria-expanded="false">
                        <img src="<?= IMAGE_DIR . "user_orange.svg" ?>" alt="mdo"
                             class="rounded-circle" width="40" height="40">
                    </a>
                    <!-- drop down list -->
                    <ul class="dropdown-menu text-small" aria-labelledby="dropdownUser">
                        <!-- user area -->
                        <li>
                            <p class="dropdown-item-text mb-1 text-muted"><?= $_SESSION["first_name"] . " " . $_SESSION["last_name"] ?></p>
                        </li>
                        <li>
                            <a class="dropdown-item" href="<?= USER_PAGES_DIR . "page_profile.php" ?>">Profile Info</a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="<?= USER_PAGES_DIR . "page_orders.php" ?>">Your Orders</a>
                        </li>

                        <!-- admin area -->
                        <?php if (isset($_SESSION["isAdmin"]) && $_SESSION["isAdmin"] === true) { ?>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li>
                                <p class="dropdown-item-text mb-1 text-muted">Admin Tools:</p>
                            </li>
                            <li>
                                <a class="dropdown-item" href="<?=ADMIN_PAGES_DIR . 'page_products.php'?>">Manage Products</a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="<?=ADMIN_PAGES_DIR . 'page_categories.php'?>">Manage Categories</a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="<?=ADMIN_PAGES_DIR . 'page_users.php'?>">Manage Users</a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="<?=ADMIN_PAGES_DIR . 'page_orders.php'?>">Manage Orders</a>
                            </li>
                        <?php } ?>

                        <!------------------>
                        <li><hr class="dropdown-divider"></li>

                        <!-- logout -->
                        <li>
                            <a class="dropdown-item" href="<?= INCLUDE_HELPER_DIR . "helper_logout.inc.php" ?>">Logout</a>
                        </li>
                    </ul>
                </div>
            <?php } ?>
        </div>
    </div>
</header>
