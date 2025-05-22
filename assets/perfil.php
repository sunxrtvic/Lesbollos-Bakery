<?php session_start(); ?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>LesBollos Bakery</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/5.0.0/normalize.min.css">
    <link rel="stylesheet" href="./css/styleperfil.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
</head>

<body>
    <?php include_once('encabezado.php'); ?>
    <?php

    if (!isset($_SESSION['usuario'])) {
        header("Location: user.php");
        exit();
    }

    /// Obtenemos la URL completa de la conexión con la base de datos desde la variable de entorno
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

    $mensaje = "";
    $usuario = $_SESSION['usuario'];

    // Verificamos si hay mensaje de pago en la sesión
    if (isset($_SESSION['mensaje_pago'])) {
        $mensaje = $_SESSION['mensaje_pago'];
        unset($_SESSION['mensaje_pago']);
    }

    if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['actualizar'])) {
        $nombre = $_POST['nombre'];
        $apellidos = $_POST['apellidos'];
        $telefono = $_POST['telefono'];
        $edad = $_POST['edad'];
        $avatarName = $usuario['avatar'] ?? "";


//Con nuestro hosting no podemos tocar los permisos para mover las imagenes a otra carpeta, así que vamos manejarnos con la carpeta tmp
//No es lo recomendado, ya que cada vez que se reinice el hosting las imagenes desaparecerán, pero nos hace el apaño
        if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
            $fileTmp = $_FILES['avatar']['tmp_name'];
            $fileName = basename($_FILES['avatar']['name']);
            $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
            $allowedExts = ['jpg', 'jpeg', 'png', 'gif'];

            if (in_array($fileExt, $allowedExts)) {
                $newFileName = 'avatar_' . $usuario['id'] . '.' . $fileExt;
                $uploadDir = sys_get_temp_dir() . '/';

                $uploadPath = $uploadDir . $newFileName;

                if (move_uploaded_file($fileTmp, $uploadPath)) {
                    // Guardamos solo el nombre del archivo, sin rutas
                    $avatarName = $newFileName;
                }
            }
        }

        $stmt = $conn->prepare("UPDATE users SET nombre = ?, apellidos = ?, telefono = ?, edad = ?, avatar = ? WHERE id = ?");
        $stmt->bind_param("sssssi", $nombre, $apellidos, $telefono, $edad, $avatarName, $usuario['id']);

        if ($stmt->execute()) {
            $mensaje = "Datos actualizados con éxito.";
            $_SESSION['usuario']['nombre'] = $nombre;
            $_SESSION['usuario']['apellidos'] = $apellidos;
            $_SESSION['usuario']['telefono'] = $telefono;
            $_SESSION['usuario']['edad'] = $edad;
            $_SESSION['usuario']['avatar'] = $avatarName;
            $usuario = $_SESSION['usuario'];
        } else {
            $mensaje = "Error al actualizar los datos.";
        }

        $stmt->close();
    }

    $conn->close();
    ?>
    <?php if (!empty($mensaje)): ?>
        <div class="mensaje-exito"><?= htmlspecialchars($mensaje) ?></div>
    <?php endif; ?>
    <div class="contenedor-perfil">
        <div class="contenedor-avatar">
            <?php
            $avatar = !empty($usuario['avatar']) ? htmlspecialchars($usuario['avatar']) : null;
            ?>
            <img src="<?= $avatar
                ? 'avatar-perfil.php?archivo=' . urlencode($avatar)
                : 'imagenes/iconos/default-avatar.jpg' ?>" alt="Avatar" class="avatar">
        </div>
        <div class="contenedor-info">
            <h1>Bienvenid@, <?= htmlspecialchars($usuario['nombre']) ?></h1>

            <form method="POST" enctype="multipart/form-data">
                <input type="hidden" name="actualizar" value="1">

                <label>Foto de perfil:</label>
                <input type="file" name="avatar" accept="image/*">

                <label>Nombre:</label>
                <input type="text" name="nombre" value="<?= htmlspecialchars($usuario['nombre']) ?>" required>

                <label>E-mail:</label>
                <input type="text" name="email" value="<?= htmlspecialchars($usuario['email']) ?>" required>

                <label>Apellidos:</label>
                <input type="text" name="apellidos" value="<?= htmlspecialchars($usuario['apellidos']) ?>" required>

                <label>Teléfono:</label>
                <input type="text" name="telefono" value="<?= htmlspecialchars($usuario['telefono']) ?>" required>

                <label>Fecha de nacimiento:</label>
                <input type="date" name="edad" value="<?= htmlspecialchars($usuario['edad']) ?>" required>

                <input class="boton-rosa" type="submit" value="Guardar cambios">
                <a class="boton-rosa" href="historial-pago.php">Ver historial de pagos</a>
            </form>

            <a href="cerrar-sesion.php" class="logout">Cerrar sesión</a>
        </div>
    </div>

    <?php include_once('footer.php'); ?>
</body>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    // Modo día y modo noche usando addClass y removeClass junto a JQuery
    $(function () {
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