<!-- php head file included in all necessary files at the beginning -->
<!-- TODO COMMENT -->

<?php
// php error display
// TODO work out how reporting works; disable for prod?
ini_set('display_errors', 'On');
ini_set('display_startup_errors', 'On');
error_reporting(-1);

// includes
// TODO
// in this var you will get the absolute file path of the current file
$current_file_path = __DIR__;
$root_file_path = $current_file_path . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR;

//Load default config
require_once $root_file_path . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'config.php';
//Load paths
require_once $root_file_path . DIRECTORY_SEPARATOR . 'include' . DIRECTORY_SEPARATOR . "paths.inc.php";

// session
session_start()
// TODO implement session use
?>