<!-- TODO COMMENT-->

<?php
// load required files
require_once(INCLUDE_DIR . DIRECTORY_SEPARATOR . "database.inc.php");

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
    // endregion

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

}
