// script for handling the address-modal

/**
 * The model
 * @type {Modal}
 */
addressModal = new bootstrap.Modal(document.getElementById("addressModal"), {
    backdrop: true,
    keyboard: false,
    focus: true
});

/**
 * Open the modal and set redirect and inner text
 * @param addressId The id of the address, which gets edited. Leaf it blank to create a new address.
 * @param zip The zip code. Leaf it blank to create a new address.
 * @param city The city name. Leaf it blank to create a new address.
 * @param street The street name. Leaf it blank to create a new address.
 * @param number The street number. Leaf it blank to create a new address.
 */
function openAddressModal(addressId = -1, zip = "", city = "", street = "", number = "") {
    //Set values
    $("#addressId").val(addressId);
    $("#zip").val(zip);
    $("#city").val(city);
    $("#street").val(street);
    $("#number").val(number);

    addressModal.show();
}

/**
 * Redirect on confirm
 */
function onConfirm() {
    window.location.replace(_redirectUrl);
}