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
// in this var you will get the absolute file path of the current file
$current_file_path = dirname(__FILE__);
$root_file_path = $current_file_path . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR;

//Load paths
require_once $root_file_path . "paths.php";

// session
session_start()
// TODO implement session use
?>