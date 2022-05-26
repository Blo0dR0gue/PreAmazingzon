<!-- TODO COMMENT-->

<?php
// load required files
require_once(INCLUDE_DIR . DS . "database.inc.php");

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
        $stmt = getDB()->prepare("SELECT * from orderstate where id = ?;");
        $stmt->bind_param("i", $id);
        if (!$stmt->execute()) return null;     // TODO ERROR handling

        // get result
        $res = $stmt->get_result();
        if ($res->num_rows === 0) return null;
        $res = $res->fetch_assoc();
        $stmt->close();

        return new OrderState($id, $res["label"]);
    }

    public static function getByName(string $label): ?OrderState
    {
        $stmt = getDB()->prepare("SELECT * from orderstate where label = ?;");
        $stmt->bind_param("s", $label);
        if (!$stmt->execute()) return null;     // TODO ERROR handling

        // get result
        $res = $stmt->get_result();
        if ($res->num_rows === 0) return null;
        $res = $res->fetch_assoc();
        $stmt->close();

        return new OrderState($res["id"], $res["label"]);
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
