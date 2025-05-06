<?php
include_once("encabezado.php");

if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['tipo'] !== 'admin') {
    header("Location: principal.php");
    exit();
}

$mysqli = new mysqli("localhost", "root", "", "lesbollos");
if ($mysqli->connect_error) {
    die("Error de conexión: " . $mysqli->connect_error);
}

$mensaje = "";

// Procesamos el formulario en el que vamos a añadir el producto
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'] ?? '';
    $precio = $_POST['precio'] ?? '';
    $stock = $_POST['stock'] ?? '';

    // Validamos los datos que vayamos a introducir para que no estén en blanco y sean del formato correcto
    if (empty($nombre) || empty($precio) || empty($stock)) {
        $mensaje = "Por favor, completa todos los campos.";
    } elseif (!is_numeric($precio) || !is_numeric($stock)) {
        $mensaje = "El precio y el stock deben ser números.";
    } else {
        // Preparamos la inserción del nuevo producto en la base de datos
        $stmt = $mysqli->prepare("INSERT INTO bolleria (nombre, precio, stock) VALUES (?, ?, ?)");
        $stmt->bind_param("sdi", $nombre, $precio, $stock);

        if ($stmt->execute()) {
            $mensaje = "Producto agregado con éxito.";
        } else {
            $mensaje = "Error al agregar el producto.";
        }

        $stmt->close();
    }
}

$mysqli->close();
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Añadir Producto</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <link rel="stylesheet" href="./css/styleperfil.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/5.0.0/normalize.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
</head>

<body>

    <?php if (!empty($mensaje)): ?>
        <div class="mensaje-exito"><?= htmlspecialchars($mensaje) ?></div>
    <?php endif; ?>

    <!-- Formulario para añadir el nuevo producto -->
    <form class="contenedor-perfil" method="POST" action="añadir-bolleria.php">
        <h1>Añadir nuevo producto de bollería</h1>

        <label>Nombre del producto:</label>
        <input type="text" name="nombre" value="<?= isset($nombre) ? htmlspecialchars($nombre) : '' ?>" required>

        <label>Precio del producto (€):</label>
        <input type="number" step="0.01" name="precio" value="<?= isset($precio) ? htmlspecialchars($precio) : '' ?>"
            required>

        <label>Stock del producto:</label>
        <input type="number" name="stock" value="<?= isset($stock) ? htmlspecialchars($stock) : '' ?>" required>

        <button class="boton-rosa" type="submit">Añadir Producto</button>
    </form>
    <?php include_once("footer.php"); ?>
</body>

</html>