<?php
session_start();

if (!isset($_SESSION['id_usuario'])) {
    echo '
        <script>
            alert("Debes iniciar sesión para realizar un pedido.");
            window.location = "inicio_sesion.php";
        </script>
    ';
    exit;
}

// Conectar a la base de datos
$conexion = new mysqli("localhost", "root", "", "servicio_tecnico");

// Verificar conexión
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

// Obtener datos del formulario
$id_usuario = $_SESSION['id_usuario'];
$tipo_dispositivo = $conexion->real_escape_string($_POST['tipo_dispositivo']);
$especificacion_consola = isset($_POST['especificacion_consola']) ? $conexion->real_escape_string($_POST['especificacion_consola']) : '';
$descripcion_problema_select = $conexion->real_escape_string($_POST['descripcion_problema']);

// Determinar la descripción final del problema
if ($descripcion_problema_select === "otro" && isset($_POST['otro_problema']) && !empty($_POST['otro_problema'])) {
    // Usar la descripción personalizada si seleccionó "otro" y escribió algo
    $descripcion_problema = $conexion->real_escape_string($_POST['otro_problema']);
} else {
    // Usar la opción seleccionada en el dropdown
    $descripcion_problema = $descripcion_problema_select;
}

// Buscar un técnico disponible de forma aleatoria y obtener su nombre completo
$query_tecnico = "SELECT id_usuario, CONCAT(nombre, ' ', apellido) as nombre_completo 
                  FROM usuarios WHERE rol = 'tecnico' ORDER BY RAND() LIMIT 1";
$result_tecnico = $conexion->query($query_tecnico);

if ($result_tecnico->num_rows > 0) {
    $row = $result_tecnico->fetch_assoc();
    $id_tecnico = $row['id_usuario'];
    $nombre_tecnico = $row['nombre_completo'];
} else {
    $id_tecnico = NULL;
    $nombre_tecnico = "Sin asignar";
}

// Obtener el nombre del usuario que crea la orden
$query_usuario = "SELECT CONCAT(nombre, ' ', apellido) as nombre_completo FROM usuarios WHERE id_usuario = $id_usuario";
$result_usuario = $conexion->query($query_usuario);
$row_usuario = $result_usuario->fetch_assoc();
$nombre_usuario = $row_usuario['nombre_completo'];

// Verificar qué columnas existen en la tabla
$columnas_existentes = array();
$result_columnas = $conexion->query("SHOW COLUMNS FROM OrdenesServicio");
while ($columna = $result_columnas->fetch_assoc()) {
    $columnas_existentes[] = $columna['Field'];
}

// Construir la consulta INSERT basada en las columnas existentes
$campos = array();
$valores = array();

// Campos básicos que deberían existir
$campos[] = "id_usuario";
$valores[] = $id_usuario;

$campos[] = "tipo_dispositivo";
$valores[] = "'$tipo_dispositivo'";

$campos[] = "descripcion_problema";
$valores[] = "'$descripcion_problema'";

if (in_array('especificacion_consola', $columnas_existentes)) {
    $campos[] = "especificacion_consola";
    $valores[] = "'$especificacion_consola'";
}

$campos[] = "estado";
$valores[] = "'pendiente'";

if (in_array('id_tecnico', $columnas_existentes)) {
    $campos[] = "id_tecnico";
    $valores[] = $id_tecnico;
}

// Campos adicionales (si existen)
if (in_array('nombre_tecnico', $columnas_existentes)) {
    $campos[] = "nombre_tecnico";
    $valores[] = "'$nombre_tecnico'";
}

if (in_array('nombre_usuario', $columnas_existentes)) {
    $campos[] = "nombre_usuario";
    $valores[] = "'$nombre_usuario'";
}

// Construir la consulta final
$query_insert = "INSERT INTO OrdenesServicio (" . implode(', ', $campos) . ") 
                 VALUES (" . implode(', ', $valores) . ")";

// Ejecutar la consulta
if ($conexion->query($query_insert) === TRUE) {
    echo '
        <script>
            alert("Pedido realizado con éxito. Su técnico asignado es: ' . $nombre_tecnico . '");
            window.location = "mispededidos.php";
        </script>
    ';
} else {
    echo "Error al registrar el pedido: " . $conexion->error . "<br>";
    echo "Consulta: " . $query_insert;
}

// Cerrar conexión
$conexion->close();
?>