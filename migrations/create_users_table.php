<?php
// Configuramos de la conexión con la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "lesbollos";

// Creamos la conexión a nuestra base da datos
$conn = new mysqli($servername, $username, $password, $dbname);


if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Creamos la tabla users
$sql = "CREATE TABLE IF NOT EXISTS users (
    id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
    apellidos VARCHAR(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
    email VARCHAR(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
    contraseña VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
    edad DATE DEFAULT NULL,
    telefono VARCHAR(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
    acepta TINYINT(1) DEFAULT 0,
    avatar VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
    INDEX (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

if ($conn->query($sql) === TRUE) {
    echo "Tabla 'users' creada correctamente.";
} else {
    echo "Error al crear la tabla: " . $conn->error;
}

//Cerramos conexión
$conn->close();
?>
