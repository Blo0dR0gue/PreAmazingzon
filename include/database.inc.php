<!-- TODO COMMENT-->

<?php
function getDB(): mysqli
{
    static $db;     // single instance of a db connection
    if ($db instanceof MYSQLI) {
        return $db;
    }

    require_once CONFIG_DIR . '/database_config.php';
    $db = new MYSQLI(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_DATABASE, DB_PORT);
    return $db;
}

// TODO close DB?

?>