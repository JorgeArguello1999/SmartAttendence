<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['file'])) {
    // Ruta de la API a la que enviar el archivo
    $url = 'http://127.0.0.1:8000/get_binary/';

    // Iniciar cURL
    $ch = curl_init();

    // Preparar los datos del archivo
    $file = new CURLFile($_FILES['file']['tmp_name'], $_FILES['file']['type'], $_FILES['file']['name']);

    // Crear el array con los datos a enviar
    $data = [
        'file' => $file
    ];

    // Configurar la solicitud cURL
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    // Ejecutar la solicitud
    $response = curl_exec($ch);

    // Verificar si hubo error en la solicitud
    if ($response === false) {
        echo 'Error en la solicitud: ' . curl_error($ch);
    } else {
        echo 'Respuesta de la API: ' . $response;
    }

    // Cerrar cURL
    curl_close($ch);
}

?>
<form action="experimento.php" method="POST" enctype="multipart/form-data">
    <label for="file">Selecciona una foto:</label>
    <input type="file" name="file" id="file" required>
    <button type="submit">Enviar</button>
</form>

<h1>other</h1>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subir Foto</title>
</head>
<body>
    <h1>Subir Foto</h1>
    <form action="http://127.0.0.1:8000/get_binary/" method="POST" enctype="multipart/form-data">
        <label for="file">Selecciona una foto:</label>
        <input type="file" id="file" name="file" accept="image/png" required>
        <br><br>
        <button type="submit">Subir</button>
    </form>
</body>
</html>
