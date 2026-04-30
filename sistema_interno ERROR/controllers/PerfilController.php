<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
require_once "../config/database.php";

$action = $_GET['action'] ?? '';
$db = (new Database())->getConnection();

if ($action === 'actualizar') {
    $id_usuario = $_SESSION['user_id'];
    $nombre = $_POST['nombre'];
    $new_pass = $_POST['new_password'];
    $confirm_pass = $_POST['confirm_password'];

    try {
        // 1. Actualización de nombre (siempre se actualiza)
        $sql = "UPDATE usuarios SET nombre = ? WHERE id_usuario = ?";
        $stmt = $db->prepare($sql);
        $stmt->execute([$nombre, $id_usuario]);
        $_SESSION['nombre'] = $nombre; // Actualizar sesión

        // 2. Si intentó cambiar contraseña
        if (!empty($new_pass)) {
            if ($new_pass !== $confirm_pass) {
                header("Location: ../views/usuarios/perfil.php?error=match");
                exit();
            }
            // Aquí podrías usar password_hash() para mayor seguridad
            $sql = "UPDATE usuarios SET password = ? WHERE id_usuario = ?";
            $stmt = $db->prepare($sql);
            $stmt->execute([$new_pass, $id_usuario]);
        }

        header("Location: ../views/usuarios/perfil.php?status=updated");
    } catch (PDOException $e) {
        header("Location: ../views/usuarios/perfil.php?error=db");
    }
}