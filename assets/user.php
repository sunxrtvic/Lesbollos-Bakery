<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>LesBollos Bakery</title>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/5.0.0/normalize.min.css">
        <link rel="stylesheet" href="./css/styleuser.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
        <style>
            input[type="date"]{
                width: 100%;
                padding: 8px;
                margin: 5px 0 10px;
                border: 1px solid #ddd;
                border-radius: 5px;
                color: #999;
            }

            input[type="password"]{
                width: 100%;
                padding: 8px;
                margin: 5px 0 10px;
                border: 1px solid #ddd;
                border-radius: 5px;
                color: #999;
            }
        </style>
    </head>

        <body>
            <?php
            include_once "encabezado.php";
            ?>
        
        <main>
            <section>
                
            </section>

            <section class="principal">
                <h1 style="font-family: 'Dosis', sans-serif;">Regístrese o inice sesión en nuestra web</h1>
                <hr>

                <form id="inicio">
                    <label>E-mail: </label>
                    <input type="text" id="emailRegistrado" placeholder="example@hotmail.com">
                    <br>
                    <label>Contraseña</label>
                    <input type="password" id="contraseñaRegistrada" placeholder="Contraseña">
                    <br>
                    <input type="submit" id="iniciar" value="Iniciar sesión">
                    <br><br>
                    <a href="#" id="mostrarRegistro">¿No estás registrad@? Regístrate aquí</a>
                </form>

                <form>
                    <label>Nombre</label>
                    <input type="text" name="nombre" id="nombre" required placeholder="Nombre">
                    <br>
                    <label>Apellidos</label>
                    <input type="text" name="apellidos" id="apellidos" required placeholder="Apellidos">
                    <br>
                    <label>E-mail</label>
                    <input type="email" name="mail" id="mail" required placeholder="example@hotmail.com">
                    <br>
                    <label>Contraseña</label>
                    <input type="password" name="contraseña" id="contraseña" required placeholder="Mínimo 8 caracteres">
                    <br>
                    <label>Edad</label>
                    <input type="date" name="edad" id="edad" required>
                    <br>
                    
                    <label>Telefono</label>
                    <input type="number" name="telefono" id="telefono" required placeholder="Telefono">
                    <br>
                    <label>¿Como conociste nuestra panadería?</label>
                    <select name="consulta" id="consulta" required>
                        <option value="conocidos">De algún conocido</option>
                        <option value="redes" selected>Por redes sociales</option>
                        <option value="publicidad">Por publicidad</option>
                        <option value="otros">Otros</option>
                    </select>
                    <br>
                    <label>Acepta usted la política de privacidad y cookies</label>
                    <label id="checkbox">Sí</label>
                    <input type="checkbox" name="cookies" id="cookies" value="si" required>
                    <label id="checkbox"> No</label>
                    <input type="checkbox" name="no" id="no" value="no" >
                    <br>
                    <input type="submit" value="Registrarse">
                    <br><br>
                    <a href=>¿Ya está registrad@? Inice sesión</a>
                </form>
            </section>
        </main>

        <?php
            include_once "footer.php";
        ?>
        
    </body>
</html>