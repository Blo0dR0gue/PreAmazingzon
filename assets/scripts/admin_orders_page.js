/**
 * Changes a status for an order. <br>
 * Requires modal_popup_content.inc.php
 * @param elem The dom element e.g. the button
 * @param orderId   The id for the order
 * @param oldValue  The old order state for reset reasons.
 */
function onOrderStateChange(elem, orderId, oldValue) {
    let value = elem.value;

    $.ajax({
        url: "../../include/helper/helper_change_order_state.inc.php",
        type: "post",
        data: {
            orderId: orderId,
            orderStateId: value
        },
        success: function (result) {
            const response_data = $.parseJSON(result);

            if (response_data.state === "success") {
                console.log(response_data.msg); //TODO log?
                //Requires modal_popup_content.inc.php
                showPopup("Success", "State changed successfully");
            } else if (response_data.state === "error") {
                //Requires modal_popup_content.inc.php
                showPopup("Error", "An error occurred.");
                //TODO log error to file?
                //Reset the select element
                elem.value = oldValue;
            }
        }
    });
}