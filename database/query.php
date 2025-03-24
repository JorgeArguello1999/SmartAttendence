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

    public function save_asistencia($id_empleado, $tipo_registro, $fecha_hora, $image_verificacion, $confianza_reconocimiento, $latitud, $longitud, $id_sede, $perimetro, $ip_dispositivo, $ip_dispositivo_info, $estatus, $observaciones){
        // Preparar la consulta SQL
        $sql = "INSERT INTO RegistrosAsistencia (
            id_empleado, tipo_registro, fecha_hora, imagen_verificacion, latitud, longitud, dispositivo_info, ip_dispositivo,
            id_sede, estatus, observaciones) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        // Preparar la consulta
        if ($smt = $this->conn->prepare($sql)) {
            // Vincular los parámetros a la consulta
            $smt->bind_param("isssdssssss", $id_empleado, $tipo_registro, $fecha_hora, $image_verificacion, $latitud, $longitud, $ip_dispositivo_info, $ip_dispositivo, $id_sede, $estatus, $observaciones);

            // Ejecutar la consulta
            $result = false;
            if ($smt->execute()) {
                $result = true;
            }

            // Cerrar el statement
            $smt->close();

            // Retornar el resultado
            return $result;
        } else {
            // En caso de error en la preparación de la consulta, retornar el error
            return $this->conn->error;
        }
    }
}

class DatosBiometricos extends main{
    public function get_id_facial_details($cedula){
        $sql = "SELECT Empleados.id_empleado, DatosBiometricos.caracteristicas_faciales FROM 
        `DatosBiometricos` INNER JOIN Empleados on Empleados.id_empleado = DatosBiometricos.id_empleado 
        where Empleados.cedula = '$cedula';";
        $result = mysqli_query($this->conn, $sql);

        if($result){
            $result = mysqli_fetch_assoc($result);
            return $result;
        }
        return False;

    }
}

?>