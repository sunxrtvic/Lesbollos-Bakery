<?php
session_start();
include_once("encabezado.php");

$usuario_autenticado = isset($_SESSION['usuario']);

// Obtenemos la URL completa de la conexión con la base de datos desde la variable de entorno
$dbUrl = getenv('MYSQL_URL');  // En nuestro hosting, aunque configurada automáticamente, debemos metarla nosotros a mano para que funcione

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
// El path incluye / al inicio, la quitamos para obtener el nombre de la base
$dbname = ltrim($dbParts['path'] ?? '', '/');

// Creamos la conexión a la base de datos
$mysqli = new mysqli($host, $user, $pass, $dbname, $port);

if ($mysqli->connect_error) {
    die("Error de conexión: " . $mysqli->connect_error);
}

// Definimos cuantos productos queremos mostrar por página
$productos_por_pagina = 6;

// Obtenemos la página actual desde la URL, por defecto será la página 1
$pagina_actual = isset($_GET['pagina']) ? (int) $_GET['pagina'] : 1;
$offset = ($pagina_actual - 1) * $productos_por_pagina;

//Filtramos los productos de la tabla "bolleria" para solo tomar los que su stock sea mayor a 0
$products = [];
$result = $mysqli->query("SELECT id, nombre, precio, stock FROM bolleria WHERE stock > 0 LIMIT $offset, $productos_por_pagina");
while ($row = $result->fetch_assoc()) {
    $row['image'] = "./imagenes/bolleria/" . $row['nombre'] . ".jpg";
    $products[] = $row;
}

// Calculamos el total de productos en la tabla para calcular el número total de páginas que habrá
$total_resultados = $mysqli->query("SELECT COUNT(*) as total FROM bolleria WHERE stock > 0")->fetch_assoc()['total'];
$total_paginas = ceil($total_resultados / $productos_por_pagina);

// Creamos la variable "totalCantidad" para guardarla en sesión y mostrar luego en carrito.php el total añadido al carrito de cada producto
$totalCantidad = array_sum($_SESSION['carrito'] ?? []);

// Procesamos el manejo de añadir un producto al carrito con sesiones
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_id'], $_POST['cantidad'])) {
    if (!$usuario_autenticado) {
        // Si el usuario no está autenticado, mostramos un mensaje
        echo "<script>alert('Debes iniciar sesión para añadir productos al carrito.');</script>";
    } else {
        $id = "bolleria_" . $_POST['product_id'];
        $cantidad = (int) $_POST['cantidad'];

        if ($cantidad > 0) {
            if (!isset($_SESSION['carrito'][$id])) {
                $_SESSION['carrito'][$id] = 0;
            }
            $_SESSION['carrito'][$id] += $cantidad;
            $totalCantidad = array_sum($_SESSION['carrito']);

            // Mostramos un mensaje si se ha añadido el producto  al carrito con éxito
            $_SESSION['mensaje'] = "¡Producto añadido al carrito!";
        }
    }
}

$mensaje = isset($_SESSION['mensaje']) ? $_SESSION['mensaje'] : '';
// Eliminamos el mensaje después de mostrarlo para que no se repita
if ($mensaje) {
    unset($_SESSION['mensaje']);
}
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>LesBollos Bakery</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <link rel="stylesheet" href="./css/stylebolleria.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/5.0.0/normalize.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <style>
        button {
            margin-bottom: 10px;
        }
    </style>
</head>

<body>
    <?php if ($mensaje): ?>
        <!-- Mostramos el mensaje solo si existe -->
        <div class="mensaje-exito-carrito">
            <?= $mensaje ?>
        </div>
    <?php endif; ?>

    <main>
        <section>
            <h1>Nuestra Bollería</h1>
            <hr>
            <section id="grid">
                <?php foreach ($products as $product): ?>
                    <article>
                        <img src="<?= $product['image'] ?>" alt="<?= htmlspecialchars($product['nombre']) ?>">
                        <h2><?= htmlspecialchars($product['nombre']) ?></h2>
                        <p class="subtitulo"><?= number_format($product['precio'], 2) ?> €/kg - Stock:
                            <?= $product['stock'] ?>
                        </p>
                        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit.</p>
                        <!-- Si el usuario no es admin se le muestra el botón para añadir el producto al carrito y el input number para que eliga la cantidad-->
                        <?php if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['tipo'] !== 'admin'): ?>
                            <form method="POST" action="">
                                <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                                <input type="number" name="cantidad" value="1" min="1" max="<?= $product['stock'] ?>"
                                    style="width: 60px;">
                                <button type="submit">Añadir al carrito</button>
                            <?php endif; ?>
                            <!-- Si el usuario es admin se le muestra los botones para eliminar o editar el producto-->
                            <?php if (isset($_SESSION['usuario']) && $_SESSION['usuario']['tipo'] === 'admin'): ?>
                                <form method="GET" action="editar-producto.php" style="display:inline;">
                                    <input type="hidden" name="producto_id" value="<?= $product['id'] ?>">
                                    <input type="hidden" name="tabla" value="bolleria">
                                    <button type="submit">Editar producto</button>
                                </form>
                                <form method="POST" action="eliminar-producto.php" style="display:inline;"
                                    onsubmit="return confirm('¿Estás seguro de que deseas eliminar este producto?');">
                                    <input type="hidden" name="producto_id" value="<?= $product['id'] ?>">
                                    <input type="hidden" name="tabla" value="bolleria">
                                    <button type="submit">Eliminar producto</button>
                                </form>
                            <?php endif; ?>
                        </form>
                    </article>
                <?php endforeach; ?>
            </section>

            <div id="paginacion">
                <?php if ($pagina_actual > 1): ?>
                    <form action="" method="get" style="display:inline;">
                        <button type="submit" name="pagina" value="<?= $pagina_actual - 1 ?>"
                            class="pagina-anterior">Anterior</button>
                    </form>
                <?php endif; ?>

                <span>Página <?= $pagina_actual ?> de <?= $total_paginas ?></span>

                <?php if ($pagina_actual < $total_paginas): ?>
                    <form action="" method="get" style="display:inline;">
                        <button type="submit" name="pagina" value="<?= $pagina_actual + 1 ?>"
                            class="pagina-siguiente">Siguiente</button>
                    </form>
                <?php endif; ?>
            </div>

        </section>
    </main>

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
            var principal = $(".principal");
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
            principal.addClass("noche");
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
            var principal = $(".principal");
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
                principal.removeClass("noche");
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
                principal.addClass("noche");
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