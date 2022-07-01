/**
 * Toggles the activation status of a product.
 */
function onToggleProductActivation(button, productId) {

    //Send AJAX request
    $.ajax({
        url: "../../include/helper/helper_toggle_active_product.inc.php",
        type: "post",
        data: {productId: productId},
        success: function (result) {
            const response_data = $.parseJSON(result);

            //IF request was successfully
            if (response_data.state === "success") {

                button.classList.toggle("btn-success");
                button.classList.toggle("btn-warning");

                $("td[data-id=" + productId + "]").text(response_data.active ? "Yes" : "No");
                console.log(response_data.msg); //TODO log
                showPopup("Success", "The active state of the product got changed successfully.")
            } else if (response_data.state === "error") {
                console.log(response_data.msg); //TODO log
                showPopup("Error", "An error occurred.")
            }
        }
    });
}