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

// Obtener la lista de proveedores
$query = "SELECT id_proveedor, nombre, correo, telefono FROM proveedores";
$resultado = $conexion->query($query);

if (!$resultado) {
    die("Error en la consulta SQL: " . $conexion->error);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Administrar Proveedores</title>
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

    <table border="1">
        <tr class="titulo-tabla">
            <th colspan="5">Lista de Proveedores</th>
        </tr>
        <tr>
            <th>ID Proveedor</th>
            <th>Nombre</th>
            <th>Correo</th>
            <th>Teléfono</th>
            <th>Acciones</th>
        </tr>

        <?php while ($fila = $resultado->fetch_assoc()) { ?>
        <tr>
            <td><?php echo $fila['id_proveedor']; ?></td>
            <td>
                <form action="p_admin_proveedores.php" method="post">
                    <input type="hidden" name="id_proveedor" value="<?php echo $fila['id_proveedor']; ?>">
                    <input type="text" name="nombre_proveedor" value="<?php echo $fila['nombre']; ?>" required>
            </td>
            <td>
                <input type="email" name="correo" value="<?php echo $fila['correo']; ?>" required>
            </td>
            <td>
                <input type="text" name="telefono" value="<?php echo $fila['telefono']; ?>" required>
            </td>
            <td>
                <button type="submit" name="editar" class="editar">Actualizar</button>
                </form>
                <a href="p_admin_proveedores.php?eliminar=<?php echo $fila['id_proveedor']; ?>" 
                   onclick="return confirm('¿Seguro que deseas eliminar este proveedor?');" class="editar">
                   Eliminar
                </a>
            </td>
        </tr>
        <?php } ?>
    </table>

    <form action="p_admin_proveedores.php" method="post">
        <h3>Nuevo Proveedor</h3>
        <input type="text" name="nombre_proveedor" placeholder="Nombre" required>
        <input type="email" name="correo" placeholder="Correo electrónico" required>
        <input type="text" name="telefono" placeholder="Teléfono" required>
        <button type="submit" name="agregar" class="editar">Guardar</button>
    </form> 

</body>
</html>

<?php $conexion->close(); ?>