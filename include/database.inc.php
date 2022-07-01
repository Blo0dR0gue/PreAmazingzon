<?php

/**
 * Gets the database object.
 * @return mysqli
 */
function getDB(): mysqli
{
    static $db;     // single instance of a db connection
    if ($db instanceof MYSQLI) {
        return $db;
    }

    require_once CONFIG_DIR . "database_config.php";

    try {
        $db = new MYSQLI(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_DATABASE, DB_PORT);
    } catch (mysqli_sql_exception $e) {
        //Redirect to error page.
        require_once "paths.inc.php";
        header("LOCATION: " . PAGES_DIR . 'page_error.php');
        die();
    }

    if ($db->connect_errno) {
        //echo $db->connect_error;
        //Redirect to error page.
        require_once "paths.inc.php";
        header("LOCATION: " . PAGES_DIR . 'page_error.php');
        die();
    }

    return $db;
}