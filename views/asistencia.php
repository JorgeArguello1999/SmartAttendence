<?php
function mostrar_asistencia($asistencia) {
    echo "<h1>Lista de Asistencia</h1>";
    echo "<table border='1' style='width:100%; border-collapse: collapse; text-align: center;'>
            <tr><th>ID</th><th>Nombre</th><th>Apellidos</th><th>Email</th><th>Teléfono</th><th>Fecha y Hora</th><th>Imagen</th></tr>";

    foreach ($asistencia->get_all() as $row) {
        echo "<tr>";
        foreach ($row as $key => $item) {
            if ($key == 'imagen_verificacion') {
                echo "<td><img src='data:image/jpeg;base64," . base64_encode($item) . "' style='width: 50px; height: 50px;'/></td>";
            } else {
                echo "<td>" . htmlspecialchars($item) . "</td>";
            }
        }
        echo "</tr>";
    }
    echo "</table>";
}

?>