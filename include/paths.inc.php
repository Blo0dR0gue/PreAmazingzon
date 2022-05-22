<!-- TODO COMMENT-->

<?php
// redefine DIRECTORY_SEPARATOR
/**
 * Shorter version of DIRECTORY_SEPARATOR
 */
const DS = DIRECTORY_SEPARATOR;

// define root dirs
$dirs = explode("/", substr($_SERVER["PHP_SELF"], 1));
$path = str_repeat(".." . DS, count($dirs) - 1);

define("ROOT_DIR", empty(ROOT_PATH_OFFSET) ? $path : $path . ROOT_PATH_OFFSET . DS);
define("SERVER_ROOT", $_SERVER["DOCUMENT_ROOT"]);

// define sub dirs
const ASSETS_DIR = ROOT_DIR . "assets";

const CONFIG_DIR = ASSETS_DIR . DS . "config";
const FILE_DIR = ASSETS_DIR . DS . "files";
const IMAGE_DIR = ASSETS_DIR . DS . "images";
const IMAGE_LOGO_DIR = IMAGE_DIR . DS . "logo";
const IMAGE_PRODUCT_DIR = IMAGE_DIR . DS . "products";
const SCRIPT_DIR = ASSETS_DIR . DS . "scripts";
const STYLE_DIR = ASSETS_DIR . DS . "style";

const CONTROLLER_DIR = ROOT_DIR . "controller";

const INCLUDE_DIR = ROOT_DIR . "include";
const INCLUDE_HELPER_DIR = INCLUDE_DIR . DS . "helper";

const MODEL_DIR = ROOT_DIR . "model";

const PAGES_DIR = ROOT_DIR . "pages";
const ADMIN_PAGES_DIR = PAGES_DIR . DS . 'admin';
const USER_PAGES_DIR = PAGES_DIR . DS . 'user';