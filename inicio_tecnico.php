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
    <title>Tec Line</title>
    <link rel="stylesheet" href="assites\css\style.css">
    <link rel="stylesheet" href="assites/css/introduccion.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
</head>
<body>

    <header class="header">
        <a href="inicio_tecnico.php" class="logo">
            <img src="https://img.icons8.com/?size=100&id=gvQUkpW15e1x&format=png&color=000000" alt="">
            <h2 class="nombre_">TecLine</h2>
        </a>
        <NAv>
            <a href="inventario.php" class="btn_conocenos">Inventario</a>
            <a href="pedidos_pendientes.php" class="btn_conocenos">Ordenes Asignadas</a>
            <a href="perfil.php" class="btn_conocenos">Mi Perfil</a>
            <a href="cerrar_sesion.php" class="btn_conocenos">Cerrar Sesion</a>
        </NAv>
    </header>         

    <section class="introduccion">
        <div class="contenido">
            <h2 class="titulo_intro">¡Bienvenido a TecLine!</h2>
            <p class="texto_intro">
                Estamos encantados de tenerte aquí. Tu experiencia y dedicación son fundamentales para brindar un servicio excepcional. En TecLine, valoramos tu trabajo y confiamos en que juntos lograremos resolver cada desafío con eficiencia y calidad. ¡Gracias por ser parte de nuestro equipo! 💙🚀
            </p>
        </div>
    </section>

</body>
</html>