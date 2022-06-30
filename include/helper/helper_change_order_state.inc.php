<?php
// Changes the state of an order.

require_once "../site_php_head.inc.php";

UserController::redirectIfNotAdmin();

if (!isset($_POST["orderId"]) || !is_numeric($_POST["orderId"]) || !isset($_POST["orderStateId"]) || !is_numeric($_POST["orderStateId"])) {
    echo json_encode(array("state" => "error", "msg" => "value error"));
    die();
}

$order = OrderController::getById($_POST["orderId"]);

if (!isset($order)) {
    exit(json_encode(array("state" => "error", "msg" => "order error")));
}

$orderState = OrderStateController::getById($_POST["orderStateId"]);

if (!isset($orderState)) {
    exit(json_encode(array("state" => "error", "msg" => "orderstate error")));
}

$order->setOrderStateId($_POST["orderStateId"]);
try {
    $order = $order->update();
} catch (Exception $e) {
    exit(json_encode(array("state" => "error", "msg" => "update cast error")));
}

if (!isset($order)) {
    exit(json_encode(array("state" => "error", "msg" => "update error")));
}

exit(json_encode(array("state" => "success", "msg" => "done", "orderStateId" => $order->getOrderStateId())));