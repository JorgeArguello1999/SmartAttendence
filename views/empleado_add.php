<?php 

require_once '../views/template.php';
require_once '../database/query.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $cedula = $_POST['cedula'];
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $correo = $_POST['correo'];
    $telefono = $_POST['telefono'];
    $fecha_ingreso = $_POST['fecha_ingreso'];
    $departamento_id = $_POST['departamento_id'];
    $cargo_id = $_POST['cargo_id'];

    // Validaciones básicas
    if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        die("Correo inválido.");
    }
    if (!is_numeric($telefono) || strlen($telefono) < 7) {
        die("Número de teléfono inválido.");
    }

    $empleado = new Empleados();
    $result = $empleado->insertarEmpleado($cedula, $nombre, $apellido, $correo, $telefono, $fecha_ingreso, $departamento_id, $cargo_id);

    if ($result) {
        echo "<script>alert('Empleado insertado correctamente.'); window.location.href='ver_empleados.php';</script>";
    } else {
        echo "<script>alert('Error al insertar el empleado.'); window.history.back();</script>";
    }
} 
?>
<body class="bg-gray-100 flex items-center justify-center h-screen">
    <div class="bg-white p-8 rounded-xl shadow-md w-full max-w-md">
        <h2 class="text-2xl font-bold mb-6 text-center">Registrar Empleado</h2>
        <form action="empleado_add.php" method="POST" class="space-y-4">
            <div>
                <label class="block text-gray-700">Cédula</label>
                <input type="text" name="cedula" required class="w-full p-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-gray-700">Nombre</label>
                <input type="text" name="nombre" required class="w-full p-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-gray-700">Apellido</label>
                <input type="text" name="apellido" required class="w-full p-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-gray-700">Correo</label>
                <input type="email" name="correo" required class="w-full p-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-gray-700">Teléfono</label>
                <input type="text" name="telefono" required class="w-full p-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-gray-700">Fecha de Ingreso</label>
                <input type="date" name="fecha_ingreso" required class="w-full p-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-gray-700">Departamento ID</label>
                <input type="number" name="departamento_id" required class="w-full p-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-gray-700">Cargo ID</label>
                <input type="number" name="cargo_id" required class="w-full p-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
            </div>
            <button type="submit" class="w-full bg-blue-600 text-white p-2 rounded-lg hover:bg-blue-700 transition">Registrar</button>
        </form>
    </div>
</body>
</html>
