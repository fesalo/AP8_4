<?php
require_once "autoloader.php";
$connection = new Model();
$conn = $connection->getConn();
$id = isset($_GET['id']) ? $_GET['id'] : null;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="estilos.css">
</head>
<body>
<table class="greenTable">
    <?= $connection->showDetail($id)?>
</table>
<a href="lista.php">Volver</a>
</body>
</html>
