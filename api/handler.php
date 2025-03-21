<?php

class ApiHandler {
    private $rostros;

    public function __construct($rostros) {
        $this->rostros = $rostros;
    }

    // Handle file upload and binary conversion
    public function handleFileUpload() {
        if (isset($_FILES['file'])) {
            $binary = $this->rostros->obtener_binario($_FILES['file']);
            return "Binario: " . $binary;
        }
        return "No file uploaded";
    }

    // Handle comparison of two files
    public function handleFaceComparison() {
        if (isset($_FILES['file1'], $_FILES['file2'])) {
            $result = $this->rostros->comparar_rostros($_FILES['file1'], $_FILES['file2']);
            return "Resultado: " . $result;
        }
        return "Please upload two files for comparison.";
    }

    // Handle binary comparison
    public function handleBinaryComparison() {
        if (isset($_POST['binary'], $_FILES['file'])) {
            $result = $this->rostros->comparacion_binaria($_POST['binary'], $_FILES['file']);
            return "Resultado: " . $result;
        }
        return "Missing binary data or file for comparison.";
    }

    // Main handler method to route requests
    public function handleRequest() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (isset($_FILES['file']) && !isset($_FILES['file1'], $_FILES['file2'])) {
                return $this->handleFileUpload();
            } elseif (isset($_FILES['file1'], $_FILES['file2'])) {
                return $this->handleFaceComparison();
            } elseif (isset($_POST['binary'])) {
                return $this->handleBinaryComparison();
            }
        }
        return "Invalid request or missing parameters.";
    }
}
