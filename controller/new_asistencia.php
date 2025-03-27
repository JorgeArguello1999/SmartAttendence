<?php
require_once 'database/query.php';

class AsistenciaController {
    private $db;

    public function __construct() {
        $this->db = new RegistroAsistencia();
    }

    public function save_asistencia($data) {
        if (!isset($data['id_empleado']) || empty($data['id_empleado'])) {
            return json_encode(['status' => 'error', 'message' => 'El ID del empleado es obligatorio']);
        }

        // Debugging: Verifica que los datos llegan correctamente
        error_log("Datos recibidos: " . json_encode($data));

        // Extraer datos
        $id_empleado = (int) $data['id_empleado']; // Forzar a entero
        $tipo_registro = $data['tipo_registro'] ?? null;
        $fecha_hora = $data['fecha_hora'] ?? null;
        $image_verificacion = $data['image_verificacion'] ?? null;
        $confianza_reconocimiento = $data['confianza_reconocimiento'] ?? null;
        $latitud = $data['latitud'] ?? null;
        $longitud = $data['longitud'] ?? null;
        $id_sede = $data['id_sede'] ?? null;
        $perimetro = $data['perimetro'] ?? null;
        $ip_dispositivo = $data['ip_dispositivo'] ?? null;
        $ip_dispositivo_info = $data['ip_dispositivo_info'] ?? null;
        $estatus = $data['estatus'] ?? null;
        $observaciones = $data['observaciones'] ?? null;

        // Llamar a la base de datos
        $result = $this->db->save_asistencia(
            $id_empleado, $tipo_registro, $fecha_hora, $image_verificacion,
            $confianza_reconocimiento, $latitud, $longitud, $id_sede,
            $perimetro, $ip_dispositivo, $ip_dispositivo_info, $estatus, $observaciones
        );

        return $result ? json_encode(['status' => 'success', 'message' => 'Asistencia registrada', 'id' => $result])
                    : json_encode(['status' => 'error', 'message' => 'Error al registrar']);
    }
}

?>