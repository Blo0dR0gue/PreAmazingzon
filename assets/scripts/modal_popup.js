// script for handling the popup modal

/**
 * By default, show the modal if it was loaded on the page.
 * @type {Modal}
 */
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
function closePopup()
{
    popupModal.hide();
}

/**
 * Event-Listener for removing the modal backdrop onClose.
 * In its default state this stays and prevents the user from interacting with the page.
 */
$("#modalPopup").on("hidden.bs.modal", function ()
{
    // remove remaining back-drop
    $("#modalPopup").remove()
    $(document.body).removeClass("modal-open");
})
// endregion