<?php
session_start();
if (!isset($_SESSION['id_usuario'])) {
    echo '
        <script>
            alert("TE ATRAPAMOS!! DEBES INICIAR SESION");
            window.location = "inicio_sesion.php";
        </script>
    ';
    session_destroy();
    die();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TecLine</title>
    <link rel="stylesheet" href="assites\css\style.css">
    <link rel="stylesheet" href="assites/css/introduccion.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
</head>
<body>

    <header class="header">
        <a href="inicio.php" class="logo">
            <img src="https://img.icons8.com/?size=100&id=gvQUkpW15e1x&format=png&color=000000" alt="">
            <h2 class="nombre_">TecLine</h2>
        </a>
        <NAv>
            <a href="Pedido_N.php" class="btn_conocenos">Realizar Pedido</a>
            <a href="mispededidos.php" class="btn_conocenos">Mis Pedidos</a>
            <a href="perfil.php" class="btn_conocenos">Mi Perfil</a>
            <a href="cerrar_sesion.php" class="btn_conocenos">Cerrar Sesion</a>
        </NAv>
    </header>         

    <!-- Carrusel -->
    <div class="carousel-container">
        <div class="carousel">
            <div class="carousel-images">
                <img src="assites\imagenes\Microsoft buys two more video game studios.jpg" alt="Imagen 1">
                <img src="assites\imagenes\Os desenvolvedores estão irritados com hardware fraco do Xbox Series S.jpg" alt="Imagen 2">
                <img src="assites\imagenes\PS5.jpg" alt="Imagen 3">
            </div>
            <div class="carousel-text">Texto para la imagen 1</div>
            <div class="carousel-nav">
                <button class="prev" onclick="moveSlide(-1)">&#10094;</button>
                <button class="next" onclick="moveSlide(1)">&#10095;</button>
            </div>
        </div>
    </div>

    <script>
        let currentSlide = 0;
        const slides = document.querySelector('.carousel-images');
        const texts = [
            'Los mantenimientos a tus equipos electronicos se debe hacer anualmente',
            'La compañia Microsoft creo la primera xbox en el año 2002',
            'Sony tiene el record de la consola más vendida de la historia siendo la Ps4'
        ];
        const carouselText = document.querySelector('.carousel-text');

        const totalSlides = slides.children.length;

        function moveSlide(direction) {
            currentSlide = (currentSlide + direction + totalSlides) % totalSlides;
            slides.style.transform = `translateX(-${currentSlide * 100}%)`;
            carouselText.textContent = texts[currentSlide];
        }

        setInterval(() => {
            moveSlide(1);
        }, 5000);

        moveSlide(0);
    </script>

    <section class="introduccion">
        <div class="contenido">
            <h2 class="titulo_intro">¡Gracias por confiar en TecLine!</h2>
            <p class="texto_intro">
                Apreciamos tu preferencia y confianza en nuestros servicios. En TecLine, nos esforzamos por brindarte la mejor experiencia en mantenimiento y soporte técnico. ¡Tu satisfacción es nuestra prioridad!
                ¡Somos los mejores del mundo en mantenimiento y seguimos trabajando para ti! 🚀🔧
            </p>
        </div>
    </section>

</body>
</html>