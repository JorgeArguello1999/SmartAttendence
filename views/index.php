<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro Biométrico</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h1>Registro Biométrico</h1>

    <input type="text" id="cedula" placeholder="Ingrese su cédula" maxlength="10" pattern="\d{10}" required>

    <button id="requestPermissions">Solicitar Permisos</button>

    <video id="video" autoplay></video>
    <button id="capture">Capturar Foto</button>
    <canvas id="canvas"></canvas>

    <select id="tipo_registro">
        <option value="Entrada">Entrada</option>
        <option value="Salida">Salida</option>
    </select>

    <button id="sendData">Enviar Datos</button>

    <script src="script.js"></script>
</body>
</html>
