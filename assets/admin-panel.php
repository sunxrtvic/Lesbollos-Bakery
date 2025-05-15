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
        h1 {
            padding-bottom: 1.875rem;
        }
    </style>
</head>

<body>
    <div class="contenedor-perfil">
        <h1>Panel de control del admin</h1>
        <a class="boton-rosa" href="añadir-tartas.php">Añadir nueva tarta</a>
        <a class="boton-rosa" href="añadir-pan.php">Añadir nuevo pan</a>
        <a class="boton-rosa" href="añadir-bolleria.php">Añadir nueva bollería</a>
        <a class="boton-rosa" href="añadir-eco.php">Añadir nuevo producto ecológico</a>

        <a href="cerrar-sesion.php" class="logout">Cerrar sesión</a>
    </div>

    <?php include_once('footer.php'); ?>
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