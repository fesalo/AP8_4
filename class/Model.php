<?php
class Model extends Connection
{
    protected $pages;

    public function importar()
    {
        $tareas = fopen("tareas.csv", "r") or die("Unable to open file!");
        while (!feof($tareas)) {
            $data = fgetcsv($tareas);
            $query = "INSERT INTO tareas (titulo, descripcion, fecha_creacion, fecha_vencimiento)
             VALUES ('$data[1]', '$data[2]', '$data[3]', '$data[4]');";
            $result = mysqli_query($this->conn, $query);
        }
    }

    public function deleteList()
    {
        $query = "DELETE FROM tareas;";
        $result = mysqli_query($this->conn, $query);
    }

    public function init()
    {
        $this->deleteList();
        $query = "SELECT COUNT(*) FROM tareas;";
        $result = mysqli_query($this->conn, $query)->fetch_row()[0];
        if ($result == 0) {
            $this->importar();
        }
    }

    public function getAllTasks($orderBy, $order)
    {
        $query = "SELECT * From tareas;";
        $result = mysqli_query($this->conn, $query);
        $page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
        $start = ($page - 1) * $this->limit;
        $query2 = "SELECT * from tareas order by $orderBy $order limit $start, $this->limit";
        $result2 = mysqli_query($this->conn, $query2);
        $tasks = [];
        while ($row = $result2->fetch_assoc()) {
            $tasks[] = $row;
        }
        $result->close();
        return $tasks;
    } /* -> que obtiene todos los registros de la tabla "tareas". */

    public function showAllTasks($orderBy, $order)
    {
        $tasks = $this->getAllTasks($orderBy, $order);
        $table = "";
        foreach ($tasks as $task) {
            $table .= "<tr>";
            $table .= "<td>" . $task['id'] . "</td>";
            $table .= "<td><a href='detalle.php?id=" . $task['id'] . "'>" . $task['titulo'] . "</a></td>";
            $table .= "<td>" . date("d/m/Y", strtotime($task['fecha_vencimiento'])) . "</td>";
            $table .= "<td><a href='modifica.php?id=" . $task['id'] . "'><img src='img/edit_icon.png' width='25'></a></td>";
            $table .= "<td><a href='borrar.php?id=" . $task['id'] . "'><img src='img/del_icon.png' width='25'></a></td>";
            $table .= "</tr>";
        }
        echo $table;
    }/*  -> que muestra todas las tareas. Ten en cuenta 
    que la fecha de vencimiento debe estar en formato DD/MM/YYYY */

    public function getDetail($id)
    {
        $query = "SELECT * From tareas where id = $id;";
        $result = mysqli_query($this->conn, $query);
        $task = [];
        $task = $result->fetch_assoc();
        $result->close();
        return $task;
    }

    public function showDetail($id)
    {
        $task = $this->getDetail($id);
        $table = "<thead>";
        $table .= "<tr>";
        $table .= "<th>" . $task['titulo'] . "</th>";
        $table .= "</tr>";
        $table .= "</thead>";
        $table .= "<tfoot>";
        $table .= "<tr>";
        $table .= "<td>La tarea 6 vence en: " . $task['fecha_vencimiento'] . "</td>";
        $table .= "</tr>";
        $table .= "</tfoot>";
        $table .= "<tbody>";
        $table .= "<tr>";
        $table .= "<td>fecha de creación: " . $task['fecha_creacion'] . "</td>";
        $table .= "</tr>";
        $table .= "<tr>";
        $table .= "<td>descripción: " . $task['descripcion'] . "</td>";
        $table .= "</tr>";
        $table .= "</tbody>";
        return $table;
    }

    public function addTarea($data)
    {
        $titulo = $data["titulo"];
        $descripcion = $data["descripcion"];
        $fecha_vencimiento = $data["fecha_vencimiento"];
        $query = "INSERT INTO tareas (titulo, descripcion, fecha_creacion, fecha_vencimiento )
        values ('$titulo', '$descripcion', curdate(), '$fecha_vencimiento') ;";
        mysqli_query($this->conn, $query);
    } /* -> que reciba la información de POST para crear un nuevo registro en la tabla 
    "tareas" de base de datos. El dato de la fecha de creación, lo debe dar el servidor.
     Despues de la inserción, redirige a la página de listado */

    public function updateTarea($data, $id)
    {
        $titulo = $data["titulo"];
        $descripcion = $data["descripcion"];
        $fecha_vencimiento = date("Y-m-d", strtotime($data['fecha_vencimiento']));
        $query = "UPDATE tareas
        set titulo = '$titulo',
        descripcion = '$descripcion',
        fecha_vencimiento = '$fecha_vencimiento'
        where id = $id;";
        mysqli_query($this->conn, $query);
    } /* de forma que modifique la tarea, pero solo el título, la descripción y la fecha de vencimiento. */

    public function deleteTarea($id)
    {
        $query = "delete from tareas where id = $id;";
        mysqli_query($this->conn, $query);
    }

    public function showNavigation($pages, $page, $order, $orderBy)
    {
        $navigator = "<table class='greenTable' style='width: 30%'><tr>";
        $navigator .= "<td><a href='lista.php?page=1&order=$order&orderBy=$orderBy'> << </a></td>";
        $navigator .= "<td><a href='lista.php?page=" .  $page - 1 . "&order=$order&orderBy=$orderBy'> < </a></td>";
        for ($i = 1; $i <= $pages; $i++) {
            $navigator .= "<td><a href='lista.php?page=$i&order=$order&orderBy=$orderBy'> $i </a></td>";
        }
        $last = $i - 1;
        $navigator .= "<td><a href='lista.php?page=" . $page + 1 . "&order=$order&orderBy=$orderBy'> > </a></td>";
        $navigator .= "<td><a href='lista.php?page=$last&order=$order&orderBy=$orderBy'> >> </a></td></tr></table>";
        return $navigator;
    } /* -> para mostrar un menú de navegación por páginas, en el que, además de números de página, 
    debes incorporar los botones "primera", "última", "anterior" y "posterior". Se debe remarcar la página actual. */

    public function showOrderAction($order)
    {
    } /* -> para crear el enlaces de cambio de ordenación en cada cabecera de columna, de forma que 
    se pueda ordenar por título o fecha de vencimiento */

    public function getCurrentPage()
    {

        if (isset($_GET['page']) && $_GET['page'] > 0) {
            return $_GET['page'];
        } elseif (isset($_SESSION['page']) && $_SESSION['page'] > 0) {
            return $_SESSION['page'];
        } else {
            return 1;
        }
    } /* -> que nos dirá la página actual, obtenida por URL, 
    y si esta no existe, desde la variable de sesión. */

    public function getCurrentOrder()
    {
        if (isset($_GET['orderBy']) && isset($_GET['order'])) {
            $orderBy = $_GET['orderBy'];
            $order = $_GET['order'];
        } elseif (isset($_SESSION['orderBy']) && isset($_SESSION['order'])) {
            $orderBy = $_SESSION['orderBy'];
            $order = $_SESSION['order'];
        } else {
            $orderBy = 'titulo';
            $order = 'ASC';
        }
        return ['orderBy' => $orderBy, 'order' => $order];
    } /* ->que nos dirá por qué 
    campo estamos ordenando, y en que orden, obtenidos por URL, 
    y si no, desde la variable de sesión. */
}
