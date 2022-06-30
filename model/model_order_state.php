<?php
// TODO Comments

// load required files
require_once(INCLUDE_DIR . "database.inc.php");

class OrderState
{
    // region fields
    private int $id;
    private string $label;
    // endregion

    /**
     * Constructor of OrderState.
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
     * Get an existing OrderState by its id.
     * @param int $id ID of OrderState
     * @return OrderState|null found OrderState
     */
    public static function getById(int $id): ?OrderState
    {
        $stmt = getDB()->prepare("SELECT * FROM orderstate WHERE id = ?;");
        $stmt->bind_param("i", $id);
        if (!$stmt->execute()) {
            return null;
        }

        // get result
        $res = $stmt->get_result();
        if ($res->num_rows === 0) {
            return null;
        }
        $res = $res->fetch_assoc();
        $stmt->close();

        return new OrderState($id, $res["label"]);
    }

    public static function getByName(string $label): ?OrderState
    {
        $stmt = getDB()->prepare("SELECT * FROM orderstate WHERE label = ?;");
        $stmt->bind_param("s", $label);
        if (!$stmt->execute()) {
            return null;
        }

        // get result
        $res = $stmt->get_result();
        if ($res->num_rows === 0) {
            return null;
        }
        $res = $res->fetch_assoc();
        $stmt->close();

        return new OrderState($res["id"], $res["label"]);
    }

    public static function getAll(): array
    {
        $orderStates = [];

        // No need for prepared statement, because we do not use inputs.
        $result = getDB()->query("SELECT id FROM orderstate ORDER BY id;");

        if (!$result) {
            return [];
        }

        $rows = $result->fetch_all(MYSQLI_ASSOC);

        foreach ($rows as $orderState) {
            $orderStates[] = self::getByID($orderState["id"]);
        }

        return $orderStates;
    }

    // endregion

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getLabel(): string
    {
        return $this->label;
    }

}
