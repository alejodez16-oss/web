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
    <title>Admin TecLine</title>
    <link rel="stylesheet" href="assites\css\style.css">
    <link rel="stylesheet" href="assites/css/introduccion.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
</head>
<body>

    <header class="header">
        <a href="inicio_administrador.php" class="logo">
            <img src="https://img.icons8.com/?size=100&id=gvQUkpW15e1x&format=png&color=000000" alt="">
            <h2 class="nombre_">TecLine</h2>
        </a>
        <NAv>
            <a href="admin_proveedores.php" class="btn_conocenos">Proveedores</a>
            <a href="a_inventario.php" class="btn_conocenos">Inventario</a>
            <a href="admin_usuarios.php" class="btn_conocenos">Usuarios</a>
            <a href="perfil.php" class="btn_conocenos">Mi Perfil</a>
            <a href="cerrar_sesion.php" class="btn_conocenos">Cerrar Sesion</a>
        </NAv>
    </header>         

    <section class="introduccion">
        <div class="contenido">
            <h2 class="titulo_intro">¡Bienvenido, Administrador de TecLine!</h2>
            <p class="texto_intro">
                Nos alegra tenerte de vuelta en la plataforma. Tu labor es esencial para garantizar que todo funcione de manera eficiente y sin contratiempos.
                Antes de comenzar, te recomendamos revisar el estado de los pedidos, verificar que los reportes estén actualizados y asegurarte de que cada solicitud esté siendo atendida correctamente. Tu dedicación mantiene en marcha el servicio y ayuda a brindar la mejor experiencia a nuestros técnicos y clientes.
                ¡Gracias por tu compromiso y excelente trabajo! 🚀 
            </p>
        </div>
    </section>

</body>
</html>