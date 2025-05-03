<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>LesBollos Bakery</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/5.0.0/normalize.min.css">
    <link rel="stylesheet" href="./css/styletartas.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
</head>

<body>
    <!-- Incluimos el header -->
    <?php
    include_once("encabezado.php");
    ?>

    <?php
    session_start();

    // Conectamos con la base de datos
    $mysqli = new mysqli("localhost", "root", "", "lesbollos");
    if ($mysqli->connect_error) {
        die("Error de conexión: " . $mysqli->connect_error);
    }

    // Obtenemos los productos de la tabla "tartas"
    $products = [];
    $result = $mysqli->query("SELECT id, nombre, precio, stock FROM tartas");
    while ($row = $result->fetch_assoc()) {
        $row['image'] = "./imagenes/tartas/" . $row['nombre'] . ".jpg";
        $products[] = $row;
    }

    // Creamos la variable "totalCantidad" para guardarla en sesión y mostrar luego en carrito.php el total añadido al carrito de cada producto
    $totalCantidad = array_sum($_SESSION['carrito'] ?? []);

    // Procesamos el manejo añadir un producto al carrito con sesiones
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_id'], $_POST['cantidad'])) {
        $id = "tartas_" . $_POST['product_id'];
        $cantidad = (int)$_POST['cantidad'];

        if ($cantidad > 0) {
            if (!isset($_SESSION['carrito'][$id])) {
                $_SESSION['carrito'][$id] = 0;
            }
            $_SESSION['carrito'][$id] += $cantidad;
            // Actualizamos la variable "totalCantidad"
            $totalCantidad = array_sum($_SESSION['carrito']);
        }
    }
    ?>

    <main>
        <section>
            <h1>Nuestros Panes</h1>
            <hr>
            <section id="grid">
                <?php foreach ($products as $product): ?>
                    <article>
                        <img src="<?= $product['image'] ?>" alt="<?= htmlspecialchars($product['nombre']) ?>">
                        <h2><?= htmlspecialchars($product['nombre']) ?></h2>
                        <p class="subtitulo"><?= number_format($product['precio'], 2) ?> € - Stock: <?= $product['stock'] ?>
                        </p>
                        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit.</p>
                        <form method="POST" action="">
                            <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                            <input type="number" name="cantidad" value="1" min="1" max="<?= $product['stock'] ?>" style="width: 60px;">
                            <button type="submit">Añadir al carrito</button>
                        </form>
                    </article>
                <?php endforeach; ?>
            </section>
        </section>
    </main>

    <?php
    include_once("footer.php");
    ?>

</body>

</html>
