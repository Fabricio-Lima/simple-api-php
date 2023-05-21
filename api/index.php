<?php
require "../bootstrap.php";

use Src\Controllers\ClubController;

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: OPTIONS,GET,POST,PUT,DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = explode( '/', $uri );
$requestResource = $uri[2];

if ($uri[1] !== 'clube') {
  if($uri[1] !== 'clubes'){
    header("HTTP/1.1 404 Not Found");
    exit();
  }
}

if ($uri[1] == 'clubes' and $requestResource !== 'consumir') {
    header("HTTP/1.1 404 Not Found");
    exit();
}

$requestMethod = $_SERVER["REQUEST_METHOD"];

$controller = new ClubController($dbConnection, $requestMethod, $requestResource);
$controller->processRequest();