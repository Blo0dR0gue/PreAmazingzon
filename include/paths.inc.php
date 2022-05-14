<!-- TODO COMMENT-->

<?php
$dirs = explode("/", substr($_SERVER["PHP_SELF"], 1));
$path = str_repeat(".." . DIRECTORY_SEPARATOR, count($dirs) - 1);

// define root dirs
define("ROOT_DIR", empty(ROOT_PATH_OFFSET) ? $path : $path . ROOT_PATH_OFFSET . DIRECTORY_SEPARATOR);

define("SERVER_ROOT", $_SERVER["DOCUMENT_ROOT"]);

// define sub dirs
const ASSETS_DIR = ROOT_DIR . "assets";

const CONFIG_DIR = ASSETS_DIR . DIRECTORY_SEPARATOR . "config";
const FILE_DIR = ASSETS_DIR . DIRECTORY_SEPARATOR . "files";
const IMAGE_DIR = ASSETS_DIR . DIRECTORY_SEPARATOR . "images";
const IMAGE_LOGO_DIR = IMAGE_DIR . DIRECTORY_SEPARATOR . "logo";
const IMAGE_PRODUCT_DIR = IMAGE_DIR . DIRECTORY_SEPARATOR . "products";
const SCRIPT_DIR = ASSETS_DIR . DIRECTORY_SEPARATOR . "scripts";
const STYLE_DIR = ASSETS_DIR . DIRECTORY_SEPARATOR . "style";

const CONTROLLER_DIR = ROOT_DIR . "controller";

const INCLUDE_DIR = ROOT_DIR . "include";
const INCLUDE_HELPER_DIR = INCLUDE_DIR . DIRECTORY_SEPARATOR . "helper";

const MODEL_DIR = ROOT_DIR . "model";

const PAGES_DIR = ROOT_DIR . "pages";
const ADMIN_PAGES_DIR = PAGES_DIR . DIRECTORY_SEPARATOR . 'admin';

?>