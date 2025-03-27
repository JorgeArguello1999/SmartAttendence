<?php
require_once '../controller/new_user.php';

$controller = new EmpleadoController();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $controller->registrarEmpleadoConBiometria();
}
?>
<form action="" method="POST" enctype="multipart/form-data">
    <h2>Datos del Empleado</h2>
    <input type="text" name="cedula" placeholder="Cédula" required>
    <input type="text" name="nombre" placeholder="Nombre" required>
    <input type="text" name="apellido" placeholder="Apellido" required>
    <input type="email" name="correo" placeholder="Correo" required>
    <input type="text" name="telefono" placeholder="Teléfono" required>
    <input type="date" name="fecha_ingreso" required>
    <input type="number" name="departamento_id" placeholder="Departamento ID" required>
    <input type="number" name="cargo_id" placeholder="Cargo ID" required>

    <h2>Datos Biométricos</h2>
    <input type="file" name="imagen_rostro" accept="image/*" required>
    <input type="text" name="caracteristicas_faciales" placeholder="Características faciales (HEX)" required>

    <button type="submit">Registrar Empleado y Biométricos</button>
</form>

