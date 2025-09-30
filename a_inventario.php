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

// Obtener lista de repuestos e inventario
$query = "SELECT ir.*, p.nombre
          FROM inventariorepuestos ir 
          LEFT JOIN proveedores p ON ir.id_proveedor = p.id_proveedor";
$resultado = $conexion->query($query);

// Si se solicita edición, obtener los datos del repuesto
$id_editar = isset($_GET['editar']) ? intval($_GET['editar']) : null;
$repuesto_editar = null;

if ($id_editar) {
    $query_edit = "SELECT * FROM inventariorepuestos WHERE id_repuesto = $id_editar";
    $resultado_edit = $conexion->query($query_edit);
    if ($resultado_edit->num_rows > 0) {
        $repuesto_editar = $resultado_edit->fetch_assoc();
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Administrar Inventario</title>
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
            <th colspan="6">Inventario de Repuestos</th>
        </tr>
        <tr>
            <th>ID</th>
            <th>Nombre Repuesto</th>
            <th>Cantidad Disponible</th>
            <th>Precio</th>
            <th>Proveedor</th>
            <th>Acciones</th>
        </tr>

        <?php while ($fila = $resultado->fetch_assoc()) { ?>
        <tr>
            <td><?php echo $fila['id_repuesto']; ?></td>
            <td><?php echo $fila['nombre_repuesto']; ?></td>
            <td><?php echo $fila['cantidad_disponible']; ?></td>
            <td>$<?php echo number_format($fila['precio'], 2); ?></td>
            <td><?php echo $fila['nombre']; ?></td>
            <td>
                <a href="a_inventario.php?editar=<?php echo $fila['id_repuesto']; ?>" class="editar">Editar</a> | 
                <a href="p_a_inventario.php?eliminar=<?php echo $fila['id_repuesto']; ?>" onclick="return confirm('¿Seguro que deseas eliminar este repuesto?');" class="editar">Eliminar</a>
            </td>
        </tr>
        <?php } ?>
    </table>

    <form action="p_a_inventario.php" method="post">
        <h2><?php echo isset($_GET['ed