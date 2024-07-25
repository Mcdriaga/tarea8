<?php

$server = 'localhost';
$user = 'root';
$pass = 'Un4given.2';
$db = 'db_viajes';

$conexion = new mysqli($server, $user, $pass, $db);

if ($conexion->connect_errno) {
    die("Conexión Fallida" . $conexion->connect_errno);
} else {
    echo "conectado";
}

?>
