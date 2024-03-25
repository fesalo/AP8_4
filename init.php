<?php
require_once "autoloader.php";

$connection = new Model();
$conn = $connection->getConn();
$connection->init();