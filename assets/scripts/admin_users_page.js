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

                const child = button.querySelector("#activeBtnImg"+userId);

                child.classList.toggle("fa-toggle-on");
                child.classList.toggle("fa-toggle-off");

                //Update the active text for the table row
                $("td[data-activeUserId=" + userId + "]").text(response_data.active ? "Yes" : "No");

                //console.log(response_data.msg);

                showPopup("Success", "The active state of the user got changed successfully.")
            } else if (response_data.state === "error") {

                //console.log(response_data.msg);

                showPopup("Error", "An error occurred! See log files for more information.")
            }
        }
    });
}

/**
 * Toggles the user admin role status
 * @param btn
 * @param userId
 */
function onToggleUserRole(btn, userId){

    //Send AJAX request
    $.ajax({
        url: "../../include/helper/helper_toggle_user_role.inc.php",
        type: "post",
        data: {userId: userId},
        success: function (result) {
            //parse the response data
            const response_data = $.parseJSON(result);

            //IF request was successfully
            if (response_data.state === "success") {

                //Update the style elements for the button
                btn.classList.toggle("btn-success");
                btn.classList.toggle("btn-warning");

                const child = btn.querySelector("#adminBtnImg"+userId);

                child.classList.toggle("fa-toggle-on");
                child.classList.toggle("fa-toggle-off");

                //Update the active text for the table row
                $("td[data-roleUserId=" + userId + "]").text(response_data.admin ? response_data.adminRoleName : response_data.defaultRoleName);

                //console.log(response_data.msg);

                showPopup("Success", "The role of the user got changed.")
            } else if (response_data.state === "error") {

                //console.log(response_data.msg);

                showPopup("Error", "An error occurred! See log files for more information.")
            }
        }
    });
}