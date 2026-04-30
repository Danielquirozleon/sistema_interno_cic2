<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
require_once "../config/database.php";

if ($_SESSION['id_rol'] != 1) { exit("Acceso denegado"); }

$action = $_GET['action'] ?? '';
$db = (new Database())->getConnection();

if ($action === 'delete_user') {
    $id = $_GET['id'];
    // Evitar que el admin se borre a sí mismo
    if ($id == $_SESSION['user_id']) {
        header("Location: ../views/auth/admin/usuarios_lista?error=self");
        exit();
    }
    
    $stmt = $db->prepare("DELETE FROM usuarios WHERE id_usuario = ?");
    if ($stmt->execute([$id])) {
        header("Location: ../views/auth/admin/usuarios_lista?status=deleted");
    }
}

if ($action === 'toggle_proceso') {
    // Lógica para pausar o activar un flujo de trabajo completo
    $id = $_GET['id'];
    header("Location: ../views/procesos/admin_procesos?status=updated");
}