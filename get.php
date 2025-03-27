<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['image'])) {
    $image = $_FILES['image'];
    $fileName = $image['name'];
    $fileTmpName = $image['tmp_name'];
    $fileError = $image['error'];

    if ($fileError === 0) {
        // Verificar que el archivo tenga extensión válida
        $fileExt = pathinfo($fileName, PATHINFO_EXTENSION);
        $allowedExts = ['jpg', 'jpeg', 'png', 'gif']; // Extensiones permitidas

        if (in_array(strtolower($fileExt), $allowedExts)) {
            // Preparar la imagen para ser enviada
            $imgData = file_get_contents($fileTmpName);

            // Usar cURL para enviar la imagen a la API
            $url = 'http://192.168.20.11:8000/get_binary/';
            $postData = [
                'file' => new CURLFile($fileTmpName, mime_content_type($fileTmpName), $fileName)
            ];

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            $response = curl_exec($ch);
            curl_close($ch);

            echo '<pre>Respuesta de la API: ' . htmlspecialchars($response) . '</pre>';
        } else {
            echo '<p>La extensión del archivo no es válida. Solo se permiten imágenes JPG, JPEG, PNG y GIF.</p>';
        }
    } else {
        echo '<p>Error al cargar la imagen. Intente nuevamente.</p>';
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cargar Imagen a API</title>
</head>
<body>
    <h2>Cargar Imagen a la API</h2>
    <form action="" method="POST" enctype="multipart/form-data">
        <label for="image">Seleccionar imagen:</label>
        <input type="file" name="image" id="image" required>
        <button type="submit">Enviar</button>
    </form>
</body>
</html>
