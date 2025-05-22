<?php
session_start();

// Verificar si el usuario ha iniciado sesión
$usuario_autenticado = isset($_SESSION['usuario']); // Asegúrate de que el nombre de la variable de sesión sea el correcto

if (!$usuario_autenticado) {
    echo "Debes iniciar sesión antes de entrar aquí.";
    header("Location: registroInicio.php");
    exit();
}

// Verificar si hay productos en el carrito
if (empty($_SESSION['carrito'])) {
    echo "No tienes productos en el carrito.";
    exit();
}

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

// Obtener los productos del carrito
$productos_en_carrito = [];
$total = 0;
foreach ($_SESSION['carrito'] as $id => $cantidad) {
    $tabla = explode('_', $id)[0]; // Determinar la tabla (bolleria, pan, etc.)
    $id_producto = (int) explode('_', $id)[1]; // Obtener el ID del producto

    // Obtener los detalles del producto
    $result = $mysqli->query("SELECT nombre, precio FROM $tabla WHERE id = $id_producto");
    $producto = $result->fetch_assoc();

    if ($producto) {
        $producto['cantidad'] = $cantidad;
        $producto['subtotal'] = $producto['cantidad'] * $producto['precio'];
        $productos_en_carrito[] = $producto;
        $total += $producto['subtotal'];
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pagar con Stripe</title>
    <link rel="stylesheet" href="../css/stylepago.css">
    <script src="https://js.stripe.com/v3/"></script>
</head>

<body>
    <h1>Resumen de tu pedido</h1>

    <!-- Mostrar productos en el carrito -->
    <div class="productos-carrito">
        <table>
            <tr>
                <th>Producto</th>
                <th>Cantidad</th>
                <th>Precio</th>
                <th>Subtotal</th>
            </tr>
            <?php foreach ($productos_en_carrito as $producto): ?>
                <tr>
                    <td><?= htmlspecialchars($producto['nombre']) ?></td>
                    <td><?= $producto['cantidad'] ?></td>
                    <td><?= number_format($producto['precio'], 2) ?> €</td>
                    <td><?= number_format($producto['subtotal'], 2) ?> €</td>
                </tr>
            <?php endforeach; ?>
            <tr>
                <td colspan="3" class="total">Total:</td>
                <td class="total"><?= number_format($total, 2) ?> €</td>
            </tr>
        </table>
    </div>

    <!-- Formulario de pago con Stripe -->
    <form id="payment-form" action="index.php" method="POST">
        <label for="amount">Cantidad a pagar (€):</label>
        <input type="number" id="amount" name="amount" value="<?= $total ?>" min="1" step="0.01" readonly required>

        <div id="card-element"></div>

        <!-- Campo oculto para almacenar el paymentMethod.id -->
        <input type="hidden" id="payment_method" name="payment_method">

        <button type="submit" id="submit-button">Pagar</button>
        <div id="payment-message"></div>
    </form>


    <script>
        const stripe = Stripe('pk_test_51Qp6eyDBHYtcSsl5EzLicDH23rglhnkEJhsnDzn0faxOr7c86kbjZ6EWOEvB7BU9iXLz30SZOmcjnE0fm6SMxpWF00xQFDa8N9');
        const elements = stripe.elements();
        const cardElement = elements.create('card');
        cardElement.mount('#card-element');

        const form = document.getElementById('payment-form');
        form.addEventListener('submit', async (event) => {
            event.preventDefault(); // Evita el envío del formulario hasta obtener el paymentMethod

            const { paymentMethod, error } = await stripe.createPaymentMethod({
                type: 'card',
                card: cardElement,
            });

            if (error) {
                document.getElementById('payment-message').innerText = error.message;
                return;
            }

            // Asigna el ID del método de pago al campo oculto y envía el formulario
            document.getElementById('payment_method').value = paymentMethod.id;
            form.submit();
        });
    </script>
</body>

</html>