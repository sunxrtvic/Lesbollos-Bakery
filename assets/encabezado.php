<?php
session_start();
?>

<style>
    #logo {
        margin-left: 0;
    }

    #header {
        z-index: 1;
    }
</style>
<link rel="stylesheet" href="https://code.jquery.com/ui/1.14.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/jquery-3.7.1.js"></script>
<script src="https://code.jquery.com/ui/1.14.1/jquery-ui.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui@4.0/dist/fancybox.css" />
<script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@4.0/dist/fancybox.umd.js"></script>

<div id="reservas">
    <p>PEDIDOS 48H ANTES <i class="fa fa-phone"></i> 965488087
        <i class="fa fa-clock"></i>L-V 08:00 - 20:00 | S-D 09:00 - 14:00
    </p>
</div>

<div id="header">
    <img id="logo" src="./imagenes/logo/LesBollos_logo.png" alt="logo de LesBollos">
    <nav>
        <ul class="menu">
            <li><a href="./principal.php">Inicio</a></li>
            <li><a href="./pan.php">Pan Artesanal</a></li>
            <li><a href="./tartas.php">Tartas Caseras</a></li>
            <li><a href="./bolleria.php">Bollería Casera</a></li>
            <li><a href="./eco.php">Productos Ecológicos</a></li>
            <li id="ultimo"><a href="./nosotros.php">Nosotros y nuestra granja</a></li>
        </ul>
        <input type="checkbox" id="hamburguesa">
        <label for="hamburguesa" class="fa fa-bars" id="icono"></label>
</div>

<div>
    <ul class="menu-iconos">
        <li><i class="fa fa-magnifying-glass"></i>
            <form class="search-form">
                <input type="search" id="search" name="search" placeholder="Buscar...">
            </form>
        </li>
        <li><a href="#" id="viewmode"><i class="fa fa-moon"></i></a></li>

        <?php if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['tipo'] !== 'admin'): ?>
            <!-- Mostramos estos iconos solo si no es un admin -->
            <li><a href="./user.php"><i class="fa-regular fa-circle-user"></i></a></li>
            <li><a href="./carrito.php"><i class="fa-solid fa-cart-shopping"></i></a></li>
        <?php endif; ?>

        <?php if (isset($_SESSION['usuario']) && $_SESSION['usuario']['tipo'] === 'admin'): ?>
            <!--Icono exclusivo para admins del panel de control-->
            <li><a href="./admin-panel.php"><i class="fa-solid fa-users-gear"></i></a></li>
        <?php endif; ?>
    </ul>

</div>
</nav>