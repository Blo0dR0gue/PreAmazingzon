<!-- TODO move to sub dir? -->

<?php
function show_popup(
    $popup_title = "Error occurred",    // TODO both unused
    $popup_text = "While executing the task an error occurred, please retry."
): void
{
    //Load the popup
    require_once "modal_popup_content.inc.php";
    ?>

    <script>
        //Open the popup by default
        showPopup();
    </script>

<?php } ?>