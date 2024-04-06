<?php
session_start();
session_destroy();
session_start();
include_once "connection.php";

if ($connection->connect_error) {
    die("La conexión a la base de datos falló: " . $connection->connect_error);
} else {
    echo ("Conexión establecida");
}

// Cerrar la conexión a la base de datos
