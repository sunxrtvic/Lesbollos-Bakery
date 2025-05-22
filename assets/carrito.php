<?php session_start(); ?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>LesBollos Bakery</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/5.0.0/normalize.min.css">
    <link rel="stylesheet" href="./css/stylecarrito.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
</head>

<body>
    <?php include_once('encabezado.php'); ?>

    <main>
        <?php

        $mysqli = new mysqli("localhost", "root", "", "lesbollos");
        if ($mysqli->connect_error) {
            die("Error de conexión: " . $mysqli->connect_error);
        }

        // Inicializamos el valor carrito en la sesión
        if (!isset($_SESSION['carrito'])) {
            $_SESSION['carrito'] = [];
        }

        // Obtenemos productos desde todas las tablas
        $productsById = [];
        $tablas = ['tartas', 'pan', 'bolleria', 'eco'];

        foreach ($tablas as $tabla) {
            $result = $mysqli->query("SELECT id, nombre, precio, stock FROM $tabla");
            while ($p = $result->fetch_assoc()) {
                $clave = $tabla . "_" . $p['id']; // ID compuesto
                $p['image'] = "./imagenes/{$tabla}/" . $p['nombre'] . ".jpg";
                $p['tabla'] = $tabla;
                $productsById[$clave] = $p;
            }
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['product_id'], $_POST['cantidad'])) {
                $id = $_POST['product_id'];
                $cant = (int) $_POST['cantidad'];

                if ($cant > 0 && isset($productsById[$id])) {
                    $enCarrito = $_SESSION['carrito'][$id] ?? 0;
                    if (($cant + $enCarrito) <= $productsById[$id]['stock']) {
                        $_SESSION['carrito'][$id] = $enCarrito + $cant;
                    }
                }
            } elseif (isset($_POST['remove_product_id'], $_POST['remove_quantity'])) {
                $id = $_POST['remove_product_id'];
                $cant = (int) $_POST['remove_quantity'];

                if (isset($_SESSION['carrito'][$id])) {
                    $_SESSION['carrito'][$id] -= $cant;
                    if ($_SESSION['carrito'][$id] <= 0) {
                        unset($_SESSION['carrito'][$id]);
                    }
                }
            } elseif (isset($_POST['vaciar_carrito'])) {
                $_SESSION['carrito'] = [];
            }

            header("Location: carrito.php");
            exit;
        }

        // Mostramos los productos guardados en el carrito
        echo "<section>";
        if (!empty($_SESSION['carrito'])) {
            $total = 0;
            echo "<table border='1' style='width: 100%; text-align: left;'>";
            echo "<tr><th>Imagen</th><th>Producto</th><th>Cantidad</th><th>Precio</th><th>Subtotal</th><th>Acción</th></tr>";

            foreach ($_SESSION['carrito'] as $id => $cant) {
                if (!isset($productsById[$id]))
                    continue;
                $prod = $productsById[$id];
                $subtotal = $cant * $prod['precio'];
                $total += $subtotal;

                echo "<tr class='tabla'>";
                echo "<td><img src='{$prod['image']}' style='width: 50px;'></td>";
                echo "<td>" . htmlspecialchars($prod['nombre']) . "</td>";
                echo "<td>$cant</td>";
                echo "<td>" . number_format($prod['precio'], 2) . " €</td>";
                echo "<td>" . number_format($subtotal, 2) . " €</td>";
                echo "<td>
                <form method='POST' action='' style='display:inline;'>
                    <input type='hidden' name='remove_product_id' value='{$id}'>
                    <input type='number' name='remove_quantity' value='1' min='1' max='{$cant}' style='width: 50px;'>
                    <button type='submit'>Eliminar</button>
                </form>
              </td>";
                echo "</tr>";
            }

            echo "<tr class='tabla'><td colspan='4' style='text-align: right;'><strong>Total:</strong></td>
              <td>" . number_format($total, 2) . " €</td><td></td></tr>";
            echo "</table>";

            echo "<div class='carrito-botones'>
    <form method='POST' action=''>
        <button type='submit' name='vaciar_carrito'><i class='fa fa-trash'></i> Vaciar carrito</button>
    </form>
    <a href='./api/pago.php'><button><i class='fa fa-credit-card'></i> Pagar</button></a>
</div>";
        } else {
            echo "<p>El carrito está vacío.</p>";
        }
        echo "</section>";
        ?>
    </main>

    <?php include_once "footer.php"; ?>
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
            var tabla = $(".tabla");
            var articles = $("article");
            var forms = $("form");
            var icon_user = $(".fa-circle-user");
            var icon_shop = $(".fa-cart-shopping");
            var icon_search = $(".fa-magnifying-glass");
            var icon_admin = $(".fa-users-gear");

            body.addClass("noche");
            header.addClass("nocheHeader");
            inicio.addClass("noche2");
            registro.addClass("noche2");
            tabla.addClass("noche2");
            articles.addClass("noche2");
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
            var tabla = $(".tabla");
            var articles = $("article");
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
                tabla.removeClass("noche2");
                articles.removeClass("noche2");
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
                tabla.addClass("noche2");
                articles.addClass("noche2");
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