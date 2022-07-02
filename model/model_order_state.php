<?php

//Add databse
require_once(INCLUDE_DIR . "database.inc.php");

class OrderState
{
    // region fields
    private int $id;
    private string $label;
    // endregion

    /**
     * Constructor of {@link OrderState}.
     * @param int $id
     * @param string $label
     */
    public function __construct(int $id, string $label)
    {
        $this->id = $id;
        $this->label = $label;
    }

    // region getter

    /**
     * Get an existing {@link OrderState} by its label.
     * @param string $label Label of {@link OrderState}.
     * @return OrderState|null The {@link OrderState} or null, if not found.
     */
    public static function getByName(string $label): ?OrderState
    {
        $stmt = getDB()->prepare("SELECT * FROM orderstate WHERE label = ?;");
        $stmt->bind_param("s", $label);
        if (!$stmt->execute()) {
            logData("Order State Model", "Query execute error! (get)", CRITICAL_LOG);
            return null;
        }

        // get result
        $res = $stmt->get_result();
        if ($res->num_rows === 0) {
            logData("Order State Model", "No items were found for label: " . $label, NOTICE_LOG);
            return null;
        }
        $res = $res->fetch_assoc();
        $stmt->close();

        return new OrderState($res["id"], $res["label"]);
    }

    /**
     * Gets all stored {@link OrderState}s
     * @return array An array with all {@link OrderState} objects stored inside the database.
     */
    public static function getAll(): array
    {
        $orderStates = [];

        // No need for prepared statement, because we do not use inputs.
        $result = getDB()->query("SELECT id FROM orderstate ORDER BY id;");

        if (!$result) {
            logData("Order State Model", "No items were found", NOTICE_LOG);
            return [];
        }

        $rows = $result->fetch_all(MYSQLI_ASSOC);

        foreach ($rows as $orderState) {
            $orderStates[] = self::getByID($orderState["id"]);
        }

        return $orderStates;
    }

    /**
     * Get an existing {@link OrderState} by its id.
     * @param int $id ID of {@link OrderState}
     * @return OrderState|null The {@link OrderState} or null, if not found.
     */
    public static function getById(int $id): ?OrderState
    {
        $stmt = getDB()->prepare("SELECT * FROM orderstate WHERE id = ?;");
        $stmt->bind_param("i", $id);
        if (!$stmt->execute()) {
            logData("Order State Model", "Query execute error! (get)", CRITICAL_LOG);
            return null;
        }

        // get result
        $res = $stmt->get_result();
        if ($res->num_rows === 0) {
            logData("Order State Model", "No items were found for id: " . $id, NOTICE_LOG);
            return null;
        }
        $res = $res->fetch_assoc();
        $stmt->close();

        return new OrderState($id, $res["label"]);
    }

    /**
     * Gets the id of the {@link OrderState}.
     * @return int The id.
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Gets the label of the {@link OrderState}
     * @return string The label.
     */
    public function getLabel(): string
    {
        return $this->label;
    }

    // endregion

}
