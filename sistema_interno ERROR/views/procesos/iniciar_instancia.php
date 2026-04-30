<?php
require_once "../includes/header.php";
require_once "../../config/database.php";

$id_proceso = $_GET['id_proceso'] ?? null;
$id_usuario = $_SESSION['user_id'];

if (!$id_proceso) {
    header("Location: lista_procesos.php");
    exit();
}

$db = (new Database())->getConnection();

// 1. Obtener el primer paso definido para este proceso
$stmt = $db->prepare("SELECT id_paso FROM pasos_definicion WHERE id_proceso = ? ORDER BY orden_paso ASC LIMIT 1");
$stmt->execute([$id_proceso]);
$primer_paso = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$primer_paso) {
    echo "<div class='alert alert-danger'>Error: Este proceso no tiene pasos configurados. Contacte al administrador.</div>";
    require_once "../includes/footer.php";
    exit();
}

$id_paso_inicial = $primer_paso['id_paso'];

try {
    // 2. Crear la instancia del proceso
    $sql = "INSERT INTO procesos_instancias (id_proceso, id_usuario_creador, paso_actual_id, estado, fecha_inicio, id_usuario_asignado_actual) 
            VALUES (?, ?, ?, 'en_progreso', NOW(), ?)";
    $stmt = $db->prepare($sql);
    $stmt->execute([$id_proceso, $id_usuario, $id_paso_inicial, $id_usuario]);
    
    $id_instancia = $db->lastInsertId();

    // 3. Redirigir directamente a la ejecución del primer paso
    header("Location: ejecutar_paso.php?id=" . $id_instancia);
    exit();

} catch (PDOException $e) {
    error_log($e->getMessage());
    header("Location: lista_procesos.php?error=no_se_pudo_iniciar");
}
?>