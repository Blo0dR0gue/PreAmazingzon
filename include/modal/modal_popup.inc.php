<?php
//Load the popup
require_once "modal_popup_content.inc.php";

/**
 * Opens the popup modal
 * @param string $popup_title The title
 * @param string $popup_text The text
 * @return void
 */
function show_popup(
    string $popup_title = "Error occurred",
    string $popup_text = "While executing the task an error occurred, please retry."
): void
{
    ?>

    <script>
        //Open the popup by default
        showPopup("<?=$popup_title?>", "<?=$popup_text?>");
    </script>

<?php } ?>