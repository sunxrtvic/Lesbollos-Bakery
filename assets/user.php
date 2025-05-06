<?php
session_start();

if (isset($_SESSION['usuario'])) {
    header("Location: perfil.php");
    exit();
}

$conn = new mysqli("localhost", "root", "", "lesbollos");
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

$mensaje = "";

// Manejo del registro
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['registro'])) {
    $nombre = $_POST['nombre'];
    $apellidos = $_POST['apellidos'];
    $email = $_POST['mail'];
    $passHash = password_hash($_POST['contraseña'], PASSWORD_DEFAULT);
    $edad = $_POST['edad'];
    $telefono = $_POST['telefono'];
    $acepta = isset($_POST['acepta']) ? 1 : 0;

    // Comprobamos que si el usuario ya existe consultando el email en la base de datos
    $checkuser = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $checkuser->bind_param("s", $email);
    $checkuser->execute();
    $checkuser->store_result();

    if ($checkuser->num_rows > 0) {
        $mensaje = "⚠️ El correo ya está registrado.";
    } else {
        $stmt = $conn->prepare("INSERT INTO users (nombre, apellidos, email, contraseña, edad, telefono, acepta) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssssi", $nombre, $apellidos, $email, $passHash, $edad, $telefono, $acepta);
        if ($stmt->execute()) {
            //Si el registro se lleva a cabo, sale este mensaje
            $mensaje = "Registro realizado con éxito, puedes iniciar sesión.";
        } else {
            $mensaje = "Ha ocurrido un error con su registro, vuelva a introducir los datos.";
        }
        $stmt->close();
    }
    $checkuser->close();
}

// Manejo del inicio de sesión
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['login'])) {
    $email = $_POST['email'];
    $pass = $_POST['contraseña'];

    // Verificamos primero en la tabla de users
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows === 1) {
        $user = $resultado->fetch_assoc();
        if (password_verify($pass, $user['contraseña'])) {
            $user['tipo'] = 'user'; // ← Tipo de usuario
            $_SESSION['usuario'] = $user;
            header("Location: principal.php");
            exit();
        }
    }

    // Si no está en 'users', intentamos en 'admin'
    $stmt = $conn->prepare("SELECT * FROM admin WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows === 1) {
        $admin = $resultado->fetch_assoc();
        if ($pass === $admin['contraseña']) {
            $admin['tipo'] = 'admin';
            $_SESSION['usuario'] = $admin;
            header("Location: principal.php");
            exit();
        }
    }

    // Si no está en ninguna tabla
    $mensaje = "Usuario o contraseña incorrectos.";
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

        #registro {
            display: none;
        }

        .mensaje {
            color: #b55690;
            font-weight: bold;
            margin: 10px 0;
        }

        main{
            min-height: 100vh;
            width: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
        }
    </style>
</head>

<body>
<?php include_once ("encabezado.php");?>
    <main>
        <section class="principal">
            <h1 style="font-family: 'Dosis', sans-serif;">Regístrese o inicie sesión en nuestra web</h1>
            <hr>

            <?php if (!empty($mensaje)): ?>
                <div class="mensaje"><?= $mensaje ?></div>
            <?php endif; ?>

            <!-- Formulario de inicio de sesión -->
            <form id="inicio" method="POST" onsubmit="return validacionInicio();">
                <label>E-mail:</label>
                <input type="email" name="email" id="emailRegistrado" placeholder="example@hotmail.com" required>
                <label>Contraseña:</label>
                <input type="password" name="contraseña" id="contraseñaRegistrada" placeholder="Contraseña" required>
                <input type="submit" name="login" value="Iniciar sesión">
                <br><br>
                <a href="#" id="mostrarRegistro">¿No estás registrad@? Regístrate aquí</a>
            </form>

            <!-- Formulario de registro -->
            <form id="registro" method="POST" onsubmit="return validacionRegistro();">
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
                <input type="checkbox" name="acepta" id="acepta" value="1" required> Acepto
                <br><br>
                <input type="submit" value="Registrarse">
                <br><br>
                <a href="#" id="mostrarLogin">¿Ya estás registrad@? Inicia sesión</a>
            </form>
        </section>
    </main>

    <?php include_once ("footer.php"); ?>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        // Por defecto mostramos el form de inicio de sesión
        // Si el usuario clicka en el link lo ocultamos y mostramos el form de registro
        $(function () {
            $("#mostrarRegistro").click(function (event) {
                event.preventDefault();
                $("#inicio").hide();
                $("#registro").show();
            });

            $("#mostrarLogin").click(function (event) {
                event.preventDefault();
                $("#registro").hide();
                $("#inicio").show();
            });

            <?php if (isset($_POST['registro'])): ?>
                $("#registro").show();
                $("#inicio").hide();
            <?php endif; ?>
        });

        function validacionRegistro() {
            var pass = document.getElementById("contraseña").value;
            var nombre = document.getElementById("nombre").value;
            var apellidos = document.getElementById("apellidos").value;
            var email = document.getElementById("mail").value;
            var telefono = document.getElementById("telefono").value;
            var edad = document.getElementById("edad").value;

            // Comprobamos que no haya campos vacíos
            if (!nombre || !apellidos || !email || !pass || !telefono || !edad) {
                alert("Por favor, completa todos los campos.");
                return false;
            }

            // Validamos de la contraseña (mínimo debe tener 8 caracteres)
            if (pass.length < 8) {
                alert("La contraseña debe tener al menos 8 caracteres.");
                return false;
            }

            // Validamos de la longitud del teléfono para que sea de 9 dígitos
            if (telefono.length !== 9) {
                alert("El teléfono debe tener exactamente 9 dígitos.");
                return false;
            }

            var emailFormato = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
            if (!emailFormato.test(email)) {
                alert("Por favor, ingresa un e-mail válido.");
                return false;
            }

            // Validamos de la fecha de nacimiento para que no sea posterior a la fecha actual
            var fechaNacimiento = new Date(edad);
            var fechaActual = new Date();
            if (fechaNacimiento > fechaActual) {
                alert("La fecha de nacimiento no puede ser posterior a la fecha actual.");
                return false;
            }

            // Comprobamos si el usuario acepta la politica de privacidad 
            if (!document.getElementById("acepta").checked) {
                alert("Debes aceptar la política de privacidad.");
                return false;
            }

            return true;
        }

        function validacionInicio() {
            var email = document.getElementById("emailRegistrado").value;
            var pass = document.getElementById("contraseñaRegistrada").value;

            // Comprobamos que no haya campos vacíos al iniciar sesión
            if (!email || !pass) {
                alert("Por favor, debes completar todos los campos.");
                return false;
            }

            return true;
        }
    </script>
</body>

</html>