<!--Adds a dynamic pagination to a site-->
<?php if (isset($page) && isset($totalPages)): ?>
    <nav aria-label="Page navigation example mt-5">
        <ul class="pagination justify-content-center">

            <li class="page-item <?php if ($page <= 1) {
                echo 'disabled';
            } ?>">
                <a class="page-link"
                   href="<?php if ($page <= 1) {
                       echo '#';
                   } else {
                       echo "?page=" . $page - 1;
                   } ?>">Previous</a>
            </li>

            <li class="page-item <?php if ($page == 1) {
                echo 'active';
            } ?>">
                <a class="page-link" href="index.php?page=1"></a>
            </li>

            <?php
            //Calculate the ranges for the showed pagination links
            if (($page - PAGINATION_RANGE) <= 1) {
                $start_x = 1;
                $end_x = 5;
            } else if ($page >= ($totalPages - PAGINATION_RANGE)) {
                $start_x = $totalPages - 4;
                $end_x = $totalPages;
            } else {
                $start_x = $page - PAGINATION_RANGE;
                $end_x = $page + PAGINATION_RANGE;
            }
            ?>

            <?php
            // loop to show links to range of pages around current page
            for ($x = $start_x;
                 $x < ($end_x + 1);
                 $x++):
                // if it's a valid page number...
                if (($x > 0) && ($x <= $totalPages)):
                    ?>
                    <li class="page-item <?php if ($page == $x) {
                        echo 'active';
                    } ?>">
                        <a class="page-link"
                           <?php if ($page != $x): ?>href="?page=<?= $x; ?>" <?php endif; ?>> <?= $x; ?> </a>
                    </li>
                <?php
                endif;
            endfor;
            ?>

            <li class="page-item <?php if ($page == $totalPages) {
                echo 'active';
            } ?>">
                <a class="page-link" href="?page=<?= $totalPages ?></a>
            </li>

            <li class=" page-item <?php if ($page >= $totalPages) {
                    echo 'disabled';
                } ?>">
                <a class="page-link"
                   href="<?php if ($page >= $totalPages) {
                       echo '#';
                   } else {
                       echo "?page=" . $page + 1;
                   } ?>">Next</a>
            </li>

        </ul>
    </nav>
<?php endif; ?>