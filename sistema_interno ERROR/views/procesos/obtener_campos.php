<?php
// Este archivo devuelve un fragmento HTML o JSON para inyectar en el formulario
require_once "../../config/database.php";
$id_proceso = $_GET['id_proceso'] ?? 0;

// Por ahora, devolvemos un mensaje simple, pero aquí iría tu lógica de campos personalizados
echo "";
?>
<div class="alert alert-secondary small py-2">
    <i class="fas fa-tags me-2"></i> No se requieren campos adicionales para este proceso.
</div>