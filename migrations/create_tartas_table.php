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

// Creamos la tabla tartas
$sql = "CREATE TABLE IF NOT EXISTS tartas (
    id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
    precio DECIMAL(10,2) NOT NULL,
    stock INT(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

if ($conn->query($sql) === TRUE) {
    echo "Tabla 'tartas' creada correctamente.<br>";
} else {
    die("Error al crear la tabla: " . $conn->error);
}

// Insertamos los productos a la tabla creando un array y haciendo un foreach
$tartas = [
    ["Cheesecake tradicional", 15.99, 10],
    ["Tarta de Nutella semifría", 10.49, 10],
    ["Tarta de fresa batida", 19.99, 7],
    ["Tarta de macedonia", 16.13, 10],
    ["Tarta de Cumpleaños", 20.29, 5],
    ["Tarta de Arándanos", 18.65, 10],
    ["Tarta de Zanahoria", 22.77, 5]
];

$stmt = $conn->prepare("INSERT INTO tartas (nombre, precio, stock) VALUES (?, ?, ?)");

foreach ($tartas as $producto) {
    [$nombre, $precio, $stock] = $producto;
    $stmt->bind_param("sdi", $nombre, $precio, $stock); // s = string, d = double, i = int
    $stmt->execute();
}

$stmt->close();
$conn->close();

echo "Datos insertados correctamente.";
?>