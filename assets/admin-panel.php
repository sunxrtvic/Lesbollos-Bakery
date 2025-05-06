<?php

include_once('encabezado.php');

if (!isset($_SESSION['usuario'])) {
    header("Location: user.php");
    exit();
}

$usuario = $_SESSION['usuario'];
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>LesBollos Bakery</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/5.0.0/normalize.min.css">
    <link rel="stylesheet" href="./css/styleperfil.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <style>
        h1{
            padding-bottom: 1.875rem;
        }
    </style>
</head>

<body>
    <div class="contenedor-perfil">
        <h1>Panel de control del admin</h1>
        <a class="boton-rosa" href="añadir-tartas.php">Añadir nueva tarta</a>
        <a class="boton-rosa" href="añadir-pan.php">Añadir  nuevo pan</a>
        <a class="boton-rosa" href="añadir-bolleria.php">Añadir nueva bollería</a>
        <a class="boton-rosa" href="añadir-eco.php">Añadir nuevo producto ecológico</a>

        <a href="cerrar-sesion.php" class="logout">Cerrar sesión</a>
    </div>

    <?php include_once('footer.php'); ?>
</body>

</html>
