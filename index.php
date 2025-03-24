<?php

// Base de datos
require_once 'database/query.php';
$biometrico = new DatosBiometricos();
$cedula = $_POST['cedula'] ?? '';
$facial_detalles = $biometrico->get_id_facial_details($cedula);
$id = $facial_detalles['id_empleado'];
$detalles = $facial_detalles['caracteristicas_faciales'];
file_put_contents("$id-$cedula.hex", $detalles);

// Verifica si se recibió la imagen
if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
    $nombreArchivo = $_FILES['image']['name'];
    $extension = pathinfo($nombreArchivo, PATHINFO_EXTENSION);
    $rutaDestino = "$id-$cedula.$extension";

    // Guarda el archivo correctamente
    move_uploaded_file($_FILES['image']['tmp_name'], $rutaDestino);
} else {
    die("Error al subir la imagen.");
}

// Enviar a CheckID

$uploadUrl = 'http://127.0.0.1:8000/compare_binary/';

// Configuración de la solicitud cURL para reenviar los archivos
$curl = curl_init();
curl_setopt_array($curl, [
    CURLOPT_URL => $uploadUrl,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_HTTPHEADER => [
        'Accept: application/json'
    ],
    CURLOPT_POSTFIELDS => [
        'image' => new CURLFile("$id-$cedula.$extension"),
        'hex_file' => new CURLFile("$id-$cedula.hex")
    ]
]);

// Ejecutar la solicitud y obtener la respuesta
$response = curl_exec($curl);
$httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
$error = curl_error($curl);
curl_close($curl);

// Configurar encabezados de respuesta JSON
header('Content-Type: application/json');

if ($error) {
    echo json_encode(["error" => $error]);
} else {
    echo json_encode(["status_code" => $httpCode, "response" => json_decode($response, true)]);
}

?>