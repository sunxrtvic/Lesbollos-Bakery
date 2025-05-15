<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>LesBollos Bakery</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/5.0.0/normalize.min.css">
    <link rel="stylesheet" href="./css/stylecarrito.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <style>
        button {
            background-color: #b55690;
            color: white;
            border: none;
            padding: 1rem 1.5rem;
            font-size: 1.2rem;
            border-radius: 0.4rem;
            cursor: pointer;
            width: 100%;
            box-sizing: border-box;
        }
    </style>
</head>

<body>
    <?php include_once('encabezado.php'); ?>

    <main>
        <?php

        if (!isset($_SESSION['usuario']) || !isset($_SESSION['usuario']['id'])) {
            echo "<p>Debes iniciar sesión para ver tu historial de pagos.</p>";
            exit;
        }

        $usuario_autenticado = $_SESSION['usuario']['id'];

        $mysqli = new mysqli("localhost", "root", "", "lesbollos");
        if ($mysqli->connect_error) {
            die("Error de conexión: " . $mysqli->connect_error);
        }

        // Consulta solo para los pagos del usuario logueado
        $query = "SELECT id, total, fecha FROM pagos WHERE user_id = $usuario_autenticado ORDER BY fecha DESC";
        $result = $mysqli->query($query);

        if ($result->num_rows > 0) {
            echo "<section>";
            echo "<table border='1' style='width: 100%; text-align: left;'>";
            echo "<tr><th>Pedido</th><th>Productos del pedido</th><th>Cantidad</th><th>Total</th><th>Fecha de Compra</th></tr>";

            while ($row = $result->fetch_assoc()) {
                $id = $row['id'];
                $total = number_format($row['total'], 2);
                $fecha = $row['fecha'];

                // Obtenemos la cantidad total de productos en el pedido
                $query_productos = "SELECT SUM(cantidad) AS cantidad_total FROM detalles_pedido WHERE pedido_id = $id";
                $result_productos = $mysqli->query($query_productos);
                $cantidad_total = 0;

                if ($result_productos && $row_productos = $result_productos->fetch_assoc()) {
                    $cantidad_total = $row_productos['cantidad_total'];
                }

                // Detalles del pedido
                $query_detalles = "SELECT cantidad, tabla_producto, producto_id 
                                   FROM detalles_pedido 
                                   WHERE pedido_id = $id";
                $result_detalles = $mysqli->query($query_detalles);

                $productos = [];
                if ($result_detalles) {
                    while ($detalle = $result_detalles->fetch_assoc()) {
                        $tabla_producto = $detalle['tabla_producto'];
                        $producto_id = $detalle['producto_id'];

                        $query_nombre_producto = "SELECT nombre FROM $tabla_producto WHERE id = $producto_id";
                        $result_nombre = $mysqli->query($query_nombre_producto);

                        if ($result_nombre && $producto = $result_nombre->fetch_assoc()) {
                            $nombre_producto = $producto['nombre'];
                            $productos[] = $detalle['cantidad'] . " x " . $nombre_producto;
                        }
                    }
                }

                $productos_lista = implode(", ", $productos);

                echo "<tr class='tabla'>";
                echo "<td>#" . htmlspecialchars($id) . "</td>";
                echo "<td>" . htmlspecialchars($productos_lista) . "</td>";
                echo "<td>" . $cantidad_total . "</td>";
                echo "<td>" . $total . " €</td>";
                echo "<td>" . htmlspecialchars($fecha) . "</td>";
                echo "</tr>";
            }

            echo "</table>";
            echo "</section>";
        } else {
            echo "<p>No has realizado compras aún.</p>";
        }
        ?>

        <div style="margin: 20px 0;">
            <a href="historial-pago-pdf.php" target="_blank">
                <button>Descargar historial en PDF</button>
            </a>
        </div>

    </main>

    <?php include_once "footer.php"; ?>
</body>
<script>
    // Modo día y modo noche usando addClass y removeClass junto a JQuery
    $(function () {
        // Aplicar el modo guardado al cargar la página
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