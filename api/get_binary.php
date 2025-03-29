<?php

function uploadImageToAPI($imageFile, $apiUrl = 'http://127.0.0.1:5050/get_binary/') {
    // Validaciones previas (igual que en el ejemplo anterior)
    if ($imageFile['error'] !== UPLOAD_ERR_OK) {
        return ['success' => false, 'message' => 'Error al cargar la imagen. Intente nuevamente.'];
    }

    $fileName = $imageFile['name'];
    $fileTmpName = $imageFile['tmp_name'];
    $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
    $allowedExts = ['jpg', 'jpeg', 'png', 'gif'];

    if (!in_array($fileExt, $allowedExts)) {
        return ['success' => false, 'message' => 'La extensión del archivo no es válida. Solo se permiten imágenes JPG, JPEG, PNG y GIF.'];
    }

    // Preparar cURL request
    $postData = [
        'file' => new CURLFile($fileTmpName, mime_content_type($fileTmpName), $fileName)
    ];

    // Inicializar cURL
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $apiUrl);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    // Ejecutar solicitud
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    // Parsear el JSON
    $parsedResponse = json_decode($response, true);

    // Verificar si el JSON se parseó correctamente
    if ($parsedResponse === null && json_last_error() !== JSON_ERROR_NONE) {
        return [
            'success' => false, 
            'message' => 'Error al parsear la respuesta JSON', 
            'rawResponse' => $response,
            'jsonError' => json_last_error_msg()
        ];
    }

    // Retornar resultado
    return [
        'success' => $httpCode === 200,
        'message' => $parsedResponse['result'], // Ahora es un array asociativo
        'httpCode' => $httpCode
    ];
}

?>