<?php

// load querys
require_once 'database/query.php';
$r_asistencia = new Registro_Asistencia();

echo "<h1>Trabajadores</h1>";
foreach($r_asistencia->get_all() as $row){
    foreach($row as $item){
        if($item == $row['imagen_rostro']){
            echo "<img src='data:image/jpeg;base64,".base64_encode($item)."'/>";
        }else{
            echo $item."<br>";
        }
    }
}

?>