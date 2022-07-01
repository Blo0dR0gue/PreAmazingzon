<?php
// Changes the state of an order.

require_once "../site_php_head.inc.php";

UserController::redirectIfNotAdmin();

if (!isset($_POST["orderId"]) || !is_numeric($_POST["orderId"]) || !isset($_POST["orderStateId"]) || !is_numeric($_POST["orderStateId"])) {
    echo json_encode(array("state" => "error", "msg" => "value error"));
    logData("Change Order State", "Value is missing or does not have the correct datatype!", LOG_LVL_CRITICAL);
    die();
}

$order = OrderController::getById($_POST["orderId"]);

if (!isset($order)) {
    logData("Change Order State", "Order with id: " . $_POST["orderId"] . " not found!", LOG_LVL_CRITICAL);
    exit(json_encode(array("state" => "error", "msg" => "order error")));
}

$orderState = OrderStateController::getById($_POST["orderStateId"]);

if (!isset($orderState)) {
    logData("Change Order State", "Order State with id: " . $_POST["orderStateId"] . " not found!", LOG_LVL_CRITICAL);
    exit(json_encode(array("state" => "error", "msg" => "orderstate error")));
}

$order->setOrderStateId($_POST["orderStateId"]);
try {
    $order = $order->update();
} catch (Exception $e) {
    logData("Change Order State", "Date could not be parsed!", LOG_LVL_CRITICAL);
    exit(json_encode(array("state" => "error", "msg" => "update cast error")));
}

if (!isset($order)) {
    logData("Change Order State", "Order " . $order->getId() . " could not be updated!", LOG_LVL_CRITICAL);
    exit(json_encode(array("state" => "error", "msg" => "update error")));
}

exit(json_encode(array("state" => "success", "msg" => "done", "orderStateId" => $order->getOrderStateId())));