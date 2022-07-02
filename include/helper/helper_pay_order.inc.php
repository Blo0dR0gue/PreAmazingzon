<?php
// Pays an order.

require_once "../site_php_head.inc.php";

UserController::redirectIfNotLoggedIn();

if (!isset($_POST["orderId"]) || !is_numeric($_POST["orderId"]) || !isset($_POST["userId"]) || !is_numeric($_POST["userId"])) {
    logData("Pay Order", "Value is missing or does not have the correct datatype!", CRITICAL_LOG);
    echo json_encode(array("state" => "error", "msg" => "value error"));
    die();
}

$order = OrderController::getById($_POST["orderId"]);

if (!isset($order)) {
    logData("Pay Order", "Order with id: " . $_POST["orderId"] . " not found!", CRITICAL_LOG);
    exit(json_encode(array("state" => "error", "msg" => "order not found")));
}

$user = UserController::getById($_POST["userId"]);

if (!isset($user)) {
    logData("Pay Order", "User with id: " . $_POST["userId"] . " not found!", CRITICAL_LOG);
    exit(json_encode(array("state" => "error", "msg" => "user not found")));
}

if ($user->getId() != $order->getUserId()) {
    logData("Pay Order", "The order with id: " . $order->getId() . " does not belong to the user with id: " . $user->getId() . "!", CRITICAL_LOG);
    exit(json_encode(array("state" => "error", "msg" => "user id != order user id")));
}

$order->setPaid(true);
try {
    $order = $order->update();
} catch (Exception $e) {
    logData("Pay Order", "Date could not be parsed!", CRITICAL_LOG);
    exit(json_encode(array("state" => "error", "msg" => "update cast error")));
}

if (!isset($order)) {
    logData("Pay Order", "Order with id: " . $_POST["orderId"] . " could not be paid!", CRITICAL_LOG);
    exit(json_encode(array("state" => "error", "msg" => "update error")));
}

logData("Pay Order", "Order with id: " . $_POST["orderId"] . "got paid from user " . $_POST["userId"] . "!");
exit(json_encode(array("state" => "success", "msg" => "done", "orderStateId" => $order->getOrderStateId())));