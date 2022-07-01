/**
 * Toggles the activation status of a user.
 */
function onToggleUserActivation(button, userId) {

    //Send AJAX request
    $.ajax({
        url: "../../include/helper/helper_toggle_user.inc.php",
        type: "post",
        data: {userId: userId},
        success: function (result) {
            const response_data = $.parseJSON(result);

            //IF request was successfully
            if (response_data.state === "success") {

                button.classList.toggle("btn-success");
                button.classList.toggle("btn-warning");

                $("td[data-id=" + userId + "]").text(response_data.active ? "Yes" : "No");
                console.log(response_data.msg); //TODO show modal?
            } else if (response_data.state === "error") {
                console.log(response_data.msg); //TODO show modal?
            }
        }
    });
}