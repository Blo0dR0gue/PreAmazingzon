function onToggleUserActivation(button, userId) {

    $.ajax({
        url: "../../include/helper/helper_toggle_user.inc.php",
        type: "post",
        data: {userId: userId},
        success: function (result) {
            const response_data = $.parseJSON(result);

            if (response_data.msg === "done") {

                button.classList.toggle("btn-success");
                button.classList.toggle("btn-warning");

                $("td[data-id=" + userId + "]").text(response_data.active ? "Yes" : "No");
            }
            //TODO error handling
        }
    });
}