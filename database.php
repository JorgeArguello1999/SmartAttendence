<?php
// Ensure proper error reporting and session handling
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

// Database connection and required classes
require_once 'database/query.php';

// Initialize classes
$biometrico = new DatosBiometricos();
$asistencia = new RegistroAsistencia(); // Assuming this class exists for saving attendance

// Input validation and security
function sanitizeInput($input) {
    return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
}

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    die(json_encode([
        'error' => 'Method Not Allowed',
        'message' => 'Only POST requests are accepted'
    ]));
}

// Retrieve and sanitize input parameters
$cedula = sanitizeInput($_POST['cedula'] ?? '');
$tipo_registro = sanitizeInput($_POST['tipo_registro'] ?? ''); // 'Entrada' or 'Salida'
$latitud = filter_input(INPUT_POST, 'latitud', FILTER_VALIDATE_FLOAT);
$longitud = filter_input(INPUT_POST, 'longitud', FILTER_VALIDATE_FLOAT);
$perimetro = sanitizeInput($_POST['perimetro'] ?? '');

// Validate required parameters
if (empty($cedula) || empty($tipo_registro)) {
    http_response_code(400);
    die(json_encode([
        'error' => 'Bad Request',
        'message' => 'Cedula and tipo_registro are required'
    ]));
}

// Retrieve facial details
try {
    $facial_detalles = $biometrico->get_id_facial_details($cedula);
    
    if (empty($facial_detalles)) {
        throw new Exception('No facial details found for the given cedula');
    }

    $id_empleado = $facial_detalles['id_empleado'];
    $detalles = $facial_detalles['caracteristicas_faciales'];
    $dir = "$id_empleado-$cedula";

    // Validate file upload
    if (!isset($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
        throw new Exception('Image upload failed');
    }

    // Prepare files
    $nombreArchivo = $_FILES['image']['name'];
    $extension = strtolower(pathinfo($nombreArchivo, PATHINFO_EXTENSION));
    $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];

    if (!in_array($extension, $allowed_extensions)) {
        throw new Exception('Invalid file type');
    }

    $rutaDestino = "$dir.$extension";
    
    // Save facial characteristics hex file
    file_put_contents("$dir.hex", $detalles);
    
    // Move uploaded file
    if (!move_uploaded_file($_FILES['image']['tmp_name'], $rutaDestino)) {
        throw new Exception('Failed to move uploaded file');
    }

    // Prepare cURL request to CheckID service
    $uploadUrl = 'http://127.0.0.1:8000/compare_binary/';
    $curl = curl_init();
    
    curl_setopt_array($curl, [
        CURLOPT_URL => $uploadUrl,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_HTTPHEADER => [
            'Accept: application/json'
        ],
        CURLOPT_POSTFIELDS => [
            'image' => new CURLFile($rutaDestino),
            'hex_file' => new CURLFile("$dir.hex")
        ]
    ]);

    // Execute cURL request
    $response = curl_exec($curl);
    $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    $error = curl_error($curl);
    curl_close($curl);

    if ($error) {
        throw new Exception("cURL Error: $error");
    }

    // Process CheckID response
    $decode = json_decode($response, true);
    
    if (!$decode || !isset($decode['result'])) {
        throw new Exception('Invalid response from CheckID service');
    }

    $result = $decode['result'];
    $distance = $result['distance'] ?? null;
    $is_same = $result['is_same'] ?? false;

    // Determine confidence and status
    $confianza_reconocimiento = $is_same ? 95 : (100 - ($distance * 100));
    $estatus = $is_same ? 'Válido' : 'Sospechoso';

    // Additional device information
    $ip_dispositivo = $_SERVER['REMOTE_ADDR'] ?? null;
    $ip_dispositivo_info = gethostbyaddr($ip_dispositivo);

    // Current timestamp
    $fecha_hora = date('Y-m-d H:i:s');

    // Optional: Image verification (base64 encoded)
    $image_verificacion = base64_encode(file_get_contents($rutaDestino));

    // Save attendance record
    $resultado = $asistencia->save_asistencia(
        $id_empleado,
        $tipo_registro,
        $fecha_hora,
        $image_verificacion,
        $confianza_reconocimiento,
        $latitud,
        $longitud,
        null, // id_sede (default null)
        $perimetro,
        $ip_dispositivo,
        $ip_dispositivo_info,
        $estatus,
        $is_same ? null : 'Reconocimiento facial no concluyente'
    );

    // Cleanup temporary files
    unlink("$dir.hex");
    unlink($rutaDestino);

    // Respond with result
    if ($resultado) {
        http_response_code(200);
        echo json_encode([
            'success' => true,
            'message' => 'Attendance saved successfully',
            'estatus' => $estatus,
            'confianza' => $confianza_reconocimiento
        ]);
    } else {
        throw new Exception('Failed to save attendance');
    }

} catch (Exception $e) {
    // Error handling
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
} finally {
    // Cleanup: remove temporary files if they still exist
    $tempFiles = ["$dir.hex", "$dir.$extension"];
    foreach ($tempFiles as $file) {
        if (file_exists($file)) {
            unlink($file);
        }
    }
}
?>