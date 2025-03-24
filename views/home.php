<?php
/**
require_once 'modules/face_compare.php';
require_once 'api/handler.php';
$rostros = new Rostros();
$api = new ApiHandler($rostros);
$response = $api->handleRequest();
echo $response;
 */
?>
<!-- 
<form action="http://127.0.0.1:82/" method="POST" enctype="multipart/form-data">
        <label for="file">Selecciona una foto:</label>
        <input type="file" id="file" name="file" accept="image/png" required>
        <br><br>
        <button type="submit">Subir</button>
</form>

<form action="http://127.0.0.1:82/" method="POST" enctype="multipart/form-data">
    <label for="file1">Selecciona la primera imagen:</label>
    <input type="file" name="file1" id="file1" required><br><br>

    <label for="file2">Selecciona la segunda imagen:</label>
    <input type="file" name="file2" id="file2" required><br><br>

    <input type="submit" value="Comparar Rostros">
</form>

<form action="http://127.0.0.1:82/" method="POST" enctype="multipart/form-data">
    <label for="file">Selecciona una imagen:</label>
    <input type="file" name="file" id="file" required><br><br>
        
    <label for="binary">Introduce la cadena binaria:</label><br>
    <textarea name="binary" id="binary" rows="4" cols="50" required></textarea><br><br>
        
    <input type="submit" value="Enviar">
 </form>
 -->

<?php

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SmartAttendance</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-blue-50 text-blue-900">
    <!-- Navbar -->
    <nav class="bg-blue-700 text-white p-4 shadow-lg">
        <div class="container mx-auto flex justify-between items-center">
            <h1 class="text-2xl font-bold">SmartAttendance</h1>
            <ul class="flex space-x-6">
                <li><a href="views/empleados.php" class="hover:underline">Empleados</a></li>
                <li><a href="views/asistencia.php" class="hover:underline">Asistencia</a></li>
                <li><a href="#contact" class="hover:underline">Contacto</a></li>
            </ul>
        </div>
    </nav>

    <!-- Hero Section -->
    <header class="container mx-auto text-center py-20">
        <h2 class="text-4xl font-bold text-blue-800">Automatiza la asistencia de tu equipo</h2>
        <p class="mt-4 text-lg">Una solución eficiente y segura para gestionar la asistencia en tu organización.</p>
        <button class="mt-6 bg-blue-700 hover:bg-blue-800 text-white py-2 px-6 rounded-lg shadow-lg text-lg">
            Empezar Ahora
        </button>
    </header>

    <!-- Footer -->
    <footer class="bg-blue-700 text-white text-center p-4 mt-16">
        <p>&copy; 2025 SmartAttendance. Todos los derechos reservados.</p>
    </footer>
</body>
</html>

