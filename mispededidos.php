<?php
session_start();
if (!isset($_SESSION['id_usuario'])) {
    header("Location: inicio_sesion.php");
    exit;
}

$conexion = new mysqli("localhost", "root", "", "servicio_tecnico");

if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

$id_usuario = $_SESSION['id_usuario'];

// Obtener pedidos del usuario
$query = "SELECT * FROM OrdenesServicio WHERE id_usuario = '$id_usuario' ORDER BY fecha_creacion DESC";
$resultado = $conexion->query($query);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mis Pedidos</title>
    <link rel="stylesheet" href="assites\css\style.css">
    <link rel="stylesheet" href="assites\css\mispedidos.css">
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

    <table>
        <tr class="titulo-tabla">
            <th colspan="8">Mis Pedidos</th>
        </tr>
        <tr>
            <th>ID Orden</th>
            <th>Cliente</th>
            <th>Tipo de Dispositivo</th>
            <th>Descripción</th>
            <th>Especificación</th>
            <th>Estado</th>
            <th>Técnico Asignado</th>
            <th>Fecha de Cita</th>
        </tr>

        <?php while ($pedido = $resultado->fetch_assoc()) { ?>
            <tr>
                <td><?php echo $pedido['id_orden']; ?></td>
                <td><?php echo isset($pedido['nombre_usuario']) ? $pedido['nombre_usuario'] : 'Usuario'; ?></td>
                <td><?php echo $pedido['tipo_dispositivo']; ?></td>
                <td><?php echo $pedido['descripcion_problema']; ?></td>
                <td>
                    <?php
                    if ($pedido['tipo_dispositivo'] === 'Consola') {
                        echo isset($pedido['especificacion_consola']) ? $pedido['especificacion_consola'] : 'N/A';
                    } else {
                        echo "N/A";
                    }
                    ?>
                </td>
                <td><?php echo $pedido['estado']; ?></td>
                <td><?php echo isset($pedido['nombre_tecnico']) ? $pedido['nombre_tecnico'] : 'Sin asignar'; ?></td>
                <td><?php echo isset($pedido['fecha_cita']) ? date('d/m/Y H:i', strtotime($pedido['fecha_cita'])) : 'No programada'; ?></td>
            </tr>
        <?php } ?>
    </table>

</body>
</html>

<?php $conexion->close(); ?>