<?php
require_once 'api/get_binary.php';
// Ejemplo de uso en un script de procesamiento de formulario
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['image'])) {
    $uploadResult = uploadImageToAPI($_FILES['image']);
    
    if ($uploadResult['success']) {
        echo '<p>Imagen subida exitosamente:</p>';
        echo '<pre>' . htmlspecialchars($uploadResult['message']) . '</pre>';
    } else {
        echo '<p>' . htmlspecialchars($uploadResult['message']) . '</p>';
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