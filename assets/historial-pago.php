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
        session_start();

        $mysqli = new mysqli("localhost", "root", "", "lesbollos");
        if ($mysqli->connect_error) {
            die("Error de conexión: " . $mysqli->connect_error);
        }

        // Consulta para obtener los pagos
        $query = "SELECT id, user_id, total, stripe_id, fecha FROM pagos ORDER BY fecha DESC";
        $result = $mysqli->query($query);

        // Verificamos si hay pagos
        if ($result->num_rows > 0) {
            echo "<section>";
            echo "<table border='1' style='width: 100%; text-align: left;'>";
            echo "<tr><th>Pedido</th><th>Productos del pedido</th><th>Cantidad</th><th>Total</th><th>Fecha de Compra</th></tr>";

            while ($row = $result->fetch_assoc()) {
                $id = $row['id'];
                $user_id = $row['user_id'];
                $total = number_format($row['total'], 2);
                $fecha = $row['fecha'];

                // Obtenemos la cantidad total de productos en el pedido
                $query_productos = "SELECT SUM(cantidad) AS cantidad_total FROM detalles_pedido WHERE pedido_id = $id";
                $result_productos = $mysqli->query($query_productos);
                $cantidad_total = 0;

                if ($result_productos && $row_productos = $result_productos->fetch_assoc()) {
                    $cantidad_total = $row_productos['cantidad_total'];
                }

                // Consultamos los datos de detalles_pedido
                $query_detalles = "SELECT detalles_pedido.cantidad, detalles_pedido.tabla_producto, detalles_pedido.producto_id 
                                   FROM detalles_pedido 
                                   WHERE detalles_pedido.pedido_id = $id";
                $result_detalles = $mysqli->query($query_detalles);

                $productos = [];
                if ($result_detalles) {
                    while ($detalle = $result_detalles->fetch_assoc()) {
                        $tabla_producto = $detalle['tabla_producto'];
                        $producto_id = $detalle['producto_id'];

                        // Hacemos una consulta dinámica en la tabla correspondiente para obtener el nombre del producto
                        $query_nombre_producto = "SELECT nombre FROM $tabla_producto WHERE id = $producto_id";
                        $result_nombre = $mysqli->query($query_nombre_producto);

                        if ($result_nombre && $producto = $result_nombre->fetch_assoc()) {
                            $nombre_producto = $producto['nombre'];
                            $productos[] = $detalle['cantidad'] . " x " . $nombre_producto;
                        }
                    }
                }
                //Separamos los productos por comas para la vista
                $productos_lista = implode(", ", $productos);

                echo "<tr>";
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
            echo "<p>No hay pagos registrados.</p>";
        }
        ?>
    </main>

    <?php include_once "footer.php"; ?>
</body>

</html>
