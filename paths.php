<!-- TODO COMMENT-->
<!-- TODO remove file from root?-->

<?php
$dirs = explode("/", substr($_SERVER["PHP_SELF"], 1));
$path = str_repeat("..".DIRECTORY_SEPARATOR, count($dirs) - 1);

$ROOT_DIR = $path;

define("SERVER_ROOT", $_SERVER["DOCUMENT_ROOT"]);

define("ASSETS_DIR", $ROOT_DIR . "assets");
const CONFIG_DIR = ASSETS_DIR . DIRECTORY_SEPARATOR . "config";
const FILE_DIR = ASSETS_DIR . DIRECTORY_SEPARATOR . "files";
const IMAGE_DIR = ASSETS_DIR . DIRECTORY_SEPARATOR . "images";
const SCRIPT_DIR = ASSETS_DIR . DIRECTORY_SEPARATOR . "scripts";
const STYLE_DIR = ASSETS_DIR . DIRECTORY_SEPARATOR . "styles";

define("CONTROLLER_DIR", $ROOT_DIR . "controller");

define("INCLUDE_DIR", $ROOT_DIR . "include");

define("MODEL_DIR", $ROOT_DIR . "model");

define("PAGES_DIR", $ROOT_DIR . "pages");

?>