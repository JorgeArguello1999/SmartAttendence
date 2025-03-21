<?php
require_once 'modules/face_compare.php';
if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['file'])){
    $rostros = new Rostros();
    $obtener_binario = $rostros->obtener_binario($_FILES['file']);
    echo "Binario: ".$obtener_binario;
}

if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["file1"], $_FILES['file2'])){
    $rostros = new Rostros();
    $comparar_rostros = $rostros->comparar_rostros($_FILES['file1'], $_FILES['file2']);
    echo "Resultado: ".$comparar_rostros;
}

if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES['file'], $_POST['binary'])){
    $rostros = new Rostros();
    $comparacion_binaria = $rostros->comparacion_binaria($_POST['binary'], $_FILES['file']);
    echo "Resultado: ".$comparacion_binaria;
}
?>
<form action="http://127.0.0.1:82/" method="POST" enctype="multipart/form-data">
        <label for="file">Selecciona una foto:</label>
        <input type="file" id="file" name="file" accept="image/png" required>
        <br><br>
        <button type="submit">Subir</button>
</form>

<form action="http://127.0.0.1:82/" method="POST" enctype="multipart/form-data">
    <label for="file1">Selecciona la primera imagen:</label>
    <input type="file" name="file1" id="file1" required><br><br>

    <label for="file2">Selecciona la segunda imagen:</label>
    <input type="file" name="file2" id="file2" required><br><br>

    <input type="submit" value="Comparar Rostros">
</form>

<form action="http://127.0.0.1:82/" method="POST" enctype="multipart/form-data">
    <!-- Campo para el archivo -->
    <label for="file">Selecciona una imagen:</label>
    <input type="file" name="file" id="file" required><br><br>
        
    <!-- Campo para la cadena binaria -->
    <label for="binary">Introduce la cadena binaria:</label><br>
    <textarea name="binary" id="binary" rows="4" cols="50" required></textarea><br><br>
        
    <!-- Botón de envío -->
    <input type="submit" value="Enviar">
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