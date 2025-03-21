<?php
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

<?php
// Views
echo "<h1>Sistema de Gestión</h1>";
require_once "views/empleados.php";

require_once "database/query.php";
$empleado = new Empleados();
echo renderizarTablaEmpleados($empleado);

require_once "views/asistencia.php";
$asistencia = new RegistroAsistencia();
echo mostrar_asistencia($asistencia);

?>