<?php

require_once 'database/query.php';
$r_asistencia = new Registro_Asistencia();

echo "<h1>Trabajadores</h1>";
echo "<table border='1' style='width:100%; border-collapse: collapse; text-align: center;'>";
echo "<tr><th>ID</th><th>Nombre</th><th>Imagen</th></tr>";

foreach ($r_asistencia->get_all() as $row) {
    echo "<tr>";
    
    foreach ($row as $key => $item) {
        if ($key == 'imagen_rostro') {
            echo "<td><img src='data:image/jpeg;base64," . base64_encode($item) . "' style='width: 50px; height: 50px;'/></td>";
        } else {
            echo "<td>" . htmlspecialchars($item) . "</td>";
        }
    }
    
    echo "</tr>";
}
echo "</table>";



?>