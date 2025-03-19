<?php

class database {
    public $conn;
    public $ruta;

    public function __construct() {
        // Init the connection
        $this->conn = mysqli_connect(
            "127.0.0.1", "root", getenv("DB_PASSWORD"), "bd_asistencia2025", 3306
        );
        mysqli_set_charset($this->conn, 'utf8');

        if(!$this->conn){
            echo "Error". mysqli_connect_error();
        }
    }
}

class vistas {
    private $conn;
    
    public function __construct(){
        $database = new database();
        $this->conn = $database->conn;
    }

    public function get_personal(){
        $sql = "SELECT * FROM Empleados";
        $result = mysqli_query($this->conn, $sql);
        return $result;
    }
}

?>