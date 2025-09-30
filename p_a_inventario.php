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

// Obtener lista de proveedores existentes
$query_proveedores = "SELECT * FROM proveedores";
$resultado_proveedores = $conexion->query($query_proveedores);
$proveedores = [];
while ($fila = $resultado_proveedores->fetch_assoc()) {
    $proveedores[$fila['id_proveedor']] = $fila['nombre'];
}

// Procesar agregar o editar repuesto
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = trim($_POST['nombre_repuesto']);
    $cantidad = intval($_POST['cantidad_disponible']);
    $precio = floatval($_POST['precio']);
    $proveedor_input = trim($_POST['proveedor']);

    // Verificar si el proveedor ya existe en la base de datos
    $id_proveedor = null;
    foreach ($proveedores as $id => $nombre_existente) {
        if (strcasecmp($nombre_existente, $proveedor_input) == 0) {
            $id_proveedor = $id;
            break;
        }
    }

    // Si el proveedor no existe, agregarlo y obtener su ID
    if ($id_proveedor === null && !empty($proveedor_input)) {
        $query_insert_proveedor = "INSERT INTO proveedores (nombre) VALUES ('$proveedor_input')";
        if ($conexion->query($query_insert_proveedor) === TRUE) {
            $id_proveedor = $conexion->insert_id;
            $proveedores[$id_proveedor] = $proveedor_input;
        }
    }

    if (!empty($nombre) && $cantidad >= 0 && $precio >= 0 && $id_proveedor !== null) {
        if (isset($_POST['agregar'])) {
            // Insertar nuevo repuesto
            $query_insert = "INSERT INTO inventariorepuestos (nombre_repuesto, cantidad_disponible, precio, id_proveedor) 
                            VALUES ('$nombre', $cantidad, $precio, $id_proveedor)";
            if ($conexion->query($query_insert) === TRUE) {
                echo "<script>alert('Repuesto agregado correctamente.'); window.location.href = 'a_inventario.php';</script>";
            }
        } elseif (isset($_POST['editar']) && isset($_POST['id_repuesto'])) {
            // Editar repuesto existente
            $id_repuesto = intval($_POST['id_repuesto']);
            $query_update = "UPDATE inventariorepuestos SET 
                            nombre_repuesto = '$nombre', 
                            cantidad_disponible = $cantidad, 
                            precio = $precio, 
                            id_proveedor = $id_proveedor 
                            WHERE id_repuesto = $id_repuesto";
            if ($conexion->query($query_update) === TRUE) {
                echo "<script>alert('Repuesto actualizado correctamente.'); window.location.href = 'a_inventario.php';</script>";
            }
        }
    }
}

// Procesar eliminación de repuesto
if (isset($_GET['eliminar'])) {
    $id_repuesto = intval($_GET['eliminar']);
    if ($id_repuesto > 0) {
        $query_delete = "DELETE FROM inventariorepuestos WHERE id_repuesto = $id_repuesto";
        if ($conexion->query($query_delete) === TRUE) {
            echo "<script>alert('Repuesto eliminado correctamente.'); window.location.href = 'a_inventario.php';</script>";
        }
    }
}

// Cerrar conexión
$conexion->close();
?>
