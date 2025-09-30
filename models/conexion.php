<?php

    $servidor = "localhost";
    $usuario = "root";
    $contraseña = "";
    $db = "servicio_tecnico";

    $conexion = new mysqli($servidor, $usuario, $contraseña, $db);
    $conexion->set_charset('utf8');

    if($conexion -> connect_error){
        die("Falla en la conexión". $conexion-> connect_error);
    }
?>
