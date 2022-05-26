<?php

require_once MODEL_DIR . DS . 'model_order_state.php';

class OrderStateController
{

    public static function getByName(string $name): ?OrderState
    {
        return OrderState::getByname($name);
    }

    public static function getById(int $id): ?OrderState
    {
        return OrderState::getById($id);
    }

}