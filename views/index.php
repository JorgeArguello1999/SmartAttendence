<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro Biométrico</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex flex-col items-center justify-center min-h-screen p-4">
    <h1 class="text-2xl font-bold text-gray-800 mb-4">Registro Biométrico</h1>

    <input type="text" id="cedula" placeholder="Ingrese su cédula" maxlength="10" pattern="\d{10}" required
        class="border border-gray-300 rounded p-2 mb-2 w-64 text-center">

    <button id="requestPermissions" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-700">Solicitar Permisos</button>

    <div class="relative mt-4 w-80 h-60 border-2 border-black">
        <video id="video" autoplay class="absolute top-0 left-0 w-full h-full"></video>
        <img id="capturedImage" class="absolute top-0 left-0 w-full h-full hidden" alt="Imagen capturada">
    </div>
    <canvas id="canvas" class="hidden"></canvas>

    <button id="capture" class="bg-green-500 text-white px-4 py-2 rounded mt-2 hover:bg-green-700">Capturar Foto</button>

    <select id="tipo_registro" class="border border-gray-300 rounded p-2 mt-2 w-64 text-center">
        <option value="Entrada">Entrada</option>
        <option value="Salida">Salida</option>
    </select>

    <button id="sendData" class="bg-blue-500 text-white px-4 py-2 rounded mt-2 hover:bg-blue-700">Enviar Datos</button>

    <div id="responseMessage" class="hidden mt-4 p-4 border rounded w-80 text-center"></div>

    <script src='script.js'></script>
</body>
</html>