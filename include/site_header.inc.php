<!-- site header resumed all over the website -->

<!-- TODO make links work -->

<header class="p-3 bg-dark text-white">
    <div class="container">
        <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start">
            <!-- logo -->
            <a href="<?= ROOT_DIR ?>" class="d-flex align-items-center mb-2 mb-lg-0">
                <img src="<?= IMAGE_LOGO_DIR . DIRECTORY_SEPARATOR . "logo_long_inv.svg" ?>" class="bi me-2" width="150"
                     height="40" alt="Company Logo">
            </a>

            <!-- nav -->
            <ul class="nav col-12 col-lg-auto me-lg-auto mb-2 justify-content-center mb-md-0">
                <li><a href="<?= ROOT_DIR ?>" class="nav-link px-2 text-white">Home</a></li>
                <li><a href="<?= PAGES_DIR . DIRECTORY_SEPARATOR . 'page_products.php' ?>"
                       class="nav-link px-2 text-white">Products</a></li>
                <li><a href="#" class="nav-link px-2 text-white">Categories</a></li>
                <li><a href="<?= PAGES_DIR . DIRECTORY_SEPARATOR . "page_about.php" ?>"
                       class="nav-link px-2 text-white">About</a></li>
            </ul>

            <!-- search -->
            <!-- TODO make search work -->
            <form class="col-12 col-lg-auto mb-3 mb-lg-0 me-lg-3" method="get"
                  action="<?= PAGES_DIR . DIRECTORY_SEPARATOR . 'page_products.php' ?>">
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
            <?php if (!isset($_SESSION["login"])) { ?>
                <!-- show login and register buttons -->
                <div class="text-end">
                    <a href="<?= PAGES_DIR . DIRECTORY_SEPARATOR . "page_login.php" ?>"
                       class="btn btn-warning me-2">Login</a>
                    <a href="<?= PAGES_DIR . DIRECTORY_SEPARATOR . "page_register.php" ?>"
                       class="btn btn-outline-light">Sign-up</a>
                </div>
            <?php } else { ?>
                <!-- shopping cart -->
                <a href="<?= PAGES_DIR . DIRECTORY_SEPARATOR . "page_shopping_cart.php" ?>"
                   class="d-flex align-items-center text-decoration-none me-3">
                    <i class='fa fa-shopping-cart link-warning' style='font-size:38px'></i>
                </a>
                <!-- show profile action -->
                <!-- TODO add links -->
                <div class="dropdown text-end">
                    <a href="" class="d-block link-light text-decoration-none dropdown-toggle" id="dropdownUser"
                       data-bs-toggle="dropdown" aria-expanded="false">
                        <img src="<?= IMAGE_DIR . DIRECTORY_SEPARATOR . "user_orange.svg" ?>" alt="mdo"
                             class="rounded-circle" width="40" height="40">
                    </a>
                    <ul class="dropdown-menu text-small" aria-labelledby="dropdownUser">
                        <li>
                            <p class="dropdown-item-text mb-1 text-muted"><?= $_SESSION["first_name"] . " " . $_SESSION["last_name"] ?></p>
                        </li>
                        <li><a class="dropdown-item" href="<?= PAGES_DIR . DIRECTORY_SEPARATOR . "page_profile.php" ?>">Profile
                                Info</a></li>
                        <?php
                        if (isset($_SESSION["isAdmin"]) && $_SESSION["isAdmin"] === true):
                            ?>
                        <!--TODO Maybe add Dashboard?-->
                            <li><a class="dropdown-item" href="<?=ADMIN_PAGES_DIR . DIRECTORY_SEPARATOR . 'page_products.php'?>">Show and Edit Products</a></li>
                        <?php
                        endif;
                        ?>
                        <li><a class="dropdown-item" href="#">###</a></li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li><a class="dropdown-item"
                               href="<?= INCLUDE_HELPER_DIR . DIRECTORY_SEPARATOR . "helper_logout.inc.php" ?>">Logout</a>
                        </li>
                    </ul>
                </div>
            <?php } ?>
        </div>
    </div>
</header>
