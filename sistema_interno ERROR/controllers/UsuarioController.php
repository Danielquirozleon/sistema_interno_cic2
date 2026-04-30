<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
require_once "../config/database.php";

// Solo permitir si es Admin
if ($_SESSION['id_rol'] != 1) { exit("No autorizado"); }

$action = $_GET['action'] ?? '';

if ($action === 'guardar') {
    $nombre   = $_POST['nombre'] ?? '';
    $email    = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $id_rol   = $_POST['id_rol'] ?? '';

    try {
        $db = (new Database())->getConnection();
        $sql = "INSERT INTO usuarios (nombre, email, password, id_rol) VALUES (?, ?, ?, ?)";
        $stmt = $db->prepare($sql);
        $stmt->execute([$nombre, $email, $password, $id_rol]);

        // Redirigir con éxito
        header("Location: ../views/auth/admin/usuarios_lista?status=created");
    } catch (PDOException $e) {
        error_log($e->getMessage());
        header("Location: ../views/auth/admin/crear_usuario?error=db");
    }
}