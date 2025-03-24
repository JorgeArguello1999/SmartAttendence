<?php

// Base de datos
require_once 'database/query.php';
$biometrico = new DatosBiometricos();
$cedula = $_POST['cedula'] ?? '';
$facial_detalles = $biometrico->get_id_facial_details($cedula);
$id = $facial_detalles['id_empleado'];
$detalles = $facial_detalles['caracteristicas_faciales'];

$dir = "$id-$cedula";
file_put_contents("$dir.hex", $detalles);

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
        'image' => new CURLFile("$dir.$extension"),
        'hex_file' => new CURLFile("$dir.hex")
    ]
]);

// Ejecutar la solicitud y obtener la respuesta
$response = curl_exec($curl);
$httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
$error = curl_error($curl);
curl_close($curl);

if ($error) {
    $response = json_encode(["error" => $error]);
} 

$decode = json_decode($response, true);
$decode = $decode['result'];
$distance = $decode['distance'];
$is_same = $decode['is_same'];

// Guardar en la base de datos 

// Delete files
unlink("$dir.hex");
unlink("$dir.$extension");

?>