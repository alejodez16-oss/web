<?php


// Conectar a la base de datos
$conexion = new mysqli("localhost", "root", "", "servicio_tecnico");

// Verificar conexión
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

session_start();

    // Obtener los datos del formulario
$correo = $_POST['correo'];
$contrasena = $_POST['contrasena'];

// Encriptar la contraseña para compararla con la almacenada
$contrasena = hash('sha512', $contrasena);

// Consulta para validar usuario y obtener el rol
$validar_login = mysqli_query($conexion, "SELECT id_usuario, rol FROM usuarios WHERE correo='$correo' AND contrasena='$contrasena'");

// Verificar si el usuario existe
if (mysqli_num_rows($validar_login) > 0) {
    $datos_usuario = mysqli_fetch_assoc($validar_login);

    // Guardar el ID y el rol en la sesión
    $_SESSION['id_usuario'] = $datos_usuario['id_usuario'];
    $_SESSION['rol'] = $datos_usuario['rol'];

    // Redirigir según el rol
    if ($datos_usuario['rol'] === 'cliente') {
        header("Location: inicio.php");
    } elseif ($datos_usuario['rol'] === 'tecnico') {
        header("Location: inicio_tecnico.php");
    } elseif ($datos_usuario['rol'] === 'administrador') {
        header("Location: inicio_administrador.php");
    } else {
        echo '
            <script>
                alert("Rol desconocido. Contacta al administrador.");
                window.location = "inicio_sesion.php";
            </script>
        ';
    }
    exit;
} else {
    echo '
        <script>
            alert("Usuario o contraseña incorrectos, por favor verifica los datos.");
            window.location = "inicio_sesion.php";
        </script>
    ';
    exit;
}