// TODO comment

$(function () {
    //Check if the delivery address radio select has changed.
    $("#checkoutForm input[type=radio][name='delivery']").on("change", function () {
        //Update the visuals of the selected address
        const dataSet = $("input[name='delivery']:checked").data();
        $("#selectedDeliveryName").text(dataSet["user"]);
        $("#selectedDeliveryStreet").text(dataSet["street"]);
        $("#selectedDeliveryCity").text(dataSet["city"]);
        //Disable the no address text
        $("#noDeliveryText").css("display", "none");
    });
});