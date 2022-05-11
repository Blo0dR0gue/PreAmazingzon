<!-- TODO comment -->

<?php
function show_popup(
    $popup_title = "Error occurred",
    $popup_text = "While executing the task an error occurred, please retry."
): void
{ ?>
    <!-- popup modal -->
    <div class="modal fade d-block py-5" tabindex="-1" id="modalPopup">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content rounded-6 shadow">
                <div class="modal-header border-bottom-0">
                    <h4 class="modal-title"><?= $popup_title ?></h4>
                </div>
                <div class="modal-body py-0 text-start">
                    <p><?= $popup_text ?></p>
                </div>
                <div class="modal-footer flex-column border-top-0">
                    <button type="button" class="btn btn-lg btn-warning w-100 mx-0 mb-2" data-dismiss="modal"
                            onclick="closePopup()">Close
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- load js managing modal -->
    <script src="<?= SCRIPT_DIR . DIRECTORY_SEPARATOR . "modal_popup.js" ?>"></script>
<?php } ?>