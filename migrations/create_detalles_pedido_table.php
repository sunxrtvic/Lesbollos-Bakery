<?php
// Configuramos la conexión a la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "lesbollos";

// Nos conectamos a la base de datos
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Creamos la tabla de detalles pedido
$sql = "CREATE TABLE IF NOT EXISTS detalles_pedido (
    id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    pedido_id INT(11) NOT NULL,
    tabla_producto VARCHAR(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
    producto_id INT(11) NOT NULL,
    cantidad INT(11) NOT NULL,
    precio_unitario DECIMAL(10,2) NOT NULL,
    subtotal DECIMAL(10,2) NOT NULL,
    INDEX (pedido_id),
    CONSTRAINT fk_pedido_id FOREIGN KEY (pedido_id) REFERENCES pagos(id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

if ($conn->query($sql) === TRUE) {
    echo "Tabla 'detalles_pedido' creada correctamente con clave foránea.";
} else {
    echo "Error al crear la tabla: " . $conn->error;
}

//Cerramos conexión
$conn->close();
?>
