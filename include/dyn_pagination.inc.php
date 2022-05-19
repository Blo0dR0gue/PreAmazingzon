<!--Adds a dynamic pagination to a site-->
<!--TODO comment -->

<?php if (isset($page) && isset($totalPages) && $page <= $totalPages): ?>

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

            <?php
            //Calculate the ranges for the showed pagination links
            $start_x = $page - PAGINATION_RANGE;
            $end_x = $page + PAGINATION_RANGE;
            ?>

            <?php if ($start_x > 1): ?>

                <li class="page-item">
                    <a class="page-link"
                       href="?page=1">1</a>
                </li>

                <li class="page-item disabled">
                    <a class="page-link">...</a>
                </li>

            <?php endif; ?>

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

            <?php if ($end_x < $totalPages && $end_x != $start_x): ?>

                <li class="page-item disabled">
                    <a class="page-link">...</a>
                </li>

                <li class="page-item">
                    <a class="page-link"
                       href="?page=<?= $totalPages ?>"><?= $totalPages ?></a>
                </li>

            <?php endif; ?>

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