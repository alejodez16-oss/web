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

// Procesar actualización de rol
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['editar'])) {
    $id_usuario = intval($_POST['id_usuario']);
    $nuevo_rol = $_POST['rol'];

    $query_update = "UPDATE usuarios SET rol = '$nuevo_rol' WHERE id_usuario = $id_usuario";
    
    if ($conexion->query($query_update) === TRUE) {
        echo "<script>alert('Rol actualizado correctamente.'); window.location.href = 'admin_usuarios.php';</script>";
    }
}

// Procesar eliminación de usuario
if (isset($_GET['eliminar'])) {
    $id_usuario = intval($_GET['eliminar']);

    if ($id_usuario > 0) {
        $query_delete = "DELETE FROM usuarios WHERE id_usuario = $id_usuario";

        if ($conexion->query($query_delete) === TRUE) {
            echo "<script>alert('Usuario eliminado correctamente.'); window.location.href = 'admin_usuarios.php';</script>";
        }
    }
}

// Cerrar conexión
$conexion->close();
?>
