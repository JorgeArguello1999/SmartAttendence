<?php
$uploadUrl = 'http://127.0.0.1:8000/compare_binary/';

// Archivos a enviar
$imagePath = 'photo1.png';
$hexFilePath = 'hex_code.hex';

// Verifica que los archivos existen
if (!file_exists($imagePath) || !file_exists($hexFilePath)) {
    die(json_encode(["error" => "Uno o ambos archivos no existen"]));
}

// Configuración de la solicitud cURL
$curl = curl_init();
curl_setopt_array($curl, [
    CURLOPT_URL => $uploadUrl,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_HTTPHEADER => [
        'Accept: application/json'
    ],
    CURLOPT_POSTFIELDS => [
        'image' => new CURLFile($imagePath),
        'hex_file' => new CURLFile($hexFilePath)
    ]
]);

// Ejecuta la solicitud y obtiene la respuesta
$response = curl_exec($curl);
$httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
$error = curl_error($curl);
curl_close($curl);

// Configura los encabezados para la respuesta JSON
header('Content-Type: application/json');

if ($error) {
    echo json_encode(["error" => $error]);
} else {
    echo json_encode(["status_code" => $httpCode, "response" => json_decode($response, true)]);
}
