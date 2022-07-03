<?php
// redefine DIRECTORY_SEPARATOR
/** Shorter version of DIRECTORY_SEPARATOR */
const DS = DIRECTORY_SEPARATOR;

// define root dirs
$dirs = explode("/", substr($_SERVER["PHP_SELF"], 1));
$path = str_repeat(".." . DS, count($dirs) - 1);

define("ROOT_DIR", empty(ROOT_PATH_OFFSET) ? $path : $path . ROOT_PATH_OFFSET . DS);
define("SERVER_ROOT", $_SERVER["DOCUMENT_ROOT"]);   // TODO unused

// define sub dirs constants
// ./assets
const ASSETS_DIR = ROOT_DIR . "assets" . DS;
const CONFIG_DIR = ASSETS_DIR . "config" . DS;
const SCRIPT_DIR = ASSETS_DIR . "scripts" . DS;
const STYLE_DIR = ASSETS_DIR . "style" . DS;
const INVOICES_DIR = ASSETS_DIR . "invoices" . DS;
const LOG_DIR = ASSETS_DIR . "logs" . DS;
const IMAGE_DIR = ASSETS_DIR . "images" . DS;
// ./assets/image
const IMAGE_LOGO_DIR = IMAGE_DIR . "logo" . DS;
const IMAGE_PRODUCT_DIR = IMAGE_DIR . "products" . DS;

// ./controller
const CONTROLLER_DIR = ROOT_DIR . "controller" . DS;

// ./include
const INCLUDE_DIR = ROOT_DIR . "include" . DS;
const INCLUDE_HELPER_DIR = INCLUDE_DIR . "helper" . DS;
const INCLUDE_TCPDF_DIR = INCLUDE_DIR . "tcpdf" . DS;
const INCLUDE_ADMIN_DIR = INCLUDE_DIR . "admin" . DS;
const INCLUDE_ELEMENTS_DIR = INCLUDE_DIR . "elements" . DS;
const INCLUDE_MODAL_DIR = INCLUDE_DIR . "modal" . DS;

// ./model
const MODEL_DIR = ROOT_DIR . "model" . DS;

// ./pages
const PAGES_DIR = ROOT_DIR . "pages" . DS;
const ADMIN_PAGES_DIR = PAGES_DIR . 'admin' . DS;
const USER_PAGES_DIR = PAGES_DIR . 'user' . DS;