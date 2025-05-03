<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>LesBollos Bakery</title>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/5.0.0/normalize.min.css">
        <link rel="stylesheet" href="./css/styletartas.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    </head>

        <body>
           <?php
           include_once "encabezado.php";
           ?>
        
        <main>
            <section>
                <section>
                    <h1 style="font-family: 'Dosis', sans-serif;">Nuestras Tartas</h1>
                        <hr>
                        <section id="grid">
                            <article>
                                <img src="./imagenes/tartas/foto1.jpg">
                                <h2>Tarta 1</h2>
                                <p class ="subtitulo">MAGNICA KICTAN - LOREMIPSUM</p>
                                <p>Lorem ipsum, dolor sit amet consectetur adipisicing elit. Et consequuntur, blanditiis ullam placeat architecto,
                                     voluptate quaerat ipsam natus sed voluptatibus, magnam soluta qui facilis animi quibusdam voluptas ducimus eligendi?
                                      Facilis eos minus sed fugiat! Modi dolor quae ducimus nisi doloremque reiciendis nobis eos aperiam suscipit maxime,
                                       quis ipsum deserunt reprehenderit.</p>
                                       <button>Añadir al carrito</button>
                            </article>
            
                            <article>
                                <img src="./imagenes/tartas/foto2.jpg">
                                <h2>Tarta 2</h2>
                                <p class ="subtitulo">MAGNICA KICTAN - LOREMIPSUM</p>
                                <p>Lorem ipsum, dolor sit amet consectetur adipisicing elit. Et consequuntur, blanditiis ullam placeat architecto,
                                     voluptate quaerat ipsam natus sed voluptatibus, magnam soluta qui facilis animi quibusdam voluptas ducimus eligendi?
                                      Facilis eos minus sed fugiat! Modi dolor quae ducimus nisi doloremque reiciendis nobis eos aperiam suscipit maxime,
                                       quis ipsum deserunt reprehenderit.</p>
                                       <button>Añadir al carrito</button>
                            </article>
                            <article>
                                <img src="./imagenes/tartas/foto3.jpg">
                                <h2>Tarta 3</h2>
                                <p class ="subtitulo">MAGNICA KICTAN - LOREMIPSUM</p>
                                <p>Lorem ipsum, dolor sit amet consectetur adipisicing elit. Et consequuntur, blanditiis ullam placeat architecto,
                                     voluptate quaerat ipsam natus sed voluptatibus, magnam soluta qui facilis animi quibusdam voluptas ducimus eligendi?
                                      Facilis eos minus sed fugiat! Modi dolor quae ducimus nisi doloremque reiciendis nobis eos aperiam suscipit maxime,
                                       quis ipsum deserunt reprehenderit.</p>
                                       <button>Añadir al carrito</button>
                            </article>
            
                            <article>
                                <img src="./imagenes/tartas/foto4.jpg">
                                <h2>Tarta 4</h2>
                                <p>MAGNICA KICTAN - LOREMIPSUM</p>
                                <p class ="subtitulo">Lorem ipsum, dolor sit amet consectetur adipisicing elit. Et consequuntur, blanditiis ullam placeat architecto,
                                     voluptate quaerat ipsam natus sed voluptatibus, magnam soluta qui facilis animi quibusdam voluptas ducimus eligendi?
                                      Facilis eos minus sed fugiat! Modi dolor quae ducimus nisi doloremque reiciendis nobis eos aperiam suscipit maxime,
                                       quis ipsum deserunt reprehenderit.</p>
                                       <button>Añadir al carrito</button>
                            </article>
                        </section>
                </section>
        </main>

        <?php
        include_once "footer.php"
        ?>
        
    </body>
</html>