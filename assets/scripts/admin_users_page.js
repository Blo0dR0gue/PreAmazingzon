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
            //parse the response data
            const response_data = $.parseJSON(result);

            //IF request was successfully
            if (response_data.state === "success") {

                //Update the style elements for the button
                button.classList.toggle("btn-success");
                button.classList.toggle("btn-warning");

                //Update the active text for the table row
                $("td[data-id=" + userId + "]").text(response_data.active ? "Yes" : "No");

                console.log(response_data.msg); //TODO log

                showPopup("Success", "The active state of the user got changed successfully.")
            } else if (response_data.state === "error") {

                console.log(response_data.msg); //TODO log

                showPopup("Error", "An error occurred.")
            }
        }
    });
}