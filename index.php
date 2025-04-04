<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

// Llamar a la API
require_once 'api/compare_files.php';

// Error handling and logging
function logError($message) {
    error_log(date('[Y-m-d H:i:s] ') . $message . PHP_EOL, 3, 'api_error.log');
}

// API Response function
function sendResponse($success, $data = null, $error = null) {
    $response = [
        'success' => $success,
        'data' => $data,
        'error' => $error
    ];
    echo json_encode($response);
    exit;
}

// Validate input data
function validateInput($input) {
    $errors = [];

    // Validate required fields
    $requiredFields = ['cedula', 'tipo_registro', 'latitud', 'longitud'];
    foreach ($requiredFields as $field) {
        if (!isset($input[$field]) || empty($input[$field])) {
            $errors[] = "Missing or empty field: $field";
        }
    }

    // Validate file uploads
    if (!isset($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
        $errors[] = "Invalid image upload";
    }

    return $errors;
}

// Main API Handler
function handleBiometricAttendance() {
    try {
        // Validate input
        $inputErrors = validateInput($_POST);
        if (!empty($inputErrors)) {
            sendResponse(false, null, $inputErrors);
        }

        // Require database connection and classes
        require_once 'database/query.php';

        // Initialize classes
        $biometrico = new DatosBiometricos();
        $asistencia = new RegistroAsistencia();

        // Extract input data
        $cedula = $_POST['cedula'];
        $tipo_registro = $_POST['tipo_registro'];
        $latitud = $_POST['latitud'];
        $longitud = $_POST['longitud'];

        // Optional fields with defaults
        $id_sede = $_POST['id_sede'] ?? null;
        $perimetro = $_POST['perimetro'] ?? 1;
        $ip_dispositivo = $_POST['ip_dispositivo'] ?? $_SERVER['REMOTE_ADDR'];
        $ip_dispositivo_info = $_POST['ip_dispositivo_info'] ?? 'Unknown';
        $observaciones = $_POST['observaciones'] ?? 'No additional observations';

        // Get facial details
        $facial_detalles = $biometrico->get_id_facial_details($cedula);
        
        if (!$facial_detalles) {
            sendResponse(false, null, "No facial details found for given cedula");
        }

        $id = $facial_detalles['id_empleado'];
        $detalles = $facial_detalles['caracteristicas_faciales'];

        // Create temporary hex file
        $tempHexFile = tempnam(sys_get_temp_dir(), 'hex_');
        file_put_contents($tempHexFile, $detalles);

        // Prepare hex file array to match CURLFile format
        $hexFileUpload = [
            'tmp_name' => $tempHexFile,
            'type' => 'application/octet-stream',
            'name' => "$id-$cedula.hex",
            'error' => UPLOAD_ERR_OK
        ];

        // Send to CheckID service
        $checkIdResult = sendToCheckIDService($_FILES['image'], $hexFileUpload);

        // Clean up temporary hex file
        unlink($tempHexFile);

        // Check service response
        if (!$checkIdResult['success']) {
            sendResponse(false, null, $checkIdResult['error']);
        }

        $decode = $checkIdResult['result'];
        $distance = $decode['distance'];
        $is_same = $decode['is_same'];

        // Determine status
        $estatus = $is_same ? "Válido" : "Sospechoso";

        // Current timestamp
        $fecha_hora = date('Y-m-d H:i:s.u');

        // Save attendance
        $resultado = $asistencia->save_asistencia(
            $id,
            $tipo_registro,
            $fecha_hora,
            null, // image_verificacion
            $distance, // confianza_reconocimiento
            $latitud,
            $longitud,
            $id_sede,
            $perimetro,
            $ip_dispositivo,
            $ip_dispositivo_info,
            $estatus,
            $observaciones
        );

        if ($resultado) {
            sendResponse(true, [
                'id_empleado' => $id,
                'estatus' => $estatus,
                'confianza_reconocimiento' => $distance,
                'nombres' => $facial_detalles['apellidos']." ".$facial_detalles['nombres'],
            ]);
        } else {
            sendResponse(false, null, "Error saving attendance record");
        }

    } catch (Exception $e) {
        logError($e->getMessage());
        sendResponse(false, null, "Internal server error");
    }
}

// Execute the API
handleBiometricAttendance();
?>