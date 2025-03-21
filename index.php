<?php

require_once 'database/query.php';

/*
$empleados = new Empleados();
echo "<h1>Trabajadores</h1>";
echo "<table border='1' style='width:100%; border-collapse: collapse; text-align: center;'>";
echo "<tr><th>ID</th><th>Nombre</th><th>Imagen</th></tr>";
foreach ($empleados->get_all() as $row) {
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

$asistencia = new RegistroAsistencia();
echo "<h1>Lista de Asistencia</h1>";
echo "<table border='1' style='width:100%; border-collapse: collapse; text-align: center;'>";
echo "<tr><th>ID</th><th>Nombre</th><th>Apellidos</th><th>email</th><th>tel</th><th>fecha_hora</th></tr>";
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
*/


require_once 'modules/face_compare.php';
if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['file'])){
    $rostros = new Rostros();
    $obtener_binario = $rostros->obtener_binario($_FILES['file']);
    echo "Binario: ".$obtener_binario;
}

?>
<form action="http://127.0.0.1:82/" method="POST" enctype="multipart/form-data">
        <label for="file">Selecciona una foto:</label>
        <input type="file" id="file" name="file" accept="image/png" required>
        <br><br>
        <button type="submit">Subir</button>
</form>