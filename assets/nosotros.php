<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>LesBollos Bakery</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/5.0.0/normalize.min.css">
    <link rel="stylesheet" href="./css/stylenosotros.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
</head>

<body>

    <?php
    include_once "encabezado.php"
        ?>

    <main>
        <section>
            <section>
                <h1 style="font-family: 'Dosis', sans-serif;">Nosotros y Nuestra Granja</h1>
                <hr>
                <img src="./imagenes/foto4.jpg">
                <p>Lorem ipsum odor amet, consectetuer adipiscing elit. Enim consectetur lorem proin ac dictum pulvinar
                    at massa?
                    Integer inceptos fringilla phasellus suscipit posuere lectus sapien. Eu laoreet mauris conubia
                    porta, ut vehicula inceptos.
                    Molestie leo mus tempus curabitur urna dis eros. Dolor pharetra amet quisque sed mollis conubia.
                    Eros inceptos suscipit efficitur;
                    rhoncus bibendum quis posuere.</p>

                <p>Lorem ipsum odor amet, consectetuer adipiscing elit. Rutrum mauris vivamus efficitur; class amet
                    bibendum.
                    Tempus sem rhoncus porta etiam hac class. Ultricies vel aenean nisl sociosqu nunc tincidunt enim.
                    Turpis sociosqu adipiscing ac suscipit
                    senectus tempor, varius gravida morbi. Varius volutpat quisque hac dui condimentum rutrum. Erat
                    netus mattis ante maximus mattis ac
                    aliquet ac. Magna vehicula molestie, sollicitudin aptent interdum hendrerit mi rutrum.</p>
            </section>
    </main>

    <?php
    include_once "footer.php";
    ?>

</body>
<script>
    // Modo día y modo noche usando addClass y removeClass junto a JQuery
    $(function () {
        // Aplica el modo guardado al cargar la página
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

</html>