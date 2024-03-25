<?php
require_once "autoloader.php";
$connection = new Model();
$conn = $connection->getConn();
$id = isset($_GET['id']) ? $_GET['id'] : null;
$connection->deleteTarea($id);
header("location: lista.php");