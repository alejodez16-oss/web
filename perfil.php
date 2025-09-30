<?php
session_start();

if (!isset($_SESSION['id_usuario'])) {
    header("Location: inicio_sesion.php");
    exit();
}

$conexion = new mysqli("localhost", "root", "", "servicio_tecnico");

if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

$id_usuario = $_SESSION['id_usuario'];
$mensaje = "";

// Obtener información del usuario
$query = "SELECT id_usuario, nombre, apellido, correo, telefono, direccion FROM usuarios WHERE id_usuario = $id_usuario";
$resultado = $conexion->query($query);
$usuario = $resultado->fetch_assoc();

// Procesar actualización del perfil
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['actualizar'])) {
    $nombre = $conexion->real_escape_string($_POST['nombre']);
    $apellido = $conexion->real_escape_string($_POST['apellido']);
    $telefono = $conexion->real_escape_string($_POST['telefono']);
    $direccion = $conexion->real_escape_string($_POST['direccion']);
    
    $query_update = "UPDATE usuarios SET nombre = '$nombre', apellido = '$apellido', telefono = '$telefono', direccion = '$direccion' WHERE id_usuario = $id_usuario";
    
    if ($conexion->query($query_update) === TRUE) {
        $mensaje = "Perfil actualizado correctamente.";
        // Actualizar datos en sesión
        $_SESSION['nombre'] = $nombre;
        // Recargar datos del usuario
        $resultado = $conexion->query($query);
        $usuario = $resultado->fetch_assoc();
    } else {
        $mensaje = "Error al actualizar el perfil: " . $conexion->error;
    }
}

$conexion->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Perfil - TecLine</title>
    <link rel="stylesheet" href="assites/css/style.css">
    <link rel="stylesheet" href="assites/css/perfil.css">
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
    
    <div class="container">
        <div class="form-container">
            <h1>Mi Perfil</h1>
            
            <?php if ($mensaje): ?>
                <div class="mensaje"><?php echo $mensaje; ?></div>
            <?php endif; ?>
            
            <form method="POST">
                <label for="nombre">Nombre:</label>
                <input type="text" id="nombre" name="nombre" value="<?php echo htmlspecialchars($usuario['nombre']); ?>" required>
                
                <label for="apellido">Apellido:</label>
                <input type="text" id="apellido" name="apellido" value="<?php echo htmlspecialchars($usuario['apellido']); ?>" required>
                
                <label for="correo">Correo electrónico:</label>
                <input type="email" id="correo" name="correo" value="<?php echo htmlspecialchars($usuario['correo']); ?>" disabled>
                <small>El correo electrónico no se puede modificar</small>
                
                <label for="telefono">Teléfono:</label>
                <input type="text" id="telefono" name="telefono" value="<?php echo htmlspecialchars($usuario['telefono']); ?>" required>
                
                <label for="direccion">Dirección:</label>
                <textarea id="direccion" name="direccion" rows="3"><?php echo htmlspecialchars($usuario['direccion']); ?></textarea>
                
                <button type="submit" name="actualizar">Actualizar Perfil</button>
            </form>
        </div>
    </div>
</body>
</html>