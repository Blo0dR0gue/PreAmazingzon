// script for handling the popup modal

/**
 * By default, show the modal if it was loaded on the page.
 * @type {Modal}
 */
confirmModal = new bootstrap.Modal(document.getElementById("confirmModal"), {
    backdrop: true,
    keyboard: false,
    focus: true
});

let _redirectUrl;

function openConfirmModal(text, redirectUrl){
    $("#confirmModalBody")[0].textContent = text;
    _redirectUrl = redirectUrl;
    confirmModal.show();
}

function onConfirm(){
    window.location.replace(_redirectUrl);
}