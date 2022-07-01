<?php
// Pays an order.

require_once "../site_php_head.inc.php";

UserController::redirectIfNotLoggedIn();

if (!isset($_POST["orderId"]) || !is_numeric($_POST["orderId"]) || !isset($_POST["userId"]) || !is_numeric($_POST["userId"])) {
    echo json_encode(array("state" => "error", "msg" => "value error"));
    die();
}

$order = OrderController::getById($_POST["orderId"]);

if (!isset($order)) {
    exit(json_encode(array("state" => "error", "msg" => "order not found")));
}

$user = UserController::getById($_POST["userId"]);

if (!isset($user)) {
    exit(json_encode(array("state" => "error", "msg" => "user not found")));
}

if ($user->getId() != $order->getUserId()) {
    exit(json_encode(array("state" => "error", "msg" => "user id != order id")));
}

$order->setPaid(true);
try {
    $order = $order->update();
} catch (Exception $e) {
    exit(json_encode(array("state" => "error", "msg" => "update cast error")));
}

if (!isset($order)) {
    exit(json_encode(array("state" => "error", "msg" => "update error")));
}

exit(json_encode(array("state" => "success", "msg" => "done", "orderStateId" => $order->getOrderStateId())));