<?php

require_once 'connect.php';

class main{
    public $conn;

    public function __construct(){
        $database = new database();
        $this->conn = $database->conn;
    }
}

class Empleados extends main{
    public $conn;

    public function get_all(){
        $sql = "SELECT 
            Empleados.cedula, Empleados.nombres, 
            Empleados.apellidos, Empleados.email, 
            Cargos.nombre as cargo, 
            Departamentos.nombre as departamento, 
            DatosBiometricos.imagen_rostro 
        from Empleados 
        INNER JOIN Departamentos ON Departamentos.id_departamento = Empleados.id_departamento 
        INNER JOIN Cargos on Cargos.id_cargo = Empleados.id_cargo 
        INNER JOIN DatosBiometricos ON DatosBiometricos.id_empleado = Empleados.id_empleado; ";
        $result = mysqli_query($this->conn, $sql);
        return $result;
    }
}

class RegistroAsistencia extends main{
    public $conn;

    public function get_all(){
        $sql = "SELECT 
            RegistrosAsistencia.id_empleado, 
            Empleados.nombres, Empleados.apellidos, 
            Empleados.email, Empleados.telefono, 
            RegistrosAsistencia.fecha_hora, 
            RegistrosAsistencia.dispositivo_info,
            RegistrosAsistencia.estatus, 
            RegistrosAsistencia.imagen_verificacion, 
            Sedes.nombre, Sedes.latitud, Sedes.longitud 
        FROM RegistrosAsistencia 
        INNER JOIN Empleados ON Empleados.id_empleado = RegistrosAsistencia.id_empleado 
        INNER JOIN Sedes ON Sedes.id_sede = RegistrosAsistencia.id_sede; ";
        $result = mysqli_query($this->conn, $sql);
        return $result;
    }

   public function save_asistencia($id_empleado, $tipo_registro, $fecha_hora, $image_verificacion, $confianza_reconocimiento, $latitud, $longitud, $id_sede, $perimetro, $ip_dispositivo, $ip_dispositivo_info, $estatus, $observaciones) {
        // Preparar la consulta SQL con todas las columnas correctas
        $sql = "INSERT INTO RegistrosAsistencia (
            id_empleado, tipo_registro, fecha_hora, imagen_verificacion, confianza_reconocimiento, latitud, longitud, 
            id_sede, dentro_perimetro, ip_dispositivo, dispositivo_info, estatus, observaciones) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        // Preparar la consulta
        if ($stmt = $this->conn->prepare($sql)) {
            // Enviar datos de imagen si no es null
            if (!is_null($image_verificacion)) {
                $stmt->send_long_data(3, $image_verificacion);
            }

            // Vincular los parámetros a la consulta
            $stmt->bind_param("isssdsdssssss", 
                $id_empleado, 
                $tipo_registro, 
                $fecha_hora, 
                $image_verificacion, 
                $confianza_reconocimiento, 
                $latitud, 
                $longitud, 
                $id_sede, 
                $perimetro, 
                $ip_dispositivo, 
                $ip_dispositivo_info, 
                $estatus, 
                $observaciones
            );

            // Ejecutar la consulta
            $result = $stmt->execute();
            
            // Cerrar el statement
            $stmt->close();

            return $result;
        } else {
            // Capturar errores
            error_log("Error en la consulta SQL: " . $this->conn->error);
            return false;
        }
    }
 
}

class DatosBiometricos extends main{
    public function get_id_facial_details($cedula){
        if (!$this->conn) {
            return false;
        }

        $sql = "SELECT Empleados.id_empleado, DatosBiometricos.caracteristicas_faciales, Empleados.apellidos, Empleados.nombres
            FROM DatosBiometricos 
            INNER JOIN Empleados ON Empleados.id_empleado = DatosBiometricos.id_empleado 
            WHERE Empleados.cedula = ?";

        // Preparar la consulta
        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            return false;
        }

        // Asociar parámetros
        $stmt->bind_param("s", $cedula);

        // Ejecutar consulta
        if (!$stmt->execute()) {
            return false;
        }

        // Obtener resultados
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            return $result->fetch_assoc();
        }

        return false;
    }
}

?>