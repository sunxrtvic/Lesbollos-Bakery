<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>LesBollos Bakery</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/5.0.0/normalize.min.css">
    <link rel="stylesheet" href="./css/styleprincipal.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5/dist/fancybox/fancybox.css" />
    <script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5/dist/fancybox/fancybox.umd.js"></script>
</head>

<body>
    <?php
    include_once "encabezado.php";
    ?>

    <main>
        <section id="imagen">
            <h2 style="color: white; font-family:'Dosis', sans-serif">La mejor calidad al alcance de todos</h2>
            <h1 style="color: white; font-family:'Dosis', sans-serif">Panadería artesanal y ecológica</h1>
            <p style="color: white; font-family:'Dosis', sans-serif">Garantía de que nuestros productos siempre son
                frescos y horneados con los mejores
                ingredientes, recién cosechados de nuestra propia granja</p>
        </section>

        <section class="principal">
            <h1 style="font-family: 'Dosis', sans-serif; margin-top: 0;">¡Bienvenido a LesBollos Bakery!</h1>
            <hr>
            <p>Lorem ipsum odor amet, consectetuer adipiscing elit. Enim consectetur lorem proin ac dictum pulvinar at
                massa?
                Integer inceptos fringilla phasellus suscipit posuere lectus sapien. Eu laoreet mauris conubia porta, ut
                vehicula inceptos.
                Molestie leo mus tempus curabitur urna dis eros. Dolor pharetra amet quisque sed mollis conubia. Eros
                inceptos suscipit efficitur;
                rhoncus bibendum quis posuere.</p>

            <p>Lorem ipsum odor amet, consectetuer adipiscing elit. Rutrum mauris vivamus efficitur; class amet
                bibendum.
                Tempus sem rhoncus porta etiam hac class. Ultricies vel aenean nisl sociosqu nunc tincidunt enim. Turpis
                sociosqu adipiscing ac suscipit
                senectus tempor, varius gravida morbi. Varius volutpat quisque hac dui condimentum rutrum. Erat netus
                mattis ante maximus mattis ac
                aliquet ac. Magna vehicula molestie, sollicitudin aptent interdum hendrerit mi rutrum.</p>

            <p>Lorem ipsum odor amet, consectetuer adipiscing elit. Felis fringilla vulputate vulputate; elit morbi
                proin.
                Fusce vitae ornare neque molestie adipiscing vel lacus. Placerat senectus per purus fames; tincidunt
                malesuada.
                Primis himenaeos torquent facilisi augue; scelerisque pulvinar curae.</p>
            <br>
            <div id="galeria">
                <!-- Solo se muestra en la página esta imagen -->
                <a href="./imagenes/2.jpg" data-fancybox="galeria" title="uno">
                    <img src="./imagenes/2.jpg" alt="uno">
                </a>

                <!-- Estas están ocultas para la página pero se mostrarán en la interfaz de fancybox -->
                <a href="./imagenes/1.jpg" data-fancybox="galeria" title="dos" style="display: none;">
                    <img src="./imagenes/1.jpg" alt="dos">
                </a>
                <a href="./imagenes/3.jpg" data-fancybox="galeria" title="tres" style="display: none;">
                    <img src="./imagenes/3.jpg" alt="tres">
                </a>
            </div>
        </section>
    </main>

    <?php
    include_once "footer.php";
    ?>

    <script>
        //Funcion para el Fancybox
        $(function () {
            Fancybox.bind('[data-fancybox="galeria"]', {
                Toolbar: {
                    display: ["slideshow", "thumbs", "close"]
                },
                Thumbs: {
                    autoStart: false
                },
                Carousel: {
                    infinite: true
                }
            });
        });

        // Modo día y modo noche usando addClass y removeClass junto a JQuery
        $(function () {
            // Aplicar el modo guardado al cargar la página
            if (localStorage.getItem("modo") === "noche") {
                var logo_dia = $("#logo");
                var logo_noche = $("#logo1");
                var body = $("body");
                var icon = $("#viewmode i");
                var header = $("#header");
                var inicio = $("#inicio");
                var registro = $("#registro");
                var principal = $(".principal");
                var articles = $("article");
                var forms = $("form");
                var icon_user = $(".fa-circle-user");
                var icon_shop = $(".fa-cart-shopping");
                var icon_search = $(".fa-magnifying-glass");
                var icon_admin = $(".fa-users-gear");

                body.addClass("noche");
                header.addClass("nocheHeader");
                inicio.addClass("noche2");
                registro.addClass("noche2");
                principal.addClass("noche");
                articles.addClass("noche2");
                icon_user.addClass("nocheIconos");
                icon_shop.addClass("nocheIconos");
                icon_search.addClass("nocheIconos");
                icon_admin.addClass("nocheIconos");
                icon.addClass("nocheIconos");
                logo_dia.hide();
                logo_noche.show();
                icon.removeClass("fa-moon").addClass("fa-sun");
            }
            //Cuando el usuario clicka el botón de cambio de modo..
            $("#viewmode").click(function () {
                var logo_dia = $("#logo");
                var logo_noche = $("#logo1");
                var body = $("body");
                var icon = $("#viewmode i");
                var header = $("#header");
                var inicio = $("#inicio");
                var registro = $("#registro");
                var principal = $(".principal");
                var articles = $("article");
                var forms = $("form");
                var icon_user = $(".fa-circle-user");
                var icon_shop = $(".fa-cart-shopping");
                var icon_search = $(".fa-magnifying-glass");
                var icon_admin = $(".fa-users-gear");

                if (body.hasClass("noche")) {
                    body.removeClass("noche");
                    header.removeClass("nocheHeader");
                    inicio.removeClass("noche2");
                    registro.removeClass("noche2");
                    principal.removeClass("noche");
                    articles.removeClass("noche2");
                    icon_user.removeClass("nocheIconos");
                    icon_shop.removeClass("nocheIconos");
                    icon_search.removeClass("nocheIconos");
                    icon_admin.removeClass("nocheIconos");
                    icon.removeClass("nocheIconos");
                    logo_dia.show();
                    logo_noche.hide();
                    icon.removeClass("fa-sun").addClass("fa-moon");
                    localStorage.setItem("modo", "dia");
                } else {
                    body.addClass("noche");
                    header.addClass("nocheHeader");
                    inicio.addClass("noche2");
                    registro.addClass("noche2");
                    principal.addClass("noche");
                    articles.addClass("noche2");
                    icon_user.addClass("nocheIconos");
                    icon_shop.addClass("nocheIconos");
                    icon_search.addClass("nocheIconos");
                    icon_admin.addClass("nocheIconos");
                    icon.addClass("nocheIconos");
                    logo_dia.hide();
                    logo_noche.show();
                    icon.removeClass("fa-moon").addClass("fa-sun");

                    localStorage.setItem("modo", "noche");
                }
            });
        });

    </script>

</body>

</html>