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

    public function save_asistencia(){
        $sql = "
        ";
    }
}

?>