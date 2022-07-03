<!-- Adds a dynamic pagination to a site -->

<?php if (isset($page) && isset($totalPages) && $page <= $totalPages): ?>

    <?php
    //Create the get parameters for the url.
    if (isset($_GET) && !empty($_GET) && !(count($_GET) == 2 && isset($_GET["page"]) && isset($_GET["message"])) && !(count($_GET) == 1 && (isset($_GET["page"]) || isset($_GET["message"])))) {
        $urlExtend = "";
        foreach ($_GET as $key => $value) {
            //Don't add the page or the message get parameter again to the url.
            if ($key != "page" && $key != "message") {
                if ($urlExtend == "") {
                    $urlExtend = $urlExtend . "?" . $key . "=" . $value;
                } else {
                    $urlExtend = $urlExtend . "&" . $key . "=" . $value;
                }
            }
        }
        $urlExtend = $urlExtend . "&page=";
    } else {
        $urlExtend = "?page=";
    }
    ?>

    <nav aria-label="Page navigation example mt-5">
        <ul class="pagination justify-content-center mb-5">

            <!-- Add the previous button -->
            <li class="page-item <?php if ($page <= 1) { echo 'disabled'; } ?>">
                <a class="page-link"
                   href="<?php if ($page <= 1) { echo '#'; } else { echo $urlExtend . $page - 1; } ?>">Previous</a>
            </li>

            <?php
            //Calculate the ranges for the showed pagination links
            $start_x = $page - PAGINATION_RANGE;
            $end_x = $page + PAGINATION_RANGE;
            ?>

            <!-- Add the first pagination item -->
            <?php if ($start_x > 1): ?>
                <li class="page-item">
                    <a class="page-link"
                       href="<?= $urlExtend . "1" ?>>">1</a>
                </li>

                <li class="page-item disabled">
                    <a class="page-link">...</a>
                </li>
            <?php endif; ?>

            <!-- loop to show links to range of pages around current page -->
            <?php for ($x = $start_x; $x < ($end_x + 1); $x++):
                // if it's a valid page number...
                if (($x > 0) && ($x <= $totalPages)): ?>
                    <li class="page-item <?php if ($page == $x) { echo 'active'; } ?>">
                        <a class="page-link"
                           <?php if ($page != $x): ?>href="<?= $urlExtend . $x; ?>" <?php endif; ?>> <?= $x; ?> </a>
                    </li>
                <?php endif;
            endfor; ?>

            <!-- Add the last pagination item -->
            <?php if ($end_x < $totalPages && $end_x != $start_x): ?>
                <li class="page-item disabled">
                    <a class="page-link">...</a>
                </li>

                <li class="page-item">
                    <a class="page-link"
                       href="<?= $urlExtend . $totalPages ?>"><?= $totalPages ?></a>
                </li>
            <?php endif; ?>

            <!-- Add the next button -->
            <li class=" page-item <?php if ($page >= $totalPages) { echo 'disabled'; } ?>">
                <a class="page-link"
                   href="<?php if ($page >= $totalPages) { echo '#'; } else { echo $urlExtend . $page + 1; } ?>">Next</a>
            </li>
        </ul>
    </nav>
<?php endif; ?>