<?php
session_start();

// Verificar si el usuario ha iniciado sesión y tiene rol de técnico
if (!isset($_SESSION['id_usuario']) || $_SESSION['rol'] !== 'tecnico') {
    header("Location: inicio_sesion.php");
    exit;
}

$conexion = new mysqli("localhost", "root", "", "servicio_tecnico");

if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

$id_usuario = $_SESSION['id_usuario'];
$nombre_tecnico = $_SESSION['nombre']; // Asegúrate de guardar el nombre del técnico en la sesión

// Obtener repuestos disponibles
$query_repuestos = "SELECT * FROM inventariorepuestos";
$resultado_repuestos = $conexion->query($query_repuestos);

// Procesar solicitud de repuestos
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['repuestos'])) {
    $repuestos_usados = $_POST['repuestos'];

    foreach ($repuestos_usados as $id_repuesto => $cantidad) {
        $id_repuesto = intval($id_repuesto);
        $cantidad = intval($cantidad);

        if ($cantidad > 0) {
            // Verificar cantidad disponible antes de descontar
            $query_check = "SELECT cantidad_disponible FROM inventariorepuestos WHERE id_repuesto = $id_repuesto";
            $resultado_check = $conexion->query($query_check);
            $fila = $resultado_check->fetch_assoc();

            if ($fila && $fila['cantidad_disponible'] >= $cantidad) {
                // Restar la cantidad utilizada
                $query_update = "UPDATE inventariorepuestos SET cantidad_disponible = cantidad_disponible - $cantidad WHERE id_repuesto = $id_repuesto";
                $conexion->query($query_update);
            }
        }
    }

    echo "<script>alert('Repuestos actualizados correctamente.'); window.location.href = 'inventario.php';</script>";
}

?>
