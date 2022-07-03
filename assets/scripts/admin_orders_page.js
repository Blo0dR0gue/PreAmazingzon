/**
 * Changes a status for an order. <br>
 * Requires modal_popup_content.inc.php
 * @param elem The dom element e.g. the button
 * @param orderId   The id for the order
 * @param oldValue  The old order state for reset reasons.
 */
function onOrderStateChange(elem, orderId, oldValue) {
    let value = elem.value;

    // Send AJAX request
    $.ajax({
        url: "../../include/helper/helper_change_order_state.inc.php",
        type: "post",
        data: {
            orderId: orderId,
            orderStateId: value
        },
        success: function (result) {
            // parse the response data
            const response_data = $.parseJSON(result);

            // If the request was successfully
            if (response_data.state === "success") {
                // console.log(response_data.msg);

                // Show success popup
                showPopup("Success", "State changed successfully");
            } else if (response_data.state === "error") {
                // Show error popup
                showPopup("Error", "An error occurred! See log files for more information.");

                // Reset the select element
                elem.value = oldValue;
            }
        }
    });
}