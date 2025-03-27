<?php
require_once '../database/query.php';
require_once '../api/get_binary.php';

class EmpleadoController {
    private $modelo;
    private $biometricos; 
    private $get_binary;

    public function __construct() {
        $this->modelo = new Empleados();
        $this->biometricos = new DatosBiometricos();
    }

    public function registrarEmpleadoConBiometria() {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Paso 1: Insertar el usuario en la base de datos
            $cedula = $_POST['cedula'];
            $nombre = $_POST['nombre'];
            $apellido = $_POST['apellido'];
            $correo = $_POST['correo'];
            $telefono = $_POST['telefono'];
            $fecha_ingreso = $_POST['fecha_ingreso'];
            $departamento_id = $_POST['departamento_id'];
            $cargo_id = $_POST['cargo_id'];

            // Insertamos el empleado y obtenemos el ID generado
            $id_empleado = $this->modelo->insertarEmpleado($cedula, $nombre, $apellido, $correo, $telefono, $fecha_ingreso, $departamento_id, $cargo_id);

            if (!$id_empleado) {
                die(json_encode(["status" => "error", "message" => "Error al registrar el empleado."]));
            }

            // Paso 2: Insertar los datos biométricos si se subió una imagen
            if (!empty($_FILES['imagen_rostro']) && isset($_POST['caracteristicas_faciales'])) {
                $imagen_rostro = file_get_contents($_FILES['imagen_rostro']['tmp_name']);


                // Obtener el id manualmente
                $id_empleado = $this->modelo->get_id_from_cedula($cedula);
                $id_empleado = $id_empleado['id_empleado'];

                // Obtener el hex
                // $caracteristicas_faciales = $_POST['caracteristicas_faciales'];
                $caracteristicas_faciales = uploadImageToAPI($_FILES['imagen_rostro']);
                $caracteristicas_faciales = $caracteristicas_faciales['message'];

                // Insertar datos biométricos
                $resultado = $this->biometricos->insertar_datos_biometricos($id_empleado, $imagen_rostro, $caracteristicas_faciales);

                if (!$resultado) {
                    die(json_encode(["status" => "error", "message" => "Error al registrar los datos biométricos."]));
                }
            }

            // Respuesta de éxito
            echo json_encode(["status" => "success", "message" => "Empleado y datos biométricos registrados correctamente."]);
        } else {
            die(json_encode(["status" => "error", "message" => "Método no permitido."]));
        }
    }
}
