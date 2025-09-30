<?php
session_start();

// Verificar si el usuario ha iniciado sesión y tiene rol de administrador
if (!isset($_SESSION['id_usuario']) || $_SESSION['rol'] !== 'administrador') {
    header("Location: inicio_sesion.php");
    exit;
}

$conexion = new mysqli("localhost", "root", "", "servicio_tecnico");

if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

// Procesar actualización de proveedor
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['editar'])) {
    $id_proveedor = intval($_POST['id_proveedor']);
    $nombre_proveedor = $_POST['nombre_proveedor'];
    $correo = $_POST['correo'];
    $telefono = $_POST['telefono'];

    $query_update = "UPDATE proveedores SET nombre_proveedor = '$nombre_proveedor', correo = '$correo', telefono = '$telefono' WHERE id_proveedor = $id_proveedor";
    
    if ($conexion->query($query_update) === TRUE) {
        echo "<script>alert('Proveedor actualizado correctamente.'); window.location.href = 'admin_proveedores.php';</script>";
    }
}

// Procesar eliminación de proveedor
if (isset($_GET['eliminar'])) {
    $id_proveedor = intval($_GET['eliminar']);

    if ($id_proveedor > 0) {
        $query_delete = "DELETE FROM proveedores WHERE id_proveedor = $id_proveedor";

        if ($conexion->query($query_delete) === TRUE) {
            echo "<script>alert('Proveedor eliminado correctamente.'); window.location.href = 'admin_proveedores.php';</script>";
        }
    }
}

if(isset($_POST['agregar'])) {
    $nombre = $_POST['nombre_proveedor'];
    $correo = $_POST['correo'];
    $telefono = $_POST['telefono'];
    
    $query = "INSERT INTO proveedores (nombre, correo, telefono) VALUES (?, ?, ?)";
    $stmt = $conexion->prepare($query);
    $stmt->bind_param("sss", $nombre, $correo, $telefono);
    
    if($stmt->execute()) {
        echo "<script>alert('Proveedor agregado correctamente');</script>";
        echo "<script>window.location.href = 'admin_proveedores.php';</script>";
    } else {
        echo "<script>alert('Error al agregar proveedor');</script>";
    }
    }

// Cerrar conexión
$conexion->close();
?>
