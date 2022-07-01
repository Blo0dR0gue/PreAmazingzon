<?php

//Add the order state model
require_once MODEL_DIR . 'model_order_state.php';

class OrderStateController
{

    /**
     * Gets an {@link OrderState} by its name
     * @param string $name The name of the state.
     * @return OrderState|null The {@link OrderState} object or null, if not found.
     */
    public static function getByName(string $name): ?OrderState
    {
        return OrderState::getByname($name);
    }

    /**
     * Gets an {@link OrderState} by its id
     * @param int $id The id of the state.
     * @return OrderState|null The {@link OrderState} object or null, if not found.
     */
    public static function getById(int $id): ?OrderState
    {
        return OrderState::getById($id);
    }

    /**
     * Gets all {@link OrderState} objects from the database.
     * @return array An array with all {@link OrderState} objects.
     */
    public static function getAll(): array
    {
        return OrderState::getAll();
    }

}