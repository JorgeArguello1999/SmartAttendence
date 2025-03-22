<?php
require_once("../database/query.php");
$asistencia = new RegistroAsistencia();

/*
// Verifica si el formulario se ha enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_empleado = $_POST['id_empleado'];
    $tipo_registro = $_POST['tipo_registro'];
    $fecha_hora = $_POST['fecha_hora'];
    $image_verificacion = $_POST['image_verificacion']; // Debería ser un archivo, puede necesitar otro tipo de manejo
    $confianza_reconocimiento = $_POST['confianza_reconocimiento'];
    $latitud = $_POST['latitud'];
    $longitud = $_POST['longitud'];
    $id_sede = $_POST['id_sede'];
    $perimetro = $_POST['perimetro'];
    $ip_dispositivo = $_POST['ip_dispositivo'];
    $ip_dispositivo_info = $_POST['ip_dispositivo_info'];
    $estatus = $_POST['estatus'];
    $observaciones = $_POST['observaciones'];

    // Llama al método save_asistencia para guardar los datos
    $resultado = $asistencia->save_asistencia($id_empleado, $tipo_registro, $fecha_hora, $image_verificacion, 
        $confianza_reconocimiento, $latitud, $longitud, $id_sede, $perimetro, 
        $ip_dispositivo, $ip_dispositivo_info, $estatus, $observaciones);

    if ($resultado) {
        echo "Asistencia registrada correctamente.";
    } else {
        echo "Hubo un error al registrar la asistencia.";
    }
}
*/
// Ejemplo de valores para los parámetros de la función
$id_empleado = 1; // ID del empleado
$tipo_registro = 'Entrada'; // Tipo de registro (por ejemplo, 'Entrada' o 'Salida')
$fecha_hora = '2025-03-21 09:00:00'; // Fecha y hora del registro
$image_verificacion = 'photo1.png'; // Ruta de la imagen de verificación (asegúrate de que sea válida)
$confianza_reconocimiento = (float)95.5; // Nivel de confianza en el reconocimiento facial (valor decimal)
$latitud = 19.432608; // Latitud de la ubicación
$longitud = -99.133209; // Longitud de la ubicación
$id_sede = 1; // ID de la sede donde se registró
$perimetro = 1; // Indica si está dentro del perímetro (puede ser un booleano o valor numérico)
$ip_dispositivo = '192.168.1.100'; // Dirección IP del dispositivo
$ip_dispositivo_info = 'Dispositivo móvil'; // Información sobre el dispositivo
$estatus = 'Sospechoso'; // Estatus del registro (por ejemplo, 'Presente', 'Ausente', etc.)
$observaciones = 'Sin observaciones'; // Observaciones adicionales

// Llamada a la función 'save_asistencia' con los valores de ejemplo
$result = $asistencia->save_asistencia(
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

// Verifica el resultado
if ($result) {
    echo "Registro de asistencia guardado exitosamente.";
} else {
    echo "Error al guardar el registro de asistencia: " . $registroAsistencia->conn->error;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Asistencia</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-blue-50 text-blue-900 flex flex-col items-center p-6">
    <h1 class="text-3xl font-bold text-blue-800 mb-6">Lista de Asistencia</h1>
    <h2>Registrar Asistencia</h2>
    <form action="asistencia.php" method="POST">
        <label for="id_empleado">ID Empleado:</label>
        <input type="text" id="id_empleado" name="id_empleado" required><br><br>

        <label for="tipo_registro">Tipo de Registro:</label>
        <input type="text" id="tipo_registro" name="tipo_registro" required><br><br>

        <label for="fecha_hora">Fecha y Hora:</label>
        <input type="datetime-local" id="fecha_hora" name="fecha_hora" required><br><br>

        <label for="image_verificacion">Imagen de Verificación:</label>
        <input type="file" id="image_verificacion" name="image_verificacion" required><br><br>

        <label for="confianza_reconocimiento">Confianza de Reconocimiento:</label>
        <input type="number" id="confianza_reconocimiento" name="confianza_reconocimiento" step="0.01" required><br><br>

        <label for="latitud">Latitud:</label>
        <input type="text" id="latitud" name="latitud" required><br><br>

        <label for="longitud">Longitud:</label>
        <input type="text" id="longitud" name="longitud" required><br><br>

        <label for="id_sede">ID Sede:</label>
        <input type="text" id="id_sede" name="id_sede" required><br><br>

        <label for="perimetro">¿Dentro del Perímetro?</label>
        <input type="checkbox" id="perimetro" name="perimetro"><br><br>

        <label for="ip_dispositivo">IP del Dispositivo:</label>
        <input type="text" id="ip_dispositivo" name="ip_dispositivo" required><br><br>

        <label for="ip_dispositivo_info">Información del Dispositivo:</label>
        <input type="text" id="ip_dispositivo_info" name="ip_dispositivo_info" required><br><br>

        <label for="estatus">Estatus:</label>
        <input type="text" id="estatus" name="estatus" required><br><br>

        <label for="observaciones">Observaciones:</label>
        <textarea id="observaciones" name="observaciones"></textarea><br><br>

        <button type="submit">Registrar Asistencia</button>
    </form>
    <div class="overflow-x-auto w-full max-w-6xl">
        <table class="w-full bg-white shadow-lg rounded-lg overflow-hidden">
            <thead class="bg-blue-700 text-white">
                <tr>
                    <th class="p-4">ID</th>
                    <th class="p-4">Nombre</th>
                    <th class="p-4">Apellidos</th>
                    <th class="p-4">Email</th>
                    <th class="p-4">Teléfono</th>
                    <th class="p-4">Fecha y Hora</th>
                    <th class="p-4">Imagen</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($asistencia->get_all() as $row): ?>
                    <tr class="border-b border-gray-300">
                        <?php foreach ($row as $key => $item): ?>
                            <?php if ($key == 'imagen_verificacion'): ?>
                                <td class="p-4 flex justify-center">
                                    <img src='data:image/jpeg;base64,<?= base64_encode($item) ?>' class='w-12 h-12 rounded-full object-cover'/>
                                </td>
                            <?php else: ?>
                                <td class="p-4 text-center"> <?= htmlspecialchars($item) ?> </td>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>