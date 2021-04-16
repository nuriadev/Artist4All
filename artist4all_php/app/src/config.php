<?php
require_once dirname(__DIR__) . '/vendor/autoload.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

define('APP_FOLDER',  dirname(__DIR__));
define('LOG_FOLDER', APP_FOLDER . '/logs');

\Artist4all\Artist4all::processRequest();
