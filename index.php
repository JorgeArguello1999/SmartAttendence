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

// Ejemplo de uso:
// Crear una instancia de la clase RegistroAsistencia
$registroAsistencia = new RegistroAsistencia();

// Cargar la imagen según el tipo de dato en la BD
$imagen_verificacion = file_get_contents('photo_test.png'); // Si la columna es BLOB

// Datos de asistencia
$id_empleado = 1;
$tipo_registro = 'Entrada';
$fecha_hora = date('Y-m-d H:i:s'); // Fecha y hora actual
$confianza_reconocimiento = 0.95;
$latitud = 40.7127837;
$longitud = -74.0059413;
$id_sede = 1;
$dentro_perimetro = 1;
$ip_dispositivo = '192.168.1.10';
$dispositivo_info = 'Bulcan SE';
$estatus = 'Válido';
$observaciones = 'Ninguna';

// Insertar el registro de asistencia
$exito = $registroAsistencia->insert_record(
    $id_empleado,
    $tipo_registro,
    $fecha_hora,
    $imagen_verificacion, // Pasar binario si es BLOB
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

// Verificar si la inserción fue exitosa
if ($exito) {
    echo "Registro de asistencia guardado correctamente.";
} else {
    echo "Error al guardar el registro de asistencia.";
}


?>