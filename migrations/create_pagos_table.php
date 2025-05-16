<?php
// Configuramos la conexión con la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "lesbollos";

// Creamos la conexión con nuestra base de datos
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Creamos la tabla de pagos
$sql = "CREATE TABLE IF NOT EXISTS pagos (
    id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    user_id INT(11) NOT NULL,
    total DECIMAL(10,2) NOT NULL,
    stripe_id VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
    fecha DATETIME DEFAULT CURRENT_TIMESTAMP,
    INDEX (user_id),
    CONSTRAINT fk_user_id FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

if ($conn->query($sql) === TRUE) {
    echo "Tabla 'pagos' creada correctamente.";
} else {
    echo "Error al crear la tabla: " . $conn->error;
}

//Cerramos conexión
$conn->close();
?>