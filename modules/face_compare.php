<?php

class Rostros {
    private $URL = "http://127.0.0.1:8000/";

    // Método para obtener archivo binario
    public function obtener_binario($file) {
        $url = $this->URL . "get_binary/";
        $ch = curl_init();

        // Validar si el archivo está presente
        if (empty($_FILES['file'])) {
            return $this->response(400, "Error: No file uploaded.");
        }

        // Crear archivo CURLFile
        $file = new CURLFile(
            $_FILES['file']['tmp_name'], 
            $_FILES['file']['type'], 
            $_FILES['file']['name']
        );

        $data = ['file' => $file];

        // Configurar la solicitud cURL
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Ejecutar la solicitud
        $response = curl_exec($ch);

        // Verificar si hay errores en la solicitud
        if ($response === false) {
            $error_message = curl_error($ch);
            curl_close($ch);
            return $this->response(500, 'Error: ' . $error_message);
        }

        // Cerrar la conexión cURL
        curl_close($ch);
        return $this->response(200, $response);  // Retornar respuesta exitosa
    }

    // Método para comparar dos rostros
    public function comparar_rostros($file1, $file2) {
        $url = $this->URL . 'compare2faces/';
        $ch = curl_init();

        // Verificar si ambos archivos están presentes
        if (empty($file1) || empty($file2)) {
            return $this->response(400, "Error: Both files must be uploaded.");
        }

        // Crear archivos CURLFile
        $file1 = new CURLFile($file1['tmp_name'], $file1['type'], $file1['name']);
        $file2 = new CURLFile($file2['tmp_name'], $file2['type'], $file2['name']);

        $data = [
            'file1' => $file1,
            'file2' => $file2
        ];

        // Configurar la solicitud cURL
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Ejecutar la solicitud
        $response = curl_exec($ch);

        // Verificar si hay errores
        if ($response === false) {
            $error_message = curl_error($ch);
            curl_close($ch);
            return $this->response(500, 'Error: ' . $error_message);
        }

        // Cerrar la conexión cURL
        curl_close($ch);
        return $this->response(200, $response);  // Retornar respuesta exitosa
    }

    // Método para comparar un archivo binario con otro
    public function comparacion_binaria($data, $file) {
        $url = $this->URL . 'binary_compare/';
        $ch = curl_init();

        // Verificar si el archivo está presente
        if (empty($file)) {
            return $this->response(400, "Error: File must be uploaded.");
        }

        // Crear archivo CURLFile
        $file = new CURLFile($file['tmp_name'], $file['type'], $file['name']);
        
        $data = [
            'file' => $file,
            'binary' => $data
        ];

        // Configurar la solicitud cURL
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Ejecutar la solicitud
        $response = curl_exec($ch);

        // Verificar si hay errores
        if ($response === false) {
            $error_message = curl_error($ch);
            curl_close($ch);
            return $this->response(500, 'Error: ' . $error_message);
        }

        // Cerrar la conexión cURL
        curl_close($ch);
        return $this->response(200, $response);  // Retornar respuesta exitosa
    }

    // Método para generar respuestas consistentes en formato JSON
    private function response($status_code, $message) {
        // Definir cabeceras HTTP
        return json_encode($message);
    }
}

?>