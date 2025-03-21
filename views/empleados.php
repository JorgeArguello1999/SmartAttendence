<?php
function renderizarTablaEmpleados($empleados) {
    ?>
    <h1>Trabajadores</h1>
    <table border='1' style='width:100%; border-collapse: collapse; text-align: center;'>
        <tr><th>ID</th><th>Nombre</th><th>Imagen</th></tr>
        <?php foreach ($empleados->get_all() as $row): ?>
            <tr>
                <?php foreach ($row as $key => $item): ?>
                    <?php if ($key == 'imagen_rostro'): ?>
                        <td><img src='data:image/jpeg;base64,<?= base64_encode($item) ?>' style='width: 50px; height: 50px;'/></td>
                    <?php else: ?>
                        <td><?= htmlspecialchars($item) ?></td>
                    <?php endif; ?>
                <?php endforeach; ?>
            </tr>
        <?php endforeach; ?>
    </table>
    <?php
}
?>
