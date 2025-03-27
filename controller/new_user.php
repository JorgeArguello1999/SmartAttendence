<?php
// Controlar la inserción de usuario 
require_once '../database/query.php';

class EmpleadoController {
    public function agregarEmpleado() {
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
            $resultado = $empleado->insertarEmpleado($cedula, $nombre, $apellido, $correo, $telefono, $fecha_ingreso, $departamento_id, $cargo_id);

            if ($resultado) {
                echo "<script>alert('Empleado insertado correctamente.'); window.location.href='ver_empleados.php';</script>";
            } else {
                echo "<script>alert('Error al insertar el empleado.'); window.history.back();</script>";
            }
        }
    }
}
?>