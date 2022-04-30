<?php
$dirs = explode("/", substr($_SERVER['PHP_SELF'], 1));
$path = str_repeat("../", count($dirs) - 1);

$ROOT_DIR = $path;

define('SERVER_ROOT', $_SERVER['DOCUMENT_ROOT']);

define("ASSETS_DIR", $ROOT_DIR . "assets");
const CONFIG_DIR = ASSETS_DIR . '/config';
const FILE_DIR = ASSETS_DIR . '/files';
const IMAGE_DIR = ASSETS_DIR . '/images';
const SCRIPT_DIR = ASSETS_DIR . '/scripts';
const STYLE_DIR = ASSETS_DIR . '/styles';

define("CONTROLLER_DIR", $ROOT_DIR . 'controller');

define("INCLUDE_DIR", $ROOT_DIR . 'include');

define("MODEL_DIR", $ROOT_DIR . 'model');

define("PAGES_DIR", $ROOT_DIR . 'pages');