<?php
session_start();
include_once "encabezado.php";

// Redirigir si ya ha iniciado sesión
if (isset($_SESSION['usuario'])) {
    header("Location: principal.php");
    exit();
}

// Conexión a la base de datos
$conn = new mysqli("localhost", "root", "", "lesbollos");
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

$mensaje = "";

// REGISTRO
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['registro'])) {
    $nombre = $_POST['nombre'];
    $apellidos = $_POST['apellidos'];
    $email = $_POST['mail'];
    $passHash = password_hash($_POST['contraseña'], PASSWORD_DEFAULT);
    $edad = $_POST['edad'];
    $telefono = $_POST['telefono'];
    $acepta = isset($_POST['cookies']) ? 1 : 0;

    $check = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $check->bind_param("s", $email);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {
        $mensaje = "⚠️ El correo ya está registrado.";
    } else {
        $stmt = $conn->prepare("INSERT INTO users (nombre, apellidos, email, contraseña, edad, telefono, acepta) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssssi", $nombre, $apellidos, $email, $passHash, $edad, $telefono, $acepta);
        if ($stmt->execute()) {
            $mensaje = "✅ Registro exitoso. Ya puedes iniciar sesión.";
        } else {
            $mensaje = "❌ Error al registrar.";
        }
        $stmt->close();
    }
    $check->close();
}

// INICIO DE SESIÓN
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['login'])) {
    $email = $_POST['email'];
    $pass = $_POST['contraseña'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($res->num_rows === 1) {
        $user = $res->fetch_assoc();
        if (password_verify($pass, $user['contraseña'])) {
            $_SESSION['usuario'] = $user;
            header("Location: principal.php");
            exit();
        } else {
            $mensaje = "❌ Contraseña incorrecta.";
        }
    } else {
        $mensaje = "❌ Usuario no encontrado.";
    }
    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>LesBollos Bakery</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/5.0.0/normalize.min.css">
    <link rel="stylesheet" href="./css/styleuser.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <style>
        input[type="date"],
        input[type="password"],
        input[type="email"],
        input[type="text"],
        input[type="number"] {
            width: 100%;
            padding: 8px;
            margin: 5px 0 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            color: #999;
        }
        #registro { display: none; }
        .mensaje { color: red; font-weight: bold; margin: 10px 0; }
    </style>
</head>
<body>

<main>
    <section class="principal">
        <h1 style="font-family: 'Dosis', sans-serif;">Regístrese o inicie sesión en nuestra web</h1>
        <hr>

        <?php if (!empty($mensaje)): ?>
            <div class="mensaje"><?= $mensaje ?></div>
        <?php endif; ?>

        <!-- Formulario de inicio de sesión -->
        <form id="inicio" method="POST" onsubmit="return validarLogin();">
            <label>E-mail:</label>
            <input type="email" name="email" id="emailRegistrado" placeholder="example@hotmail.com" required>
            <label>Contraseña:</label>
            <input type="password" name="contraseña" id="contraseñaRegistrada" placeholder="Contraseña" required>
            <input type="submit" name="login" value="Iniciar sesión">
            <br><br>
            <a href="#" id="mostrarRegistro">¿No estás registrad@? Regístrate aquí</a>
        </form>

        <!-- Formulario de registro -->
        <form id="registro" method="POST" onsubmit="return validarRegistro();">
            <input type="hidden" name="registro" value="1">
            <label>Nombre:</label>
            <input type="text" name="nombre" id="nombre" required>
            <label>Apellidos:</label>
            <input type="text" name="apellidos" id="apellidos" required>
            <label>E-mail:</label>
            <input type="email" name="mail" id="mail" required>
            <label>Contraseña:</label>
            <input type="password" name="contraseña" id="contraseña" required placeholder="Mínimo 8 caracteres">
            <label>Edad:</label>
            <input type="date" name="edad" id="edad" required>
            <label>Teléfono:</label>
            <input type="number" name="telefono" id="telefono" required>
            <label>¿Cómo conociste nuestra panadería?</label>
            <select name="consulta" id="consulta" required>
                <option value="conocidos">De algún conocido</option>
                <option value="redes" selected>Por redes sociales</option>
                <option value="publicidad">Por publicidad</option>
                <option value="otros">Otros</option>
            </select>
            <br>
            <label>Acepta usted la política de privacidad y cookies</label><br>
            <input type="checkbox" name="cookies" id="cookies" value="1" required> Acepto
            <br><br>
            <input type="submit" value="Registrarse">
            <br><br>
            <a href="#" id="mostrarLogin">¿Ya estás registrad@? Inicia sesión</a>
        </form>
    </section>
</main>

<?php include_once "footer.php"; ?>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(function () {
        $("#mostrarRegistro").click(function (e) {
            e.preventDefault();
            $("#inicio").hide();
            $("#registro").show();
        });

        $("#mostrarLogin").click(function (e) {
            e.preventDefault();
            $("#registro").hide();
            $("#inicio").show();
        });

        <?php if (isset($_POST['registro'])): ?>
            $("#registro").show();
            $("#inicio").hide();
        <?php endif; ?>
    });

    function validarRegistro() {
        const pass = document.getElementById("contraseña").value;
        if (pass.length < 8) {
            alert("La contraseña debe tener al menos 8 caracteres.");
            return false;
        }
        if (!document.getElementById("cookies").checked) {
            alert("Debe aceptar la política de privacidad.");
            return false;
        }
        return true;
    }

    function validarLogin() {
        const email = document.getElementById("emailRegistrado").value;
        const pass = document.getElementById("contraseñaRegistrada").value;
        if (!email || !pass) {
            alert("Por favor, completa todos los campos.");
            return false;
        }
        return true;
    }
</script>
</body>
</html>
