<?php

require_once 'connect.php';

class Registro_Asistencia{

    private $conn;

    public function __construct(){
        $database = new database();
        $this->conn = $database->conn;
    }

    public function get_all(){
        $sql = "SELECT Empleados.cedula, Empleados.nombres, Empleados.apellidos, Empleados.email, Cargos.nombre as cargo, Departamentos.nombre as departamento, DatosBiometricos.imagen_rostro from Empleados 
        INNER JOIN Departamentos ON Departamentos.id_departamento = Empleados.id_departamento 
        INNER JOIN Cargos on Cargos.id_cargo = Empleados.id_cargo 
        INNER JOIN DatosBiometricos ON DatosBiometricos.id_empleado = Empleados.id_empleado; ";
        $result = mysqli_query($this->conn, $sql);
        return $result;
    }

    public function insert(){
        $sql = "";
        $result = mysqli_query($this->conn, $sql);
        return $result;
    }

}

?>