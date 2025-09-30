    <?php
    require 'conexion.php'; // Archivo donde tienes la conexión a MySQL

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $nombre = $_POST["nombre"];
        $apellido = $_POST["apellido"];
        $correo = $_POST["correo"];
        $contrasena =$_POST['contrasena'];
        $telefono = $_POST["telefono"];
        $rol = "cliente";

        $contrasena = hash('sha512', $contrasena);


        // Verificar si el correo ya existe
        $stmt = $conexion->prepare("SELECT id_usuario FROM Usuarios WHERE correo = ?");
        $stmt->bind_param("s", $correo);
        $stmt->execute();
        $stmt->store_result();
        
        if ($stmt->num_rows > 0) {
            echo '<script>
                        alert("El Correo Ya Ha Sido Registrado");
                        window.location = "../inicio_sesion.php";
                    </script>
                    ';
        } else {
            // Insertar el usuario en la base de datos
            $stmt = $conexion->prepare("INSERT INTO Usuarios (nombre, apellido, correo, contrasena, telefono, rol) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssssss", $nombre, $apellido, $correo, $contrasena, $telefono, $rol);
            
            if ($stmt->execute()) {
                echo '
                    <script>
                        alert("Usuario Almacenado Correctamente");
                        window.location = "../inicio_sesion.php";
                    </script>
                    ';
            } else {
                echo "Error al registrar usuario.";
            }
        }
        
        $stmt->close();
        $conexion->close();
    }
    ?>
