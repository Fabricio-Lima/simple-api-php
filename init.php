<?php
require 'vendor/autoload.php';

use Src\System\DatabaseConnector;

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->safeLoad();

$dbConnection = (new DatabaseConnector())->getConnection();