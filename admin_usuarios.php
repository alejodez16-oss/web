<?php
session_start();
if (!isset($_SESSION['id_usuario']) || $_SESSION['rol'] !== 'administrador') {
    header("Location: inicio_sesion.php");
    exit;
}

$conexion = new mysqli("localhost", "root", "", "servicio_tecnico");

if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

// Obtener la lista de usuarios
$query = "SELECT id_usuario, nombre, apellido, correo, telefono, rol FROM usuarios";
$resultado = $conexion->query($query);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Administrar Usuarios</title>
    <link rel="stylesheet" href="assites\css\style.css">
    <link rel="stylesheet" href="assites\css\mispedidos.css">
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

    <table id="tabla-usuarios" border="1">
        <tr class="titulo-tabla">
            <th colspan="7">Gestión de Usuarios</th>
        </tr>
        <tr>
            <th>ID Usuario</th>
            <th>Nombre</th>
            <th>Apellido</th>
            <th>Correo</th>
            <th>Teléfono</th>
            <th>Rol</th>
            <th>Acciones</th>
        </tr>

        <?php while ($fila = $resultado->fetch_assoc()) { ?>
        <tr>
            <td><?php echo $fila['id_usuario']; ?></td>
            <td><?php echo $fila['nombre']; ?></td>
            <td><?php echo $fila['apellido']; ?></td>
            <td><?php echo $fila['correo']; ?></td>
            <td><?php echo $fila['telefono']; ?></td>
            <td>
                <form action="p_admin_usuarios.php" method="post">
                    <input type="hidden" name="id_usuario" value="<?php echo $fila['id_usuario']; ?>">
                    <select name="rol">
                        <option value="administrador" <?php echo ($fila['rol'] === 'administrador') ? 'selected' : ''; ?>>Administrador</option>
                        <option value="tecnico" <?php echo ($fila['rol'] === 'tecnico') ? 'selected' : ''; ?>>Técnico</option>
                        <option value="cliente" <?php echo ($fila['rol'] === 'cliente') ? 'selected' : ''; ?>>Cliente</option>
                    </select>
                    <button type="submit" name="editar" class="editar">Actualizar</button>
                </form>
            </td>
            <td>
                <a href="p_admin_usuarios.php?eliminar=<?php echo $fila['id_usuario']; ?>" 
                   onclick="return confirm('¿Seguro que deseas eliminar este usuario?');" class="editar">
                   Eliminar
                </a>
            </td>
        </tr>
        <?php } ?>
    </table>

</body>
</html>

<?php $conexion->close(); ?>