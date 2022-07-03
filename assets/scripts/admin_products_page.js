/**
 * Toggles the activation status of a product.
 */
function onToggleProductActivation(button, productId) {

    // Send AJAX request
    $.ajax({
        url: "../../include/helper/helper_toggle_active_product.inc.php",
        type: "post",
        data: {productId: productId},
        success: function (result) {
            // parse the response data
            const response_data = $.parseJSON(result);

            // IF request was successfully
            if (response_data.state === "success") {

                button.classList.toggle("btn-success");
                button.classList.toggle("btn-warning");

                const child = button.querySelector("#activeBtnImg" + productId);

                child.classList.toggle("fa-toggle-on");
                child.classList.toggle("fa-toggle-off");

                $("td[data-id=" + productId + "]").text(response_data.active ? "Yes" : "No");
                // console.log(response_data.msg);

                showPopup("Success", "The active state of the product got changed successfully.")
            } else if (response_data.state === "error") {
                // console.log(response_data.msg);

                showPopup("Error", "An error occurred! See log files for more information.")
            }
        }
    });
}