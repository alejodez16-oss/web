<?php
session_start();
if (!isset($_SESSION['id_usuario']) || $_SESSION['rol'] !== 'tecnico') {
    header("Location: inicio_sesion.php");
    exit;
}

$conexion = new mysqli("localhost", "root", "", "servicio_tecnico");

if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

$id_usuario = $_SESSION['id_usuario'];

// Obtener pedidos asignados al técnico
$query = "SELECT * FROM OrdenesServicio WHERE estado IN ('Pendiente', 'En proceso')";
$resultado = $conexion->query($query);

// Procesar actualización de estado
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id_orden']) && isset($_POST['nuevo_estado'])) {
    $id_orden = intval($_POST['id_orden']);
    $nuevo_estado = $conexion->real_escape_string($_POST['nuevo_estado']);

    $update_query = "UPDATE OrdenesServicio SET estado = '$nuevo_estado' WHERE id_orden = $id_orden";
    if ($conexion->query($update_query) === TRUE) {
        header("Location: pedidos_pendientes.php");
        exit;
    } else {
        echo "Error al actualizar el estado: " . $conexion->error;
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Órdenes Asignadas</title>
    <link rel="stylesheet" href="assites\css\style.css">
    <link rel="stylesheet" href="assites\css\mispedidos.css">
</head>
<body>

    <header class="header">
        <a href="inicio_tecnico.php" class="logo">
            <img src="https://img.icons8.com/?size=100&id=gvQUkpW15e1x&format=png&color=000000" alt="">
            <h2 class="nombre_">TecLine</h2>
        </a>
        <nav>
            <a href="inventario.php" class="btn_conocenos">Inventario</a>
            <a href="pedidos_pendientes.php" class="btn_conocenos">Órdenes Asignadas</a>
            <a href="perfil.php" class="btn_conocenos">Mi Perfil</a>
            <a href="cerrar_sesion.php" class="btn_conocenos">Cerrar Sesión</a>
        </nav>
    </header>  

    <table>
        <tr class="titulo-tabla">
            <th colspan="5">Órdenes Asignadas</th>
        </tr>
        <tr>
            <th>ID Orden</th>
            <th>Tipo de Dispositivo</th>
            <th>Descripción</th>
            <th>Estado</th>
            <th>Actualizar Estado</th>
        </tr>

        <?php while ($pedido = $resultado->fetch_assoc()) { ?>
            <tr>
                <td><?php echo htmlspecialchars($pedido['id_orden']); ?></td>
                <td><?php echo htmlspecialchars($pedido['tipo_dispositivo']); ?></td>
                <td><?php echo htmlspecialchars($pedido['descripcion_problema']); ?></td>
                <td><?php echo htmlspecialchars($pedido['estado']); ?></td>
                <td>
                    <div class="custom-select">
                        <form method="POST">
                            <input type="hidden" name="id_orden" value="<?php echo $pedido['id_orden']; ?>">
                            <select name="nuevo_estado">
                                <option value="Pendiente" <?php if ($pedido['estado'] == "Pendiente") echo "selected"; ?>>Pendiente</option>
                                <option value="En proceso" <?php if ($pedido['estado'] == "En proceso") echo "selected"; ?>>En proceso</option>
                                <option value="Finalizado" <?php if ($pedido['estado'] == "Finalizado") echo "selected"; ?>>Finalizado</option>
                            </select>
                            <button type="submit" class="nuevo_estadobutton">Actualizar</button>
                        </form>
                    </div>
                </td>
            </tr>
        <?php } ?>
    </table>

</body>
</html>

<?php $conexion->close(); ?>