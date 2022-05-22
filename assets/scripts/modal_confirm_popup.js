// script for handling the confirm-modal

/**
 * The model
 * @type {Modal}
 */
confirmModal = new bootstrap.Modal(document.getElementById("confirmModal"), {
    backdrop: true,
    keyboard: false,
    focus: true
});

let _redirectUrl;

/**
 * Open the modal and set redirect and inner text
 * @param text The text, which is the content of this confirm modal
 * @param title The title, which is showed in the header of this confirm modal
 * @param redirectUrl The url, to we redirect, if we click confirm.
 */
function openConfirmModal(text, title, redirectUrl) {
    $("#confirmModalHead")[0].textContent = title;
    $("#confirmModalBody")[0].textContent = text;
    _redirectUrl = redirectUrl;
    confirmModal.show();
}

/**
 * Redirect on confirm
 */
function onConfirm() {
    window.location.replace(_redirectUrl);
}