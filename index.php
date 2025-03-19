<?php

require_once 'database/query.php';

$empleados = new Empleados();
echo "<h1>Trabajadores</h1>";
echo "<table border='1' style='width:100%; border-collapse: collapse; text-align: center;'>";
echo "<tr><th>ID</th><th>Nombre</th><th>Imagen</th></tr>";
foreach ($empleados->get_all() as $row) {
    echo "<tr>";
    
    foreach ($row as $key => $item) {
        if ($key == 'imagen_rostro') {
            echo "<td><img src='data:image/jpeg;base64," . base64_encode($item) . "' style='width: 50px; height: 50px;'/></td>";
        } else {
            echo "<td>" . htmlspecialchars($item) . "</td>";
        }
    }
    
    echo "</tr>";
}
echo "</table>";

$asistencia = new RegistroAsistencia();
echo "<h1>Lista de Asistencia</h1>";
echo "<table border='1' style='width:100%; border-collapse: collapse; text-align: center;'>";
echo "<tr><th>ID</th><th>Nombre</th><th>Apellidos</th><th>email</th><th>tel</th><th>fecha_hora</th></tr>";
foreach ($asistencia->get_all() as $row) {
    echo "<tr>";
    
    foreach ($row as $key => $item) {
        if ($key == 'imagen_verificacion') {
            echo "<td><img src='data:image/jpeg;base64," . base64_encode($item) . "' style='width: 50px; height: 50px;'/></td>";
        } else {
            echo "<td>" . htmlspecialchars($item) . "</td>";
        }
    }
    echo "</tr>";
}
echo "</table>";

require_once 'database/query.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Crear una instancia de la clase RegistroAsistencia
    $registroAsistencia = new RegistroAsistencia();

    // Obtener los datos del formulario POST
    $id_empleado = $_POST['id_empleado'] ?? null;
    $tipo_registro = $_POST['tipo_registro'] ?? null;
    $fecha_hora = $_POST['fecha_hora'] ?? date('Y-m-d H:i:s'); // Si no se envía, usa la fecha actual
    $confianza_reconocimiento = isset($_POST['confianza_reconocimiento']) ? floatval($_POST['confianza_reconocimiento']) : null;
    $latitud = isset($_POST['latitud']) ? floatval($_POST['latitud']) : null;
    $longitud = isset($_POST['longitud']) ? floatval($_POST['longitud']) : null;
    $id_sede = $_POST['id_sede'] ?? null;
    $dentro_perimetro = $_POST['dentro_perimetro'] ?? null;
    $ip_dispositivo = $_POST['ip_dispositivo'] ?? null;
    $dispositivo_info = $_POST['dispositivo_info'] ?? null;
    $estatus = $_POST['estatus'] ?? null;
    $observaciones = $_POST['observaciones'] ?? null;

    // Manejo de la imagen (si se envía)
    if (isset($_FILES['imagen_verificacion']) && $_FILES['imagen_verificacion']['error'] == 0) {
        $imagen_verificacion = file_get_contents($_FILES['imagen_verificacion']['tmp_name']);
    } else {
        $imagen_verificacion = null; // No se envió imagen
    }

    // Insertar el registro de asistencia
    $exito = $registroAsistencia->insert_record(
        $id_empleado,
        $tipo_registro,
        $fecha_hora,
        $imagen_verificacion,
        $confianza_reconocimiento,
        $latitud,
        $longitud,
        $id_sede,
        $dentro_perimetro,
        $ip_dispositivo,
        $dispositivo_info,
        $estatus,
        $observaciones
    );

    // Responder con JSON
    header('Content-Type: application/json');
    echo json_encode([
        'success' => $exito,
        'message' => $exito ? "Registro de asistencia guardado correctamente." : "Error al guardar el registro de asistencia."
    ]);
} else {
    // Si no es una solicitud POST, mostrar error
    http_response_code(405);
    echo json_encode([
        'success' => false,
        'message' => "Método no permitido. Usa POST."
    ]);
}

?>