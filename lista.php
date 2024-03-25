<?php
require_once "autoloader.php";
session_start();
$connection = new Model();
$conn = $connection->getConn();
$orderBy = isset($_GET['orderBy']) ? $_GET['orderBy'] : "titulo";
$order = isset($_GET['order']) ? $_GET['order'] : "asc";
$query = "SELECT * From tareas;";
$result = mysqli_query($conn, $query);
$rows = mysqli_num_rows($result);
$pages = ceil($rows / 10);
$page = $connection->getCurrentPage();
if ($page <1){
    $page = 1;
}
if ($page > $pages-1){
    $page = $pages-1;
}
$order = $connection->getCurrentOrder()['order'];
$orderBy = $connection->getCurrentOrder()['orderBy'];
$_SESSION['order'] = $order;
$_SESSION['orderBy'] = $orderBy;
$_SESSION['page'] = $page;
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
    <p><a href="nueva.php">Añadir tarea</a></p>
    <table class="greenTable">
        <thead>
            <tr>
                <th>ID</th>
                <th><a href="lista.php?page=<?=$page?>&orderBy=titulo&order=<?=$order?>">Título</a></th>
                <th><a href='lista.php?page=<?=$page?>&orderBy=fecha_vencimiento&order=<?=$order?>'>Vencimiento</a></th>
                <th colspan="2">Acciones</th>
            </tr>
        </thead>
        <tfoot>
            <tr>
                <td colspan="5">
                    &nbsp;
                </td>
            </tr>
        </tfoot>
        <tbody>
            <?= $connection->showAllTasks($orderBy, $order, $page); ?>
        </tbody>
    </table>
    <?= $connection->showNavigation($pages,$page,$order, $orderBy); ?>
    <?php session_destroy() ?>
</body>

</html>