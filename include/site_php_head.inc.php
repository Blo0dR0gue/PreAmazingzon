<!-- php head file included in all necessary files at the beginning -->
<!-- TODO COMMENT -->

<?php
// php error display
// TODO work out how reporting works
ini_set('display_errors', 'On');
ini_set('display_startup_errors', 'On');
error_reporting(-1);

// includes
// TODO
$dirs = explode("/", substr($_SERVER['PHP_SELF'], 1));
$path = str_repeat("../", (count($dirs) - 1) > 1 ? (count($dirs) - 1) : 0);
require_once $path . "paths.php"

// session
//TODO
?>