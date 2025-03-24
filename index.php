<?php
$uploadUrl = 'http://127.0.0.1:8000/compare_binary/';

// Verifica que los archivos fueron subidos correctamente
if (!isset($_FILES['image']) || !isset($_FILES['hex_file'])) {
    die(json_encode(["error" => "No se recibieron ambos archivos correctamente"]));
}

// Obtener archivos temporales
$imageTmpPath = $_FILES['image']['tmp_name'];
$hexTmpPath = $_FILES['hex_file']['tmp_name'];

// Verifica que los archivos existen en la carpeta temporal
if (!file_exists($imageTmpPath) || !file_exists($hexTmpPath)) {
    die(json_encode(["error" => "Uno o ambos archivos temporales no existen"]));
}

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
        'image' => new CURLFile($imageTmpPath, $_FILES['image']['type'], $_FILES['image']['name']),
        'hex_file' => new CURLFile($hexTmpPath, $_FILES['hex_file']['type'], $_FILES['hex_file']['name'])
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
