<?php
require_once 'database/query.php';
$asistencia = new RegistroAsistencia();

// Valores de ejemplo para la asistencia
$id_empleado = 1;
$tipo_registro = 'entrada';  // O 'salida', dependiendo de la acción
$fecha_hora = '2025-03-24 08:00:00';  // Fecha y hora actual o de la asistencia
$image_verificacion = 'ruta/a/imagen.jpg';  // Ruta o contenido de la imagen
$confianza_reconocimiento = 0.95;  // Confianza del reconocimiento facial
$latitud = 19.432608;  // Ejemplo de latitud
$longitud = -99.133209;  // Ejemplo de longitud
$id_sede = null;  // ID de la sede en la que se registró la asistencia
$perimetro = 50;  // Perímetro del área de verificación
$ip_dispositivo = '192.168.1.1';  // IP del dispositivo que hace el registro
$ip_dispositivo_info = 'Dispositivo móvil';  // Información sobre el dispositivo
$estatus = 'Valido';  // Estatus de la asistencia, puede ser 'activo', 'inactivo', etc.
$observaciones = 'Llegó a tiempo';  // Observaciones adicionales

// Llamar al método save_asistencia para guardar los datos
$resultado = $asistencia->save_asistencia(
    $id_empleado,
    $tipo_registro,
    $fecha_hora,
    $image_verificacion,
    $confianza_reconocimiento,
    $latitud,
    $longitud,
    $id_sede,
    $perimetro,
    $ip_dispositivo,
    $ip_dispositivo_info,
    $estatus,
    $observaciones
);

// Verificar el resultado
if ($resultado) {
    echo "La asistencia se guardó correctamente.";
} else {
    echo "Hubo un error al guardar la asistencia.";
}

?>