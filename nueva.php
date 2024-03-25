<?php
require_once "autoloader.php";
$connection = new Model();
$conn = $connection->getConn();
if (count($_POST) > 0){
    $connection->addTarea($_POST);
    header("location: lista.php");
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>Nueva Tarea</title>
    <link rel="stylesheet" type="text/css" href="form/view.css" media="all">
</head>

<body id="main_body">

    <img id="top" src="form/top.png" alt="">
    <div id="form_container">

        <h1><a>Nueva Tarea</a></h1>
        <form class="appnitro" method="post" action="">
            <div class="form_description">
                <h2>Nueva Tarea</h2>
                <p>Introduce los datos de la tarea</p>
            </div>
            <ul>

                <li>
                    <label class="description" for="titulo">Título </label>
                    <div>
                        <input name="titulo" class="element text medium" type="text" maxlength="255" value="" />
                    </div>
                </li>
                <li>
                    <label class="description" for="descripcion">Descripción de la tarea </label>
                    <div>
                        <textarea name="descripcion" class="element textarea medium"></textarea>
                    </div>
                </li>
                <li>
                    <label class="description" for="fecha_vencimiento">Fecha de vencimiento </label>
                    <span>
                        <input name="fecha_vencimiento" class="element text" size="2" maxlength="2" value="" type="date">
                    </span>
                </li>

                <li class="buttons">
                    <input type="hidden" name="id" value="12028" />
                    <input class="button_text" type="submit" name="submit" value="Guardar" />
                </li>
            </ul>
        </form>
        <div id="footer">
            Generated by <a href="http://www.floridauniversitaria.es">Florida</a>
        </div>
    </div>
    <img id="bottom" src="form/bottom.png" alt="">
</body>

</html>