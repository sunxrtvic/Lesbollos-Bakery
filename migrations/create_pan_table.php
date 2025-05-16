<?php
// Configuramos la conexión a la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "lesbollos";

// Creamos la conexión a la base de datos
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Creamos la tabla pan
$sql = "CREATE TABLE IF NOT EXISTS pan (
    id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
    precio DECIMAL(10,2) NOT NULL,
    stock INT(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

if ($conn->query($sql) === TRUE) {
    echo "Tabla 'pan' creada correctamente.<br>";
} else {
    die("Error al crear la tabla: " . $conn->error);
}

// Insertamos los productos a la tabla creando un array y haciendo un foreach
$pan = [
    ["Pan rústico", 5.67, 10],
    ["Pan brioche", 8.98, 10],
    ["Pan de centeno", 12.31, 7],
    ["Cesta surtido de pan", 24.99, 5],
];

$stmt = $conn->prepare("INSERT INTO pan (nombre, precio, stock) VALUES (?, ?, ?)");

foreach ($pan as $producto) {
    [$nombre, $precio, $stock] = $producto;
    $stmt->bind_param("sdi", $nombre, $precio, $stock); // s = string, d = double, i = int
    $stmt->execute();
}

$stmt->close();
$conn->close();

echo "Datos insertados correctamente.";
?>