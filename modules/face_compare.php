<?php

class Rostros{
    private $URL = "http://127.0.0.1:8000/";

    public function obtener_binario ($file): string{
        $url = $this->URL."get_binary/";
        $ch = curl_init(); // Init cURL
        $file = new CURLFile(
            $_FILES['file']['tmp_name'], 
            $_FILES['files']['type'],
            $_FILES['file']['name'],
        );
        $data = ['file' => $file];

        // Config request
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Exec 
        $response = curl_exec($ch);
        if ($response === false) {
            $error_message = curl_error($ch); // Obtiene el mensaje de error
            curl_close($ch);
            return 'Error: ' . $error_message;
        }

        curl_close($ch);
        return $response;
    }

    public function comparar_rostros($file1, $file2): string {
        $url = $this->URL.'compare2faces/';
        $ch = curl_init(); // Inicializa cURL

        // Usamos CURLFile para enviar los archivos
        $file1 = new CURLFile($file1['tmp_name'], $file1['type'], $file1['name']);
        $file2 = new CURLFile($file2['tmp_name'], $file2['type'], $file2['name']);

        $data = [
            'file1' => $file1,
            'file2' => $file2
        ];

        // Configura la solicitud cURL
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Ejecuta la solicitud
        $response = curl_exec($ch);
        
        // Maneja errores en caso de fallo
        if ($response === false) {
            $error_message = curl_error($ch); // Obtiene el mensaje de error
            curl_close($ch);
            return 'Error: ' . $error_message;
        }

        // Cierra la conexión cURL
        curl_close($ch);
        return $response; // 
    }

}

?>