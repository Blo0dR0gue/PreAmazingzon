<!-- (Dynamic) content for the popup modal (Does not open the popup modal on load) -->
<div class="modal fade" id="modalPopup" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
     aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content rounded-6 shadow">
            <div class="modal-header border-bottom-0">
                <h4 class="modal-title" id="popupModalHead"><?= $popup_title ?? "Title" ?></h4>
            </div>
            <div class="modal-body py-0 text-start">
                <p id="popupModalBody"><?= $popup_text ?? "..." ?></p>
            </div>
            <div class="modal-footer flex-column border-top-0">
                <button type="button" class="btn btn-lg btn-warning w-100 mx-0 mb-2"
                        data-bs-dismiss="modal">Close
                </button>
            </div>
        </div>
    </div>
</div>

<!-- load js managing modal -->
<script src="<?= SCRIPT_DIR . "modal_popup.js" ?>"></script>