<?php

require_once 'database/connect.php';
$vistas = new vistas();
$personal = $vistas->get_personal();
print_r($personal);

?>