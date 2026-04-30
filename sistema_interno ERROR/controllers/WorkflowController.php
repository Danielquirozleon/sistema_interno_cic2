<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
require_once "../config/database.php";

$action = $_GET['action'] ?? '';

if ($action === 'avanzar') {
    $id_instancia = $_POST['id_instancia'];
    $comentario   = $_POST['comentario'];
    $id_usuario   = $_SESSION['user_id'];

    try {
        $db = (new Database())->getConnection();
        $db->beginTransaction();

        // 1. Obtener el paso actual y el proceso de esta instancia
        $stmt = $db->prepare("SELECT i.id_proceso, i.paso_actual_id, d.orden_paso 
                              FROM procesos_instancias i 
                              JOIN pasos_definicion d ON i.paso_actual_id = d.id_paso 
                              WHERE i.id_instancia = ?");
        $stmt->execute([$id_instancia]);
        $actual = $stmt->fetch(PDO::FETCH_ASSOC);

        // 2. Buscar si existe un siguiente paso (orden + 1)
        $next_orden = $actual['orden_paso'] + 1;
        $stmt = $db->prepare("SELECT id_paso FROM pasos_definicion 
                              WHERE id_proceso = ? AND orden_paso = ?");
        $stmt->execute([$actual['id_proceso'], $next_orden]);
        $siguiente_paso = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($siguiente_paso) {
            // Existe un siguiente paso: Actualizamos la instancia
            $sql = "UPDATE procesos_instancias SET 
                    paso_actual_id = ?, 
                    id_usuario_asignado_actual = NULL, 
                    ultima_actualizacion = NOW() 
                    WHERE id_instancia = ?";
            $stmt = $db->prepare($sql);
            $stmt->execute([$siguiente_paso['id_paso'], $id_instancia]);
            $msg = "Avanzado al paso " . $next_orden;
        } else {
            // No hay más pasos: Finalizar el trámite
            $sql = "UPDATE procesos_instancias SET 
                    estado = 'finalizado', 
                    ultima_actualizacion = NOW() 
                    WHERE id_instancia = ?";
            $stmt = $db->prepare($sql);
            $stmt->execute([$id_instancia]);
            $msg = "Trámite finalizado con éxito";
        }

        // 3. (Opcional) Guardar en un log de movimientos/comentarios si tienes la tabla
        // $db->prepare("INSERT INTO historial (id_instancia, id_usuario, comentario) VALUES (?,?,?)")...

        $db->commit();
        header("Location: ../views/procesos/bandeja?status=success&msg=" . urlencode($msg));

    } catch (Exception $e) {
        $db->rollBack();
        error_log($e->getMessage());
        header("Location: ../views/procesos/bandeja?error=wf");
    }
}