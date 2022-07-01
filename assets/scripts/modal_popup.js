// script for handling the popup modal

/**
 * The modal
 * @type {Modal}
 */
popupModal = new bootstrap.Modal(document.getElementById("modalPopup"), {
    backdrop: true,
    keyboard: false,
    focus: true
});

/**
 * Shows the popup modal.
 * @param title The title for the modal (Can also be set via php: $popup_title)
 * @param text The text for the modal  (Can also be set via php: $popup_text)
 */
function showPopup(title, text){
    $("#popupModalHead").text(title);
    $("#popupModalBody").text(text);
    popupModal.show();
}