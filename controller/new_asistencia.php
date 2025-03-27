<?php
require_once 'database/query.php';

class AsistenciaController {
    private $db;

    public function __construct() {
        $this->db = new RegistroAsistencia();
    }

    public function save_asistencia($data) {
        // Extraer datos del array asociativo
        $id_empleado = $data['id_empleado'];
        $tipo_registro = $data['tipo_registro'];
        $fecha_hora = $data['fecha_hora'];
        $image_verificacion = $data['image_verificacion'] ?? null;
        $confianza_reconocimiento = $data['confianza_reconocimiento'];
        $latitud = $data['latitud'];
        $longitud = $data['longitud'];
        $id_sede = $data['id_sede'];
        $perimetro = $data['perimetro'];
        $ip_dispositivo = $data['ip_dispositivo'];
        $ip_dispositivo_info = $data['ip_dispositivo_info'];
        $estatus = $data['estatus'];
        $observaciones = $data['observaciones'];
        
        // Llamar al método de base de datos
        $result = $this->db->save_asistencia(
            $id_empleado, $tipo_registro, $fecha_hora, $image_verificacion,
            $confianza_reconocimiento, $latitud, $longitud, $id_sede,
            $perimetro, $ip_dispositivo, $ip_dispositivo_info, $estatus, $observaciones
        );

        if ($result) {
            return json_encode(['status' => 'success', 'message' => 'Asistencia registrada exitosamente', 'id' => $result]);
        } else {
            return json_encode(['status' => 'error', 'message' => 'Error al registrar la asistencia']);
        }
    }
}

?>