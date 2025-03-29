<?php
echo '
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Asistencia</title>
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Metadatos para PWA -->
    <meta name="description" content="Aplicación para registro biométrico de entrada y salida">
    <meta name="theme-color" content="#3b82f6">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="Biométrico">
    
    <!-- Enlaces a íconos para iOS -->
    <link rel="apple-touch-icon" href="icons/icon-152x152.png">
    <link rel="apple-touch-icon" sizes="152x152" href="icons/icon-152x152.png">
    <link rel="apple-touch-icon" sizes="180x180" href="icons/icon-192x192.png">
    <link rel="apple-touch-icon" sizes="167x167" href="icons/icon-152x152.png">
    
    <!-- Enlace al manifest -->
    <link rel="manifest" href="manifest.json">
    
    <!-- Ícono para navegadores antiguos -->
    <link rel="shortcut icon" href="icons/icon-72x72.png">
    
</head>
<body class="bg-gray-100 p-6">
    <nav class="bg-blue-600 p-4 text-white shadow-md mb-6">
        <ul class="flex space-x-4">
            <li><a href="index.php" class="hover:underline">🫆 Biométrico</a></li>
            <li><a href="ver_asistencia.php" class="hover:underline">👩🏻‍🏫 Asistencia</a></li>
            <li><a href="ver_empleados.php" class="hover:underline">👷🏻‍♂️ Empleados</a></li>
            <li><a href="empleado_add.php" class="hover:underline">🆕 Guardar Empleado</a></li>
        </ul>
    </nav>

    <script src="register_sw.js"></script>
';
?>