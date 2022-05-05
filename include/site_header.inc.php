<!-- site header resumed all over the website -->

<!-- TODO make links wor-->

<header class="p-3 bg-dark text-white">
    <div class="container">
        <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start">
            <a href="<?php global $ROOT_DIR; echo $ROOT_DIR ?>" class="d-flex align-items-center mb-2 mb-lg-0 text-white text-decoration-none">
                <img src="<?= IMAGE_DIR . "/logo/logo_long_inv.svg" ?>" class="bi me-2" width="150" height="40"
                     alt="Company Logo">
            </a>

            <ul class="nav col-12 col-lg-auto me-lg-auto mb-2 justify-content-center mb-md-0">
                <li><a href="<?php global $ROOT_DIR; echo $ROOT_DIR ?>" class="nav-link px-2 text-secondary">Home</a></li>
                <li><a href="#" class="nav-link px-2 text-white">Features</a></li>
                <li><a href="#" class="nav-link px-2 text-white">Pricing</a></li>
                <li><a href="#" class="nav-link px-2 text-white">FAQs</a></li>
                <li><a href="<?= PAGES_DIR . DIRECTORY_SEPARATOR . 'page_about.php' ?>"
                       class="nav-link px-2 text-white">About</a></li>
            </ul>

            <form class="col-12 col-lg-auto mb-3 mb-lg-0 me-lg-3">
                <input type="search" class="form-control form-control-dark" placeholder="Search..." aria-label="Search">
            </form>

            <!-- defendant on login state-->
            <?php if (!isset($_SESSION["login"])) { ?>
                <!-- show login and register buttons -->
                <div class="text-end">
                    <a href="<?= PAGES_DIR . DIRECTORY_SEPARATOR . 'page_login.php' ?>"
                       class="btn btn-warning me-2">Login</a>
                    <a href="<?= PAGES_DIR . DIRECTORY_SEPARATOR . 'page_register.php' ?>"
                       class="btn btn-outline-light">Sign-up</a>
                </div>
            <?php } else { ?>
                <!-- show profile action -->
                <!-- TODO add links-->
                <div class="dropdown text-end">
                    <a href="#" class="d-block link-light text-decoration-none dropdown-toggle" id="dropdownUser"
                       data-bs-toggle="dropdown" aria-expanded="false">
                        <img src="<?= IMAGE_DIR . DIRECTORY_SEPARATOR . "user_orange.svg" ?>" alt="mdo"
                             class="rounded-circle" width="40" height="40">
                    </a>
                    <ul class="dropdown-menu text-small" aria-labelledby="dropdownUser" style="">
                        <li><a class="dropdown-item" href="#">New project...</a></li>
                        <li><a class="dropdown-item" href="#">Settings</a></li>
                        <li><a class="dropdown-item" href="#">Profile</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="#">Sign out</a></li>
                    </ul>
                </div>
            <?php } ?>
        </div>
    </div>
</header>
