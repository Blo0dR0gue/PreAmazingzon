<!-- TODO COMMENT-->

<?php
// load required files
require_once(INCLUDE_DIR . DIRECTORY_SEPARATOR . "database.inc.php");

// TODO implement
class User
{
    // region fields
    private int $id;
    private string $first_name;
    private string $last_name;
    private string $email;
    private string $password_hash;
    private bool $active;
    private int $role_id;
    private int $default_address_id;
    // endregion


    // region getter

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