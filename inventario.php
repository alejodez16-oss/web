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

// Obtener repuestos disponibles
$query_repuestos = "SELECT * FROM inventariorepuestos";
$resultado_repuestos = $conexion->query($query_repuestos);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Inventario de Repuestos</title>
    <link rel="stylesheet" href="assites\css\style.css">
    <link rel="stylesheet" href="assites\css\mispedidos.css">
</head>
<body>

    <header class="header">
        <a href="inicio_tecnico.php" class="logo">
            <img src="https://img.icons8.com/?size=100&id=gvQUkpW15e1x&format=png&color=000000" alt="">
            <h2 class="nombre_">TecLine</h2>
        </a>
        <NAv>
            <a href="inventario.php" class="btn_conocenos">Inventario</a>
            <a href="pedidos_pendientes.php" class="btn_conocenos">Órdenes Asignadas</a>
            <a href="perfil.php" class="btn_conocenos">Mi Perfil</a>
            <a href="cerrar_sesion.php" class="btn_conocenos">Cerrar Sesión</a>
        </NAv>
    </header>  

    <table>
        <tr class="titulo-tabla">
            <th colspan="3">Inventario de Repuestos</th>
        </tr>
        <tr>
            <th>ID Repuesto</th>
            <th>Nombre</th>
            <th>Cantidad Disponible</th>
        </tr>

        <?php while ($repuesto = $resultado_repuestos->fetch_assoc()) { ?>
            <tr>
                <td><?php echo htmlspecialchars($repuesto['id_repuesto']); ?></td>
                <td><?php echo htmlspecialchars($repuesto['nombre_repuesto']); ?></td>
                <td><?php echo htmlspecialchars($repuesto['cantidad_disponible']); ?></td>
            </tr>
        <?php } ?>
    </table>

    <form action="p_inventario.php" method="POST" onsubmit="return verificarSeleccion()">
        <table>
            <tr class="titulo-tabla">
                <th colspan="4">¿Qué Vas A Utilizar?</th>
            </tr>
            <tr>
                <th>Seleccionar</th>
                <th>Nombre Repuesto</th>
                <th>Cantidad Disponible</th>
                <th>Cantidad a Usar</th>
            </tr>

            <?php
            $resultado_repuestos->data_seek(0);
            while ($repuesto = $resultado_repuestos->fetch_assoc()) { ?>
                <tr>
                    <td>
                        <input type="checkbox" name="repuestos[<?php echo $repuesto['id_repuesto']; ?>]" value="<?php echo $repuesto['id_repuesto']; ?>">
                    </td>
                    <td><?php echo htmlspecialchars($repuesto['nombre_repuesto']); ?></td>
                    <td><?php echo htmlspecialchars($repuesto['cantidad_disponible']); ?></td>
                    <td>
                        <input type="number" id="cantidad-<?php echo $repuesto['id_repuesto']; ?>" name="repuestos[<?php echo $repuesto['id_repuesto']; ?>]" min="1" max="<?php echo $repuesto['cantidad_disponible']; ?>">
                    </td>
                </tr>
            <?php } ?>
        </table>
        <button type="submit" class="nuevo_estadobutton">Usar Repuestos</button>
    </form>

    <script>
        function verificarSeleccion() {
            let checkboxes = document.querySelectorAll('input[type="checkbox"]:checked');
            if (checkboxes.length > 5) {
                alert("Solo puedes seleccionar hasta 5 repuestos.");
                return false;
            }

            for (let checkbox of checkboxes) {
                let inputCantidad = document.getElementById("cantidad-" + checkbox.value);
                if (parseInt(inputCantidad.value) <= 0 || isNaN(parseInt(inputCantidad.value))) {
                    alert("La cantidad debe ser mayor a 0.");
                    return false;
                }
            }
            return true;
        }
    </script>

</body>
</html>

<?php $conexion->close(); ?>