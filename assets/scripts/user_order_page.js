/**
 * Needs to be replaced with an actual pay function.
 * @param elem
 * @param orderId
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

            if (response_data.state === "success") {
                //TODO delete btn and show paid
                console.log(response_data.msg); //TODO show modal?
            } else if (response_data.state === "error") {
                console.log(response_data.msg); //TODO show modal?
            }
        }
    });

}