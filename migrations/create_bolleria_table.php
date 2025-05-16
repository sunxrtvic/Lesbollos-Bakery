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

// Creamos la tabla bolleria
$sql = "CREATE TABLE IF NOT EXISTS bolleria (
    id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
    precio DECIMAL(10,2) NOT NULL,
    stock INT(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

if ($conn->query($sql) === TRUE) {
    echo "Tabla 'bolleria' creada correctamente.<br>";
} else {
    die("Error al crear la tabla: " . $conn->error);
}

// Insertamos los productos a la tabla creando un array y haciendo un foreach
$bolleria = [
    ["Croissant", 7.24, 10],
    ["Hojaldre con chocolate blanco", 6.79, 10],
    ["Surtido variado pequeño", 9.99, 5],
    ["Surtido variado grande", 19.99, 5],
];

$stmt = $conn->prepare("INSERT INTO bolleria (nombre, precio, stock) VALUES (?, ?, ?)");

foreach ($bolleria as $producto) {
    [$nombre, $precio, $stock] = $producto;
    $stmt->bind_param("sdi", $nombre, $precio, $stock); // s = string, d = double, i = int
    $stmt->execute();
}

$stmt->close();
$conn->close();

echo "Datos insertados correctamente.";
?>
