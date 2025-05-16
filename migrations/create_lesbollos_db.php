<?php
// Configuramos la conexion al localhost
$servername = "localhost";
$username = "root";
$password = "";

// Creamos conexión con mysqli
$conn = new mysqli($servername, $username, $password);

// Verificamos que estamos conectados
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Creamos nuestra base de datos "lesbollos"
$sql = "CREATE DATABASE IF NOT EXISTS lesbollos CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci";
if ($conn->query($sql) === TRUE) {
    echo "Base de datos 'lesbollos' creada correctamente o ya existe.";
} else {
    echo "Error al crear la base de datos: " . $conn->error;
}

// Cerramos conexión
$conn->close();
?>