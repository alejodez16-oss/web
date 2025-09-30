<?php
session_start();
if (!isset($_SESSION['id_usuario'])) {
    header("Location: inicio_sesion.php");
    exit();
}

$id_usuario = $_SESSION['id_usuario'];

// Obtener información del usuario para prellenar
$conexion = new mysqli("localhost", "root", "", "servicio_tecnico");

// Verificar conexión
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

$query_usuario = "SELECT nombre, apellido, correo, telefono, direccion FROM usuarios WHERE id_usuario = $id_usuario";
$resultado_usuario = $conexion->query($query_usuario);

// Verificar si la consulta fue exitosa
if ($resultado_usuario === false) {
    die("Error en la consulta: " . $conexion->error);
}

// Verificar si se encontró el usuario
if ($resultado_usuario->num_rows > 0) {
    $usuario = $resultado_usuario->fetch_assoc();
} else {
    die("Usuario no encontrado en la base de datos.");
}

$conexion->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Orden - TecLine</title>
    <link rel="stylesheet" href="assites/css/style.css">
    <link rel="stylesheet" href="assites/css/crear_orden.css">
    <link rel="stylesheet" href="assites/css/calendario.css">
</head>
<body>

    <header class="header">
        <a href="inicio.php" class="logo">
            <img src="https://img.icons8.com/?size=100&id=gvQUkpW15e1x&format=png&color=000000" alt="">
            <h2 class="nombre_">TecLine</h2>
        </a>
        <nav>
            <a href="Pedido_N.php" class="btn_conocenos">Realizar Pedido</a>
            <a href="mispededidos.php" class="btn_conocenos">Mis Pedidos</a>
            <a href="perfil.php" class="btn_conocenos">Actualizar Información</a>
            <a href="cerrar_sesion.php" class="btn_conocenos">Cerrar Sesión</a>
        </nav>
    </header> 
    
    <div class="container">
        <div class="form-container">
            <h1>Crear Orden de Servicio</h1>
            
            <!-- Información del usuario -->
            <div class="user-info">
                <h3>Datos de Contacto</h3>
                <p><strong>Nombre:</strong> <?php echo htmlspecialchars($usuario['nombre'] . ' ' . $usuario['apellido']); ?></p>
                <p><strong>Teléfono:</strong> <?php echo htmlspecialchars($usuario['telefono']); ?></p>
                <p><strong>Dirección:</strong> <?php echo htmlspecialchars($usuario['direccion'] ? $usuario['direccion'] : 'No registrada'); ?></p>
            </div>

            <form action="procesar_orden.php" method="POST" enctype="multipart/form-data" onsubmit="return validarFormulario()">
                <input type="hidden" name="id_usuario" value="<?php echo $id_usuario; ?>">
                <input type="hidden" name="fecha_creacion" value="<?php echo date('Y-m-d H:i:s'); ?>">
                
                <!-- Tipo de dispositivo -->
                <div class="form-group">
                    <label for="tipo_dispositivo">Tipo de dispositivo:</label>
                    <select name="tipo_dispositivo" id="tipo_dispositivo" onchange="mostrarOpcionesTipoDispositivo()" required>
                        <option value="">Seleccione...</option>
                        <option value="Consola">Consola</option>
                        <option value="Computadora">Computadora</option>
                    </select>
                </div>

                <!-- Especificación de consola -->
                <div class="form-group hidden" id="especificacion_consola_group">
                    <label for="especificacion_consola">Especificación de consola:</label>
                    <select name="especificacion_consola" id="especificacion_consola">
                        <option value="">Seleccione...</option>
                        <option value="Xbox Series X">Xbox Series X</option>
                        <option value="Xbox Series S">Xbox Series S</option>
                        <option value="Xbox One">Xbox One</option>
                        <option value="Xbox 360">Xbox 360</option>
                        <option value="Xbox Clásico">Xbox Clásico</option>
                        <option value="Play 2">Play 2</option>
                        <option value="Play 3">Play 3</option>
                        <option value="Play 4">Play 4</option>
                        <option value="Play 5">Play 5</option>
                        <option value="Nintendo Wii">Nintendo Wii</option>
                        <option value="Nintendo Wii U">Nintendo Wii U</option>
                        <option value="Nintendo 3DS">Nintendo 3DS</option>
                        <option value="Nintendo Switch">Nintendo Switch</option>
                    </select>
                </div>

                <!-- Descripción del problema -->
                <div class="form-group">
                    <label for="descripcion_problema">Descripción del problema:</label>
                    <select name="descripcion_problema" id="descripcion_problema" onchange="mostrarCamposAdicionales()" required>
                        <option value="">Seleccione...</option>
                        <option value="Mantenimiento">Mantenimiento</option>
                        <option value="Pantalla rota">Pantalla rota</option>
                        <option value="No enciende">No enciende</option>
                        <option value="Problema de sonido">Problema de sonido</option>
                        <option value="Sobrecalentamiento">Sobrecalentamiento</option>
                        <option value="Luz roja">Luz roja</option>
                        <option value="otro">Otro (especificar abajo)</option>
                    </select>
                </div>

                <!-- Campo para "Otro problema" -->
                <div class="form-group hidden" id="otro_problema_container">
                    <label for="otro_problema">Describa el problema en detalle:</label>
                    <textarea name="otro_problema" id="otro_problema" maxlength="255" placeholder="Por favor, describe detalladamente el problema que presenta tu dispositivo"></textarea>
                </div>

                <!-- Subir foto del problema -->
                <div class="form-group hidden" id="foto_problema_container">
                    <label for="foto_problema">Foto del problema (opcional):</label>
                    <input type="file" name="foto_problema" id="foto_problema" accept="image/*">
                    <small>Puedes subir una foto que muestre el problema que presenta tu dispositivo</small>
                </div>

                <!-- Modalidad de entrega -->
                <div class="form-group">
                    <label for="modalidad_entrega">¿Cómo prefieres que atendamos tu dispositivo?</label>
                    <select name="modalidad_entrega" id="modalidad_entrega" onchange="mostrarInfoModalidad()" required>
                        <option value="">Seleccione...</option>
                        <option value="domicilio">Recogida a domicilio (+$5.000)</option>
                        <option value="local">Llevarlo al local</option>
                    </select>
                </div>

                <!-- Información adicional según modalidad -->
                <div class="info-box hidden" id="info_local">
                    <strong>📋 Importante:</strong> Si eliges llevar tu dispositivo al local, por favor llega 10 minutos antes de tu cita programada para completar el proceso de admisión.
                </div>

                <!-- Calendario para agendar cita -->
                <h3>Selecciona una fecha y hora para tu cita</h3>
                <p>Horario de atención: Lunes a Viernes de 9:00 AM a 5:00 PM</p>
                
                <div id="calendar-container">
                    <div class="calendar-header">
                        <button type="button" onclick="changeMonth(-1)">←</button>
                        <h4 id="current-month-year"></h4>
                        <button type="button" onclick="changeMonth(1)">→</button>
                    </div>
                    <div id="calendar" class="calendar"></div>
                    <div id="time-slots" class="time-slots hidden"></div>
                    <input type="hidden" name="fecha_cita" id="fecha_cita" required>
                </div>

                <button type="submit">Crear Orden de Servicio</button>
            </form>
        </div>        
    </div>    

    <script>
        // El mismo JavaScript que antes, pero sin los estilos CSS
        let currentDate = new Date();
        let selectedDate = null;
        let selectedTime = null;

        function mostrarOpcionesTipoDispositivo() {
            const tipoDispositivo = document.getElementById("tipo_dispositivo").value;
            const especificacionGroup = document.getElementById("especificacion_consola_group");

            if (tipoDispositivo === "Consola") {
                especificacionGroup.classList.remove("hidden");
            } else {
                especificacionGroup.classList.add("hidden");
                document.getElementById("especificacion_consola").value = "";
            }
        }

        function mostrarCamposAdicionales() {
            const descripcionProblema = document.getElementById("descripcion_problema").value;
            const otroProblemaContainer = document.getElementById("otro_problema_container");
            const fotoProblemaContainer = document.getElementById("foto_problema_container");

            if (descripcionProblema === "otro") {
                otroProblemaContainer.classList.remove("hidden");
            } else {
                otroProblemaContainer.classList.add("hidden");
                document.getElementById("otro_problema").value = "";
            }

            if (descripcionProblema !== "Mantenimiento" && descripcionProblema !== "" && descripcionProblema !== "otro") {
                fotoProblemaContainer.classList.remove("hidden");
            } else {
                fotoProblemaContainer.classList.add("hidden");
            }
        }

        function mostrarInfoModalidad() {
            const modalidad = document.getElementById("modalidad_entrega").value;
            const infoLocal = document.getElementById("info_local");

            if (modalidad === "local") {
                infoLocal.classList.remove("hidden");
            } else {
                infoLocal.classList.add("hidden");
            }
        }

        function renderCalendar() {
            const calendar = document.getElementById('calendar');
            const currentMonthYear = document.getElementById('current-month-year');
            
            calendar.innerHTML = '';
            
            const year = currentDate.getFullYear();
            const month = currentDate.getMonth();
            
            currentMonthYear.textContent = `${currentDate.toLocaleString('es-ES', { month: 'long' })} ${year}`;
            
            const days = ['Dom', 'Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb'];
            days.forEach(day => {
                const dayElement = document.createElement('div');
                dayElement.textContent = day;
                dayElement.style.fontWeight = 'bold';
                calendar.appendChild(dayElement);
            });
            
            const firstDay = new Date(year, month, 1).getDay();
            const daysInMonth = new Date(year, month + 1, 0).getDate();
            
            for (let i = 0; i < firstDay; i++) {
                const emptyDay = document.createElement('div');
                emptyDay.classList.add('calendar-day', 'disabled');
                calendar.appendChild(emptyDay);
            }
            
            for (let i = 1; i <= daysInMonth; i++) {
                const dayElement = document.createElement('div');
                dayElement.classList.add('calendar-day');
                dayElement.textContent = i;
                
                const dayDate = new Date(year, month, i);
                const today = new Date();
                today.setHours(0, 0, 0, 0);
                
                if (dayDate.getDay() === 0 || dayDate.getDay() === 6 || dayDate < today) {
                    dayElement.classList.add('disabled');
                } else {
                    dayElement.addEventListener('click', () => selectDate(dayDate, i));
                }
                
                if (selectedDate && dayDate.getTime() === selectedDate.getTime()) {
                    dayElement.classList.add('selected');
                }
                
                calendar.appendChild(dayElement);
            }
        }
        
        function changeMonth(direction) {
            currentDate.setMonth(currentDate.getMonth() + direction);
            renderCalendar();
        }
        
        function selectDate(date, day) {
            selectedDate = new Date(date);
            renderCalendar();
            
            const timeSlots = document.getElementById('time-slots');
            timeSlots.classList.remove('hidden');
            timeSlots.innerHTML = '';
            
            for (let hour = 9; hour <= 16; hour++) {
                for (let minute = 0; minute < 60; minute += 60) {
                    const timeSlot = document.createElement('div');
                    timeSlot.classList.add('time-slot');
                    
                    const timeFormatted = `${hour.toString().padStart(2, '0')}:${minute.toString().padStart(2, '0')}`;
                    timeSlot.textContent = timeFormatted;
                    timeSlot.dataset.time = timeFormatted;
                    
                    timeSlot.addEventListener('click', () => selectTime(timeFormatted));
                    timeSlots.appendChild(timeSlot);
                }
            }
        }
        
        function selectTime(time) {
            selectedTime = time;
            
            document.querySelectorAll('.time-slot').forEach(slot => {
                slot.classList.remove('selected');
                if (slot.dataset.time === time) {
                    slot.classList.add('selected');
                }
            });
            
            const fechaCita = new Date(selectedDate);
            const [hours, minutes] = time.split(':');
            fechaCita.setHours(parseInt(hours), parseInt(minutes));
            
            document.getElementById('fecha_cita').value = fechaCita.toISOString().slice(0, 16);
        }
        
        function validarFormulario() {
            const descripcionProblema = document.getElementById("descripcion_problema").value;
            const otroProblema = document.getElementById("otro_problema").value;
            
            if (descripcionProblema === "otro" && otroProblema.trim() === "") {
                alert("Por favor, describe el problema en el campo 'Describa el problema'.");
                return false;
            }
            
            const fechaCita = document.getElementById("fecha_cita").value;
            if (!fechaCita) {
                alert("Por favor, selecciona una fecha y hora para tu cita.");
                return false;
            }
            
            return true;
        }

        document.addEventListener('DOMContentLoaded', function() {
            renderCalendar();
            mostrarCamposAdicionales();
        });
    </script>
</body>
</html>