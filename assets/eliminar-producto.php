<?php
session_start();

if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['tipo'] !== 'admin') {
    header("Location: index.php");
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
        header("Location: index.php");
        exit();
    }

    // Obtenemos la URL completa de la conexión con la base de datos desde la variable de entorno
    $dbUrl = getenv('MYSQL_URL');  // Asegúrate de que esté configurada en Railway

    if (!$dbUrl) {
        die("Error: La variable de entorno MYSQL_URL no está configurada.");
    }

    // Parseamos la URL de la base de datos del hosting
    $dbParts = parse_url($dbUrl);

    if (!$dbParts) {
        die("Error: No se pudo parsear MYSQL_URL.");
    }

    $host = $dbParts['host'] ?? '';
    $port = $dbParts['port'] ?? 3306;
    $user = $dbParts['user'] ?? '';
    $pass = $dbParts['pass'] ?? '';
    $dbname = ltrim($dbParts['path'] ?? '', '/');

    // Creamos la conexión a la base de datos
    $conn = new mysqli($host, $user, $pass, $dbname, $port);

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