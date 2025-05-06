<?php
session_start();

if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['tipo'] !== 'admin') {
    header("Location: principal.php");
    exit();
}

// Aseguramos que se recibieron los datos necesarios
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['producto_id'], $_POST['tabla'])) {
    $producto_id = intval($_POST['producto_id']);
    $tabla = $_POST['tabla'];

    // Lista de las tablas de productos
    $tablas_permitidas = ['bolleria', 'tartas', 'pan', 'eco'];

    if (!in_array($tabla, $tablas_permitidas)) {
        $_SESSION['mensaje'] = "Tabla no válida.";
        header("Location: principal.php");
        exit();
    }

    $conn = new mysqli("localhost", "root", "", "lesbollos");
    if ($conn->connect_error) {
        die("Error de conexión: " . $conn->connect_error);
    }

    // Eliminamos el producto de la tabla específica
    $query = "DELETE FROM `$tabla` WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $producto_id);

    if ($stmt->execute()) {
        $_SESSION['mensaje'] = "Producto eliminado correctamente";
    } else {
        $_SESSION['mensaje'] = "Error al eliminar el producto.";
    }

    $stmt->close();
    $conn->close();
}

// Redirigimos de vuelta a la página de la que venimos
header("Location: {$_SERVER['HTTP_REFERER']}");
exit();
?>
