/**
 * Needs to be replaced with an actual pay function.
 * @param elem  The dom element e.g. the button
 * @param orderId   The id of the order
 * @param userId    TODO
 */
function onItemPayBtn(elem, orderId, userId) {

    $.ajax({
        url: "../../include/helper/helper_pay_order.inc.php",
        type: "post",
        data: {
            orderId: orderId,
            userId: userId
        },
        success: function (result) {
            const response_data = $.parseJSON(result);
            const parentElem = elem.parentElement;

            if (response_data.state === "success") {
                //Remove pay button
                parentElem.removeChild(elem);

                let paidBtn = document.createElement("button");
                paidBtn.disabled = true;

                //Create text and add it to the div
                let paidText = document.createTextNode("Paid");
                paidBtn.appendChild(paidText);

                //Add the style classes
                paidBtn.classList.add("col-5", "btn", "btn-success");

                //Add the paid text
                parentElem.appendChild(paidBtn);

                $("#paidTxt"+orderId).text("Paid");

                showPopup("Success", "The order got paid.");
            } else if (response_data.state === "error") {
                showPopup("Error", "An error occurred!");
                console.log(response_data.msg); //TODO log
            }
        }
    });
}