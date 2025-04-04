<?php

function sendToCheckIDService($uploadedImage, $hexFile, $uploadUrl='http://127.0.0.1:5050/compare_binary/') {
    // Validate input files
    if (!$uploadedImage || !$hexFile) {
        return [
            'success' => false, 
            'error' => 'Missing image or hex file'
        ];
    }

    // Check if files are valid
    if ($uploadedImage['error'] !== UPLOAD_ERR_OK) {
        return [
            'success' => false, 
            'error' => 'Image upload error'
        ];
    }

    // Prepare cURL request
    $curl = curl_init();
    curl_setopt_array($curl, [
        CURLOPT_URL => $uploadUrl,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_HTTPHEADER => ['Accept: application/json'],
        CURLOPT_POSTFIELDS => [
            'image' => new CURLFile(
                $uploadedImage['tmp_name'], 
                $uploadedImage['type'], 
                $uploadedImage['name']
            ),
            'hex_file' => new CURLFile(
                $hexFile['tmp_name'], 
                $hexFile['type'], 
                $hexFile['name']
            )
        ]
    ]);

    // Execute cURL request
    $response = curl_exec($curl);
    $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    $error = curl_error($curl);
    curl_close($curl);

    // Handle cURL errors
    if ($error) {
        return [
            'success' => false, 
            'error' => "cURL Error: $error"
        ];
    }

    // Decode and return response
    $decode = json_decode($response, true);
    return [
        'success' => $httpCode === 200,
        'result' => $decode['result'] ?? null,
        'httpCode' => $httpCode
    ];
}
?>