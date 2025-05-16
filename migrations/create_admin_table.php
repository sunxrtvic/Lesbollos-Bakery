<?php
// Configuramos la conexión con la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "lesbollos";

// Creamos la conexión
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Creamos la tabla admin, si esta no existe en la base de datos
$sql = "CREATE TABLE IF NOT EXISTS admin (
    id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
    contraseña VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
    INDEX (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

if ($conn->query($sql) === TRUE) {
    echo "Tabla 'admin' creada correctamente.<br>";
} else {
    die("Error al crear la tabla: " . $conn->error);
}

// Insertamos los datos del admin
$email = "vrg_rodriguez@hotmail.com";
$contraseña = "12345678";

$stmt = $conn->prepare("INSERT INTO admin (email, contraseña) VALUES (?, ?)");
$stmt->bind_param("ss", $email, $contraseña); //ss= string, es el tipo de dato que se inserta

if ($stmt->execute()) {
    echo "Fila insertada correctamente.";
} else {
    echo "Error al insertar datos: " . $stmt->error;
}

// Cerramos conexiones
$stmt->close();
$conn->close();
?>
