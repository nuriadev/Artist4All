<?php
require_once dirname(__DIR__) . '/vendor/autoload.php';

header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, PATCH, DELETE");
header("Allow: GET, POST, OPTIONS, PUT, PATCH, DELETE"); 

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

define('APP_FOLDER',  dirname(__DIR__));
define('LOG_FOLDER', APP_FOLDER . '/logs');
 
\Artist4all\Artist4all::processRequest();
