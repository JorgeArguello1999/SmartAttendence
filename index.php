<?php
require_once 'modules/UploadAppApi.php';
use ImageUploader\APIUploader;

header("Content-Type: application/json");

// Verifica que la solicitud sea POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode([
        "error" => "Método no permitido",
        "estructura" => [
            "cedula" => "(string de 10 dígitos)",
            "gps" => "(latitud,longitud con al menos 6 decimales)",
            "ip" => "(dirección IP válida)",
            "password" => "(cadena de texto)",
            "device_info" => "(información del dispositivo)",
            "rostro" => "(archivo de imagen jpeg/png/jpg)"
        ]
    ]);
    exit;
}

// Validar y obtener los datos del formulario
$cedula = $_POST['cedula'] ?? '';
$gps = $_POST['gps'] ?? '';
$ip = $_POST['ip'] ?? '';
$password = $_POST['password'] ?? '';
$device_info = $_POST['device_info'] ?? '';

// Validar campos
if (strlen($cedula) !== 10 || !ctype_digit($cedula)) {
    echo json_encode(["error" => "Cédula inválida"]);
    exit;
}

if (!preg_match('/^-?\d{1,3}\.\d{6,},\s?-?\d{1,3}\.\d{6,}$/', $gps)) {
    echo json_encode(["error" => "GPS inválido"]);
    exit;
}

if (!filter_var($ip, FILTER_VALIDATE_IP)) {
    echo json_encode(["error" => "IP inválida"]);
    exit;
}

if (empty($password)) {
    echo json_encode(["error" => "Contraseña requerida"]);
    exit;
}

if (empty($device_info)) {
    echo json_encode(["error" => "Información del dispositivo requerida"]);
    exit;
}

// Manejo del archivo de imagen
if (!isset($_FILES['rostro'])) {
    echo json_encode(["error" => "Imagen del rostro requerida"]);
    exit;
}

$imagen = $_FILES['rostro'];
$permitidos = ['image/jpeg', 'image/png', 'image/jpg'];

if (!in_array($imagen['type'], $permitidos)) {
    echo json_encode(["error" => "Formato de imagen no permitido"]);
    exit;
}

$upload_dir = "../uploads/";
if (!is_dir($upload_dir)) {
    mkdir($upload_dir, 0755, true);
}

$nombre_archivo = uniqid() . "_" . basename($imagen['name']);
$ruta_destino = $upload_dir . $nombre_archivo;

if (!move_uploaded_file($imagen['tmp_name'], $ruta_destino)) {
    echo json_encode(["error" => "Error al guardar la imagen"]);
    exit;
}

// GET desde la base de datos del rostro codificado
require_once "database/query.php";
$empleado = new DatosBiometricos();
$detalles = $empleado->get_id_facial_details($cedula);

// Usar ImageUploader para enviar a la API de CheckID
try {
    // Preparar datos binarios si están disponibles (opcional)
    $binaryData = $detalles['caracteristicas_faciales'] ?? '';

    // Configurar URL de la API de CheckID (ajustar según tu configuración)
    $apiUrl = 'http://127.0.0.1:8000/binary_compare/';

    // Usar ImageUploader para subir imagen
    $apiRespuesta = APIUploader::uploadToAPI(
        $ruta_destino,  // Ruta de la imagen
        $apiUrl,        // URL de la API
        $binaryData,    // Datos binarios (opcional)
        [
            // Opciones adicionales de cURL si es necesario
            CURLOPT_TIMEOUT => 60,
            // Puedes agregar más configuraciones específicas
        ]
    );

    // Verificar respuesta de la API
    if (!$apiRespuesta['success']) {
        throw new Exception("Error en la comparación facial: " . $apiRespuesta['error']);
    }

    // Respuesta exitosa
    $response = [
        "mensaje" => "Datos recibidos correctamente",
        "cedula" => $cedula,
        "gps" => $gps,
        "ip" => $ip,
        "imagen_guardada" => $ruta_destino,
        "device_info" => $device_info,
        "api_response" => $apiRespuesta['response'],
    ];

    echo json_encode($response);

} catch (Exception $e) {
    // Manejo de errores
    http_response_code(500);
    echo json_encode([
        "error" => "Error en el procesamiento",
        "detalle" => $e->getMessage()
    ]);
}