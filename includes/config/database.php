<?php
function conectarDB() {
    $db = mysqli_connect('localhost', 'root', '', 'bienesraices_crud');

    if (!$db) {
        die("Error al conectar a la base de datos: ". mysqli_connect_error());
    }
    return $db;
}