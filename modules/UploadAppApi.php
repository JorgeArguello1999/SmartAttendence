<?php
namespace ImageUploader;

class APIUploader {
    /**
     * Carga una imagen a una API mediante cURL
     * 
     * @param string $imagePath Ruta completa del archivo de imagen
     * @param string $apiUrl URL de la API de destino
     * @param string $binaryData Datos binarios adicionales (opcional)
     * @param array $extraOptions Opciones adicionales de cURL (opcional)
     * @return array Resultado de la subida
     */
    public static function uploadToAPI(
        string $imagePath, 
        string $apiUrl = 'http://127.0.0.1:8000/binary_compare/', 
        string $binaryData = '', 
        array $extraOptions = []
    ): array {
        // Verificaciones iniciales
        if (!file_exists($imagePath)) {
            return [
                'success' => false,
                'error' => "El archivo $imagePath no existe."
            ];
        }

        if (!is_readable($imagePath)) {
            return [
                'success' => false,
                'error' => "No se puede leer el archivo $imagePath."
            ];
        }

        // Obtener información del archivo
        $fileInfo = new \finfo(FILEINFO_MIME_TYPE);
        $mimeType = $fileInfo->file($imagePath);

        // Preparar los datos para el formulario
        $postFields = [
            'file' => new \CURLFile($imagePath, $mimeType, basename($imagePath))
        ];

        // Añadir datos binarios si se proporcionan
        if (!empty($binaryData)) {
            $postFields['binary'] = $binaryData;
        }

        // Configuración por defecto de cURL
        $defaultOptions = [
            CURLOPT_URL => $apiUrl,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $postFields,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => 0
        ];

        // Combinar opciones por defecto con opciones extra
        $curlOptions = $extraOptions + $defaultOptions;

        // Inicializar cURL
        $curl = curl_init();
        curl_setopt_array($curl, $curlOptions);

        // Ejecutar solicitud
        $response = curl_exec($curl);

        // Manejar errores
        if (curl_errno($curl)) {
            $result = [
                'success' => false,
                'error' => curl_error($curl),
                'error_code' => curl_errno($curl),
                'debug_info' => curl_getinfo($curl)
            ];
            curl_close($curl);
            return $result;
        }

        // Cerrar conexión cURL
        curl_close($curl);

        // Devolver resultado exitoso
        return [
            'success' => true,
            'response' => $response
        ];
    }

    /**
     * Registro de errores (opcional)
     * 
     * @param string $message Mensaje de error
     * @param array $context Contexto adicional
     */
    private static function logError(string $message, array $context = []): void {
        // Implementar según necesidad (archivo, base de datos, etc.)
        error_log($message . ' ' . json_encode($context));
    }
}