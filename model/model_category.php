<!-- TODO COMMENT-->

<?php
// load required files
require_once(INCLUDE_DIR . DIRECTORY_SEPARATOR . "database.inc.php");

// TODO implement
class Category
{
    // region fields

    private int $id;
    private string $name;
    private string $description;
    private int $parentID;

    // endregion


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
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @return int
     */
    public function getParentID(): int
    {
        return $this->parentID;
    }

    // endregion

    public function insert(): void
    {
        // TODO
    }

    public function update(): void
    {
        // TODO
    }

    public function delete(): void
    {
        // TODO
    }
}
