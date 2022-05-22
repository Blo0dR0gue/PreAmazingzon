$(function(){

    $("#checkoutForm input[type=radio][name='delivery']").on("change", function() {
        const dataSet = $("input[name='delivery']:checked").data();
        $("#selectedDeliveryName").text(dataSet["user"]);
        $("#selectedDeliveryStreet").text(dataSet["street"]);
        $("#selectedDeliveryCity").text(dataSet["city"]);
        $("#noDeliveryText").css("display", "none");
    });

});