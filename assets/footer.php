<footer>
    <div class="footer-contenedor">
        <div id="izquierda">
            <h3>Dónde puedes encontrarnos</h3>
            <img class="imagen" src="./imagenes/foto1.jpg">
            <p>LesBollos</p>
            <p>Avda. Sant Vicent Ferrer, 8</p>
            <p>Albatera, Alicante</p>
            <p>03340</p>
        </div>

        <div id="centro">
            <ul>
                <li><a href="./principal.php">Inicio</a></li>
                <li><a href="./pan.php">Pan Artesanal</a></li>
                <li><a href="./tartas.php">Tartas Caseras</a></li>
                <li><a href="./bolleria.php">Bollería Casera</a></li>
                <li><a href="./eco.php">Productos Ecológicos</a></li>
                <li><a href="./nosotros.php">Nosotros y nuestra granja</a></li>
            </ul>
        </div>

        <div id="derecha">
            <img src="./imagenes/th.jpg" class="circulo">
            <div class="horario">
                <h3>Horario</h3>
                <p>Lunes a Viernes de 08:00 a 20:00</p>
                <p>Sábados y Domingos de 09:00 a 14:00</p>
            </div>
        </div>
    </div>

    <div id="bajo">
        <div class="content">
            <div id="copyright">
                <i class="fa-regular fa-copyright"></i>
                <p>LesBollos</p>
            </div>
            <div id="clima">
                <p id="weather-info"></p>
            </div>
        </div>
    </div>

    <script>
        $(function () {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function (position) { //Pedimos permiso en el navegador para tomar la localización del usuario
                    var lat = position.coords.latitude;
                    var lon = position.coords.longitude;

                    $.ajax({
                        url: `https://api.open-meteo.com/v1/forecast`, //Comectamos la API del tiempo, en este caso estamos utilizando Open Meteo 
                        data: {
                            latitude: lat,
                            longitude: lon,
                            current_weather: true
                        },
                        success: function (data) {
                            var temp = data.current_weather.temperature;
                            var code = data.current_weather.weathercode;

                            // Relacionamos los códigos del clima con iconos de font-awesome para que se muestren al usuario
                            var iconosRefe = {
                                0: "fa-sun",
                                1: "fa-cloud-sun",
                                2: "fa-cloud",
                                3: "fa-cloud",
                                45: "fa-smog",
                                48: "fa-smog",
                                51: "fa-cloud-rain",
                                53: "fa-cloud-rain",
                                55: "fa-cloud-showers-heavy",
                                61: "fa-cloud-showers-heavy",
                                63: "fa-cloud-showers-heavy",
                                65: "fa-cloud-showers-heavy",
                                71: "fa-snowflake",
                                73: "fa-snowflake",
                                75: "fa-snowflake",
                                80: "fa-cloud-sun-rain",
                                81: "fa-cloud-showers-heavy",
                                82: "fa-cloud-showers-heavy",
                                95: "fa-bolt",
                                96: "fa-bolt",
                                99: "fa-bolt"
                            };

                            var iconos = iconosRefe[code] || "fa-question"; // En caso de que haya algún error, se le mostrará un icono de un signo de interrogación

                            // Aquí se va mostrando el icono y la temperatura
                            $('#clima').html(
                                `<i class="fa-solid ${iconos}"></i> ${temp}°C`
                            );
                        },
                        error: function () {
                            console.error("No se pudo obtener el clima de esta localizacion.");
                        }
                    });
                }, function () {
                    console.warn("No se pudo obtener la ubicación del usuario en este momento.");
                });
            } else {
                console.warn("Geolocalización no disponible.");
            }
        });
    </script>

</footer>