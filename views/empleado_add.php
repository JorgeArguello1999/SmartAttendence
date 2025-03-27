<?php 
require_once '../views/template.php'; 
require_once '../controller/new_user.php';
$controller = new EmpleadoController();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $controller->registrarEmpleadoConBiometria();
}
?>
<body class="bg-gray-100 flex items-center justify-center h-screen">
    <div class="bg-white p-8 rounded-xl shadow-md w-full max-w-md">
        <h2 class="text-2xl font-bold mb-6 text-center">Registrar Empleado y Datos Biométricos</h2>
        <form action="" method="POST" enctype="multipart/form-data" class="space-y-4">
            <!-- Campos de datos personales -->
            <div>
                <label class="block text-gray-700">Cédula</label>
                <input type="text" name="cedula" placeholder="Cédula" required class="w-full p-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-gray-700">Nombre</label>
                <input type="text" name="nombre" placeholder="Nombre" required class="w-full p-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-gray-700">Apellido</label>
                <input type="text" name="apellido" placeholder="Apellido" required class="w-full p-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-gray-700">Correo</label>
                <input type="email" name="correo" placeholder="Correo" required class="w-full p-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-gray-700">Teléfono</label>
                <input type="text" name="telefono" placeholder="Teléfono" required class="w-full p-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-gray-700">Fecha de Ingreso</label>
                <input type="date" name="fecha_ingreso" required class="w-full p-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-gray-700">Departamento ID</label>
                <input type="number" name="departamento_id" placeholder="Departamento ID" required class="w-full p-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-gray-700">Cargo ID</label>
                <input type="number" name="cargo_id" placeholder="Cargo ID" required class="w-full p-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
            </div>

            <!-- Campos biométricos -->
            <div>
                <h2 class="text-xl font-semibold mb-4">Datos Biométricos</h2>
                <label class="block text-gray-700">Imagen de Rostro</label>
                <input type="file" name="imagen_rostro" accept="image/*" required class="w-full p-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-gray-700">Características Faciales (HEX)</label>
                <input type="text" name="caracteristicas_faciales" placeholder="Características faciales (HEX)" required class="w-full p-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
            </div>

            <button type="submit" class="w-full bg-blue-600 text-white p-2 rounded-lg hover:bg-blue-700 transition">Registrar Empleado y Biométricos</button>
        </form>
    </div>
</body>
</html>
