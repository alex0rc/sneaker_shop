<?php
// Ajusta estos valores
$servername = "localhost";
$username   = "root";
$password   = "";
$dbname     = "sneaker_shop";

// Crear la conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Ajustar el charset.
$conn->set_charset("utf8");
?>
