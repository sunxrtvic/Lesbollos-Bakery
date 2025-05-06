<?php
include_once("encabezado.php");

if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['tipo'] !== 'admin') {
    header("Location: principal.php");
    exit();
}

$conn = new mysqli("localhost", "root", "", "lesbollos");
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

$tablas_validas = ['bolleria', 'tartas', 'pan', 'eco'];
$mensaje = "";

// Si se envió el formulario de edición por POST, procesamos la edición
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id = (int) $_POST['id'];
    $tabla = $_POST['tabla'];

    if (!in_array($tabla, $tablas_validas)) {
        die("Tabla inválida.");
    }

    $nombre = $_POST['nombre'];
    $precio = (float) $_POST['precio'];
    $stock = (int) $_POST['stock'];

    $stmt = $conn->prepare("UPDATE $tabla SET nombre = ?, precio = ?, stock = ? WHERE id = ?");
    $stmt->bind_param("sdii", $nombre, $precio, $stock, $id);

    if ($stmt->execute()) {
        $_SESSION['mensaje'] = "Producto actualizado correctamente.";
        header("Location: {$tabla}.php");
        exit();
    } else {
        $mensaje = "Error al actualizar producto.";
    }

    $stmt->close();
}

// Si llega por GET
$id = isset($_GET['producto_id']) ? (int) $_GET['producto_id'] : null;
$tabla = isset($_GET['tabla']) ? $_GET['tabla'] : null;

if (!in_array($tabla, $tablas_validas)) {
    die("Tabla inválida.");
}

// Obtenemos los datos del producto
$stmt = $conn->prepare("SELECT * FROM $tabla WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$resultado = $stmt->get_result();

if ($resultado->num_rows !== 1) {
    die("Producto no encontrado.");
}

$producto = $resultado->fetch_assoc();
$stmt->close();
$conn->close();

// Mostramos el mensaje si existe en sesión
if (isset($_SESSION['mensaje'])) {
    $mensaje = $_SESSION['mensaje'];
    unset($_SESSION['mensaje']);
}
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Editar Producto</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <link rel="stylesheet" href="./css/styleperfil.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/5.0.0/normalize.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
</head>

<body>

    <?php if (!empty($mensaje)): ?>
        <div class="mensaje-exito"><?= htmlspecialchars($mensaje) ?></div>
    <?php endif; ?>

    <form class="contenedor-perfil" method="POST" action="editar-producto.php">
        <h1>Editar producto</h1>
        <input type="hidden" name="id" value="<?= $producto['id'] ?>">
        <input type="hidden" name="tabla" value="<?= htmlspecialchars($tabla) ?>">

        <label>Nombre del producto:</label>
        <input type="text" name="nombre" value="<?= htmlspecialchars($producto['nombre']) ?>" required>

        <label>Precio del producto:</label>
        <input type="number" step="0.01" name="precio" value="<?= $producto['precio'] ?>" required>

        <label>Stock del producto:</label>
        <input type="number" name="stock" value="<?= $producto['stock'] ?>" required>

        <button class="boton-rosa" type="submit">Guardar cambios</button>
    </form>
</body>

</html>