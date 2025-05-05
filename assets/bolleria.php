<?php
session_start();

// Verificar si el usuario ha iniciado sesión
$usuario_autenticado = isset($_SESSION['usuario']);

// Conectamos con la base de datos
$mysqli = new mysqli("localhost", "root", "", "lesbollos");
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
$total_resultados = $mysqli->query("SELECT COUNT(*) as total FROM tartas WHERE stock > 0")->fetch_assoc()['total'];
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
</head>

<body>
    <!-- Incluimos el header -->
    <?php include_once("encabezado.php"); ?>


    <?php if ($mensaje): ?>
        <!-- Mostrar el mensaje solo si existe -->
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
                        <p class="subtitulo"><?= number_format($product['precio'], 2) ?> €/kg - Stock: <?= $product['stock'] ?></p>
                        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit.</p>
                        <form method="POST" action="">
                            <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                            <input type="number" name="cantidad" value="1" min="1" max="<?= $product['stock'] ?>" style="width: 60px;">
                            <button type="submit">Añadir al carrito</button>
                        </form>
                    </article>
                <?php endforeach; ?>
            </section>

            <div id="paginacion">
                <?php if ($pagina_actual > 1): ?>
                    <form action="" method="get" style="display:inline;">
                        <button type="submit" name="pagina" value="<?= $pagina_actual - 1 ?>" class="pagina-anterior">Anterior</button>
                    </form>
                <?php endif; ?>

                <span>Página <?= $pagina_actual ?> de <?= $total_paginas ?></span>

                <?php if ($pagina_actual < $total_paginas): ?>
                    <form action="" method="get" style="display:inline;">
                        <button type="submit" name="pagina" value="<?= $pagina_actual + 1 ?>" class="pagina-siguiente">Siguiente</button>
                    </form>
                <?php endif; ?>
            </div>

        </section>
    </main>

    <?php include_once("footer.php"); ?>

</body>
</html>
