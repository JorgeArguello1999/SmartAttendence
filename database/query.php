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
        LEFT JOIN Departamentos ON Departamentos.id_departamento = Empleados.id_departamento 
        LEFT JOIN Cargos on Cargos.id_cargo = Empleados.id_cargo 
        LEFT JOIN DatosBiometricos ON DatosBiometricos.id_empleado = Empleados.id_empleado; ";
        $result = mysqli_query($this->conn, $sql);
        return $result;
    }

    public function get_id_from_cedula($cedula) {
        $sql = "SELECT Empleados.id_empleado, Empleados.cedula, Empleados.nombres, Empleados.apellidos
                FROM Empleados WHERE Empleados.cedula = ? LIMIT 1;";

        if ($stmt = $this->conn->prepare($sql)) {
            $stmt->bind_param("s", $cedula);
            $stmt->execute();
            $stmt->store_result();
            if ($stmt->num_rows > 0) {
                $stmt->bind_result($id_empleado, $cedula, $nombres, $apellidos);
                $stmt->fetch();
                $empleado = [
                    'id_empleado' => $id_empleado,
                    'cedula' => $cedula,
                    'nombres' => $nombres,
                    'apellidos' => $apellidos
                ];
                $stmt->close();
                return $empleado;
            } else {
                $stmt->close();
                return false;
            }
        } else {
            error_log("Error en la consulta: " . $this->conn->error);
            return false;
        }
    }
    
    public function insertarEmpleado($cedula, $nombres, $apellidos, $email, $telefono, $fecha_contratacion, $id_departamento) {
        // Valor por defecto para estado
        $estado = 'Activo';

        // Consulta SQL corregida con las columnas y valores correctos
        $sql = "INSERT INTO Empleados (cedula, nombres, apellidos, email, telefono, fecha_contratacion, id_departamento, estado) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

        // Preparar la consulta
        if ($stmt = $this->conn->prepare($sql)) {
            // Vincular los parámetros a la consulta
            $stmt->bind_param("ssssssis", 
                $cedula, 
                $nombres, 
                $apellidos, 
                $email, 
                $telefono, 
                $fecha_contratacion, 
                $id_departamento, 
                $estado  // Se agrega aquí correctamente
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

class RegistroAsistencia extends main{
    public $conn;

    public function get_all(){
        $sql = "SELECT 
        RegistrosAsistencia.id_empleado, Empleados.nombres, 
        Empleados.apellidos, Empleados.email, Empleados.telefono, 
        RegistrosAsistencia.fecha_hora, RegistrosAsistencia.dispositivo_info, 
        RegistrosAsistencia.estatus, RegistrosAsistencia.imagen_verificacion, 
        Sedes.nombre, RegistrosAsistencia.latitud, RegistrosAsistencia.longitud 
        FROM RegistrosAsistencia 
        INNER JOIN Empleados ON Empleados.id_empleado = RegistrosAsistencia.id_empleado 
        LEFT JOIN Sedes ON Sedes.id_sede = RegistrosAsistencia.id_sede 
        WHERE RegistrosAsistencia.id_sede IS NULL OR Sedes.id_sede IS NOT NULL; ";
        $result = mysqli_query($this->conn, $sql);
        return $result;
    }
    
    public function save_asistencia($id_empleado, $tipo_registro, $fecha_hora, $image_verificacion, $confianza_reconocimiento, $latitud, $longitud, $id_sede, $perimetro, $ip_dispositivo, $ip_dispositivo_info, $estatus, $observaciones) {
        // Preparar la consulta SQL con todas las columnas correctas
        $sql = "INSERT INTO RegistrosAsistencia (
            id_empleado, tipo_registro, fecha_hora, imagen_verificacion, confianza_reconocimiento, 
            latitud, longitud, id_sede, dentro_perimetro, ip_dispositivo, 
            dispositivo_info, estatus, observaciones
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        // Verificar si hay imagen antes de preparar
        if ($stmt = $this->conn->prepare($sql)) {
            // Vincular los parámetros, usando tipos de datos apropiados
            $stmt->bind_param(
                "isssdsdssssss", 
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

            // Manejo específico para datos largos (imagen)
            if (!is_null($image_verificacion)) {
                // Usar send_long_data si la imagen es grande
                $stmt->send_long_data(3, $image_verificacion);
            }

            // Ejecutar la consulta
            try {
                $result = $stmt->execute();
                
                // Verificar si la inserción fue exitosa
                if ($result) {
                    $last_id = $this->conn->insert_id; // Obtener el ID del último registro insertado
                    $stmt->close();
                    return $last_id; // Devolver el ID del registro insertado
                } else {
                    // Registro de error si la ejecución falla
                    error_log("Error al ejecutar la inserción: " . $stmt->error);
                    $stmt->close();
                    return false;
                }
            } catch (Exception $e) {
                // Capturar cualquier excepción durante la ejecución
                error_log("Excepción al guardar asistencia: " . $e->getMessage());
                $stmt->close();
                return false;
            }
        } else {
            // Capturar errores de preparación de la consulta
            error_log("Error en la preparación de la consulta SQL: " . $this->conn->error);
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

    public function insertar_datos_biometricos($id_empleado, $imagen_rostro, $caracteristicas_faciales){
        $sql = "INSERT INTO DatosBiometricos (id_empleado, imagen_rostro, caracteristicas_faciales, fecha_registro) 
                VALUES (?, ?, ?, CURRENT_TIMESTAMP())";

        // Preparar la consulta
        if ($stmt = $this->conn->prepare($sql)) {
            // Enviar datos de imagen si no es null
            if (!is_null($imagen_rostro)) {
                $stmt->send_long_data(1, $imagen_rostro);
            }

            // Vincular los parámetros a la consulta
            $stmt->bind_param("iss", 
                $id_empleado, 
                $imagen_rostro, 
                $caracteristicas_faciales
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

?>