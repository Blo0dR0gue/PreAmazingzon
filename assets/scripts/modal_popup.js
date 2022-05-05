// TODO COMMENT

// display modal
const popupModal = new bootstrap.Modal(document.getElementById("modalPopup"), {
    backdrop: true,
    keyboard: false,
    focus: true
});
popupModal.show();

// region button handler
/**
 * Button handler for "close"-button.
 * Hides modal and saves consent.
 */
function closePopup() {
    popupModal.hide();
}

$('#modalPopup').on('hidden.bs.modal', function () {
    // remove remaining back-drop
    $('#modalPopup').remove()
    $(document.body).removeClass("modal-open");
})
// endregion