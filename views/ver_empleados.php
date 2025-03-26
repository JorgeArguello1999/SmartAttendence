<?php require_once '../views/template.php'; ?>
    <div class="max-w-4xl mx-auto bg-white p-6 rounded-lg shadow-lg">
        <h2 class="text-2xl font-bold mb-4 text-gray-700">Registro de Empleados</h2>
        <table class="w-full border-collapse border border-gray-300">
            <thead>
                <tr class="bg-gray-200">
                    <th class="border border-gray-300 p-2">Cédula</th>
                    <th class="border border-gray-300 p-2">Nombre</th>
                    <th class="border border-gray-300 p-2">Email</th>
                    <th class="border border-gray-300 p-2">Cargo</th>
                    <th class="border border-gray-300 p-2">Departamento</th>
                    <th class="border border-gray-300 p-2">Imagen</th>
                </tr>
            </thead>
            <tbody>
                <?php
                require_once '../database/query.php';
                $empleados = new Empleados();
                $datos = $empleados->get_all();
                
                while ($filas = mysqli_fetch_assoc($datos)) {
                    echo "<tr class='border border-gray-300 hover:bg-gray-100'>";
                    echo "<td class='p-2'>" . htmlspecialchars($filas['cedula']) . "</td>";
                    echo "<td class='p-2'>" . htmlspecialchars($filas['nombres'] . " " . $filas['apellidos']) . "</td>";
                    echo "<td class='p-2'>" . htmlspecialchars($filas['email']) . "</td>";
                    echo "<td class='p-2'>" . htmlspecialchars($filas['cargo']) . "</td>";
                    echo "<td class='p-2'>" . htmlspecialchars($filas['departamento']) . "</td>";
                    echo "<td class='p-2'><img class='w-16 h-16 object-cover rounded' src='data:image/png;base64," . base64_encode($filas['imagen_rostro']) . "' alt='Imagen de rostro'></td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>
