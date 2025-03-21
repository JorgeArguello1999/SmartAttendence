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
            curl_close($ch);
            return 'false';
        }

        curl_close($ch);
        return $response;
    }
}

?>