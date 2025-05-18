<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pagar con Stripe</title>
    <link rel="stylesheet" href="../css/stylepago.css">
    <script src="https://js.stripe.com/v3/"></script>
</head>
<?php
session_start();
require("vendor/autoload.php");

// Verificamos que el usuario haya iniciado sesión
if (!isset($_SESSION['usuario'])) {
    die("Debes iniciar sesión para realizar el pago.");
}

$mysqli = new mysqli("localhost", "root", "", "lesbollos");
if ($mysqli->connect_error) {
    die("Error de conexión: " . $mysqli->connect_error);
}

// Configuramos clave secreta de Stripe
\Stripe\Stripe::setApiKey('sk_test_51Qp6eyDBHYtcSsl5PZyAxzQ5dvvIvGDGKFJ9afB7FFUSR9avS4AaoXzsVOhXK0DdbmZzyGNYrjN8pDLs59Sh9BsI00RfNwilEc');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $paymentMethod = $_POST['payment_method'] ?? '';
    $carrito = $_SESSION['carrito'] ?? [];

    if (empty($carrito) || empty($paymentMethod)) {
        die("Error: Carrito vacío o método de pago no válido.");
    }

    // Calculamos el total del carrito
    $total = 0;
    $productos_en_carrito = [];

    foreach ($carrito as $clave => $cantidad) {
        if ($cantidad <= 0) continue;

        if (preg_match('/^(eco|tartas|pan|bolleria)_(\d+)$/', $clave, $matches)) {
            $tabla = $matches[1];
            $id = (int)$matches[2];

            $stmt = $mysqli->prepare("SELECT nombre, precio FROM $tabla WHERE id = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $res = $stmt->get_result();

            if ($row = $res->fetch_assoc()) {
                $precio = $row['precio'];
                $subtotal = $precio * $cantidad;
                $productos_en_carrito[] = [
                    'tabla' => $tabla,
                    'id' => $id,
                    'nombre' => $row['nombre'],
                    'precio' => $precio,
                    'cantidad' => $cantidad,
                    'subtotal' => $subtotal
                ];
                $total += $subtotal;
            }
        }
    }

    $amountInCents = intval($total * 100);

    try {
        $paymentIntent = \Stripe\PaymentIntent::create([
            'amount' => $amountInCents,
            'currency' => 'eur',
            'payment_method' => $paymentMethod,
            'confirm' => true,
            'return_url' => 'http://localhost/lesbollos/perfil.php',
            'automatic_payment_methods' => ['enabled' => true],
        ]);

        // Guardaamos el pago
        $user_id = $_SESSION['usuario']['id'];
        $stripe_id = $paymentIntent->id;

        $stmt = $mysqli->prepare("INSERT INTO pagos (user_id, total, stripe_id) VALUES (?, ?, ?)");
        $stmt->bind_param("ids", $user_id, $total, $stripe_id);
        $stmt->execute();
        $pedido_id = $stmt->insert_id;  // Obtenenemos el ID del pedido registrado
        $stmt->close();

        // Guardamos los detalles del pedido en la tabla detalles_pedido
        foreach ($productos_en_carrito as $producto) {
            $tabla_producto = $producto['tabla'];
            $producto_id = $producto['id'];
            $precio_unitario = $producto['precio'];
            $subtotal = $producto['subtotal'];

            // Insertamos los detalles del pedido en la tabla detalles_pedido
            $stmt_detalles = $mysqli->prepare("INSERT INTO detalles_pedido (pedido_id, tabla_producto, producto_id, cantidad, precio_unitario, subtotal) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt_detalles->bind_param("isiddd", $pedido_id, $tabla_producto, $producto_id, $producto['cantidad'], $precio_unitario, $subtotal);
            $stmt_detalles->execute();
            $stmt_detalles->close();

            // Actualizamos el stock para que reste 1 al producto/s
            $stmt_stock = $mysqli->prepare("UPDATE $tabla_producto SET stock = stock - ? WHERE id = ?");
            $stmt_stock->bind_param("ii", $producto['cantidad'], $producto_id);
            $stmt_stock->execute();
            $stmt_stock->close();
        }

        unset($_SESSION['carrito']); // Vaciamos el carrito una vez realizado el pago

        // Guardamos mensaje de éxito en sesión para mostrarlo en index.php
        $_SESSION['mensaje_pago'] = "¡Pago realizado con éxito! Consulta más abajo tu historial de pago.";

        // Redirigir a perfil.php
        header("Location: ../perfil.php");
        exit;

    } catch (Exception $e) {
        echo "Error al procesar el pago: " . $e->getMessage();
    }
}

?>
