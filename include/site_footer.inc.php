<!-- site footer reused all over the website -->

<!-- footer -->
<footer class="bg-light border-top mt-auto">
    <div class="container d-flex flex-wrap justify-content-between align-items-center py-3 my-3">
        <!-- footer left -->
        <div class="col-md-6 d-flex align-items-center">
            <!-- company logo -->
            <a href="/" class="mb-0">
                <img src="<?=IMAGE_DIR.'/logo/logo.svg'?>" class="bi me-2" width="40" height="40" alt="Company Logo">
            </a>

            <!-- company copy right -->
            <p class="text-muted mb-0">© 2022 Amazingzon, Inc. All rights reserved.</p>
        </div>

        <!-- footer right -->
        <!-- TODO edit footer navigation -->
        <ul class="nav col-md-6 justify-content-end d-flex">
            <li class="nav-item"><a href="#" class="nav-link px-2 text-muted">Home</a></li>
            <li class="nav-item"><a href="#" class="nav-link px-2 text-muted">Features</a></li>
            <li class="nav-item"><a href="#" class="nav-link px-2 text-muted">Pricing</a></li>
            <li class="nav-item"><a href="#" class="nav-link px-2 text-muted">FAQs</a></li>
            <li class="nav-item"><a href="#" class="nav-link px-2 text-muted">About</a></li>
        </ul>
    </div>
</footer>

<!-- load cookie consent modal on ever site, after the page has loaded -->
<?php require INCLUDE_DIR."/modal_cookie_consent.inc.php" ?>
