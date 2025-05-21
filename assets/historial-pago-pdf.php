<?php
session_start();
require('fpdf/fpdf.php');

if (!isset($_SESSION['usuario']) || !isset($_SESSION['usuario']['id'])) {
    echo "<p>Debes iniciar sesión para ver tu historial de pagos.</p>";
    exit;
}

$usuario_autenticado = $_SESSION['usuario']['id'];

$mysqli = new mysqli("localhost", "root", "", "lesbollos");
if ($mysqli->connect_error) {
    die("Error de conexión: " . $mysqli->connect_error);
}

//Función para la paginación del pdf
class PDF extends FPDF
{
    function Footer()
    {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->AliasNbPages(); // Habilita el uso de {nb} para la paginación
        $this->Cell(0, 10, 'Pag ' . $this->PageNo() . ' / {nb}', 0, 0, 'R');
    }
}

$pdf = new PDF(); 
$pdf->AliasNbPages(); // Con esto podemos paginar el pdf
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 14);
$pdf->Cell(0, 10, utf8_decode('Historial de Pagos'), 0, 1, 'C');
$pdf->Ln(5);

// Encabezados
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(20, 10, 'Pedido', 1);
$pdf->Cell(70, 10, 'Productos', 1);
$pdf->Cell(20, 10, 'Cant.', 1);
$pdf->Cell(30, 10, 'Total', 1);
$pdf->Cell(50, 10, 'Fecha', 1);
$pdf->Ln();

$cellHeight = 10;
$query = "SELECT id, total, fecha FROM pagos WHERE user_id = $usuario_autenticado ORDER BY fecha DESC";
$result = $mysqli->query($query);

$pdf->SetFont('Arial', '', 9);

while ($row = $result->fetch_assoc()) {
    $id = $row['id'];
    $total = number_format($row['total'], 2);
    $fecha = $row['fecha'];

    $query_productos = "SELECT SUM(cantidad) AS cantidad_total FROM detalles_pedido WHERE pedido_id = $id";
    $cantidad_total = 0;
    $res_prod = $mysqli->query($query_productos);
    if ($res_prod && $res_row = $res_prod->fetch_assoc()) {
        $cantidad_total = $res_row['cantidad_total'];
    }

    $query_detalles = "SELECT cantidad, tabla_producto, producto_id FROM detalles_pedido WHERE pedido_id = $id";
    $productos = [];
    $res_detalles = $mysqli->query($query_detalles);
    if ($res_detalles) {
        while ($detalle = $res_detalles->fetch_assoc()) {
            $tabla = $detalle['tabla_producto'];
            $prod_id = $detalle['producto_id'];
            $nombre_query = $mysqli->query("SELECT nombre FROM $tabla WHERE id = $prod_id");
            if ($nombre_query && $nombre = $nombre_query->fetch_assoc()) {
                $productos[] = $detalle['cantidad'] . ' x ' . $nombre['nombre'];
            }
        }
    }

    $productos_str = implode(", ", $productos);

    if ($pdf->GetY() + $cellHeight > 250) {
        $pdf->AddPage();
    }

    $pdf->Cell(20, $cellHeight, "#$id", 1);
    $pdf->Cell(70, $cellHeight, utf8_decode(substr($productos_str, 0, 45) . (strlen($productos_str) > 50 ? "...": "")), 1);
    $pdf->Cell(20, $cellHeight, $cantidad_total, 1);
    $pdf->Cell(30, $cellHeight, number_format($total, 2) . chr(128), 1);
    $pdf->Cell(50, $cellHeight, utf8_decode($fecha), 1);
    $pdf->Ln();
}

$pdf->Output('D', 'historial_pagos.pdf');
?>
