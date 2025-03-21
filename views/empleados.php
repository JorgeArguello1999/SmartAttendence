<?php
require_once '../database/query.php';
$empleados = new Empleados();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Trabajadores</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-blue-50 text-blue-900 flex flex-col items-center p-6">
    <h1 class="text-3xl font-bold text-blue-800 mb-6">Trabajadores</h1>
    <table class="w-full max-w-4xl bg-white shadow-lg rounded-lg overflow-hidden">
        <thead class="bg-blue-700 text-white">
            <tr>
                <th class="p-4">ID</th>
                <th class="p-4">Nombre</th>
                <th class="p-4">Imagen</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($empleados->get_all() as $row): ?>
                <tr class="border-b border-gray-300">
                    <?php foreach ($row as $key => $item): ?>
                        <?php if ($key == 'imagen_rostro'): ?>
                            <td class="p-4 flex justify-center">
                                <img src='data:image/jpeg;base64,<?= base64_encode($item) ?>' class='w-12 h-12 rounded-full object-cover'/>
                            </td>
                        <?php else: ?>
                            <td class="p-4 text-center"> <?= htmlspecialchars($item) ?> </td>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>
