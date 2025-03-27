<?php

require_once '../views/template.php';

require_once '../database/query.php';
$empleado = new Empleados();
$result = $empleado->insertarEmpleado("1234567890", "Juan", "Pérez", "juan.prez@ejemplo.com", "0987654321", "2025-03-26", 1, 2);

if ($result) {
    echo "Empleado insertado correctamente.";
} else {
    echo "Error al insertar el empleado.";
}


?>