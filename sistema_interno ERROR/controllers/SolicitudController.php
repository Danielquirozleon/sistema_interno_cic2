<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
require_once "../config/database.php";

$action = $_GET['action'] ?? '';
$db = (new Database())->getConnection();

if ($action === 'registrar') {
    $id_proceso  = $_POST['id_proceso'];
    $titulo      = $_POST['titulo'];
    $descripcion = $_POST['descripcion'];
    $prioridad   = $_POST['prioridad'];
    $id_usuario  = $_SESSION['user_id'];

    try {
        // Buscamos el primer paso del proceso para asignar la instancia
        $stmt = $db->prepare("SELECT id_paso FROM pasos_definicion WHERE id_proceso = ? ORDER BY orden_paso ASC LIMIT 1");
        $stmt->execute([$id_proceso]);
        $paso = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$paso) { throw new Exception("El proceso no tiene pasos definidos."); }

        $sql = "INSERT INTO procesos_instancias (id_proceso, id_usuario_creador, paso_actual_id, estado, fecha_inicio) 
                VALUES (?, ?, ?, 'en_progreso', NOW())";
        $stmt = $db->prepare($sql);
        $stmt->execute([$id_proceso, $id_usuario, $paso['id_paso']]);

        header("Location: ../views/procesos/bandeja?status=sent");
    } catch (Exception $e) {
        header("Location: ../views/solicitudes/crear?id_proceso=$id_proceso&error=" . urlencode($e->getMessage()));
    }
}