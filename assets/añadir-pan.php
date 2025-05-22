<?php
session_start();
include_once("encabezado.php");

if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['tipo'] !== 'admin') {
    header("Location: index.php");
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

    // Validamos los datos que vayamos a introducir para que no estén ne blanco y sean del formato correcto
    if (empty($nombre) || empty($precio) || empty($stock)) {
        $mensaje = "Por favor, completa todos los campos.";
    } elseif (!is_numeric($precio) || !is_numeric($stock)) {
        $mensaje = "El precio y el stock deben ser números.";
    } else {
        // Preparamos la inserción del nuevo producto en la base de datos
        $stmt = $mysqli->prepare("INSERT INTO pan (nombre, precio, stock) VALUES (?, ?, ?)");
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
    <form class="contenedor-perfil" method="POST" action="añadir-pan.php">
        <h1>Añadir nuevo pan</h1>

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
<script>
    // Modo día y modo noche usando addClass y removeClass junto a JQuery
    $(function () {
        // Aplica el modo guardado al cargar la página
        if (localStorage.getItem("modo") === "noche") {
            var logo_dia = $("#logo");
            var logo_noche = $("#logo1");
            var body = $("body");
            var icon = $("#viewmode i");
            var header = $("#header");
            var inicio = $("#inicio");
            var registro = $("#registro");
            var principal = $(".contenedor-perfil");
            var logout = $(".logout");
            var icon_user = $(".fa-circle-user");
            var icon_shop = $(".fa-cart-shopping");
            var icon_search = $(".fa-magnifying-glass");
            var icon_admin = $(".fa-users-gear");

            body.addClass("noche");
            header.addClass("nocheHeader");
            inicio.addClass("noche2");
            registro.addClass("noche2");
            principal.addClass("noche2");
            logout.addClass("nochelogout");
            icon_user.addClass("nocheIconos");
            icon_shop.addClass("nocheIconos");
            icon_search.addClass("nocheIconos");
            icon_admin.addClass("nocheIconos");
            icon.addClass("nocheIconos");
            logo_dia.hide();
            logo_noche.show();
            icon.removeClass("fa-moon").addClass("fa-sun");
        }
        //Cuando el usuario clicka el botón de cambio de modo..
        $("#viewmode").click(function () {
            var logo_dia = $("#logo");
            var logo_noche = $("#logo1");
            var body = $("body");
            var icon = $("#viewmode i");
            var header = $("#header");
            var inicio = $("#inicio");
            var registro = $("#registro");
            var principal = $(".contenedor-perfil");
            var logout = $(".logout");
            var forms = $("form");
            var icon_user = $(".fa-circle-user");
            var icon_shop = $(".fa-cart-shopping");
            var icon_search = $(".fa-magnifying-glass");
            var icon_admin = $(".fa-users-gear");

            if (body.hasClass("noche")) {
                body.removeClass("noche");
                header.removeClass("nocheHeader");
                inicio.removeClass("noche2");
                registro.removeClass("noche2");
                principal.removeClass("noche2");
                logout.removeClass("nochelogout");
                icon_user.removeClass("nocheIconos");
                icon_shop.removeClass("nocheIconos");
                icon_search.removeClass("nocheIconos");
                icon_admin.removeClass("nocheIconos");
                icon.removeClass("nocheIconos");
                logo_dia.show();
                logo_noche.hide();
                icon.removeClass("fa-sun").addClass("fa-moon");
                localStorage.setItem("modo", "dia");
            } else {
                body.addClass("noche");
                header.addClass("nocheHeader");
                inicio.addClass("noche2");
                registro.addClass("noche2");
                principal.addClass("noche2");
                logout.addClass("nochelogout");
                icon_user.addClass("nocheIconos");
                icon_shop.addClass("nocheIconos");
                icon_search.addClass("nocheIconos");
                icon_admin.addClass("nocheIconos");
                icon.addClass("nocheIconos");
                logo_dia.hide();
                logo_noche.show();
                icon.removeClass("fa-moon").addClass("fa-sun");

                localStorage.setItem("modo", "noche");
            }
        });
    });
</script>

</html>