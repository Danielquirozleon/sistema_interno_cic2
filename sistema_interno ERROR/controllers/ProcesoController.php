<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
require_once "../config/database.php";

if ($_SESSION['id_rol'] != 1) { exit("No autorizado"); }

$action = $_GET['action'] ?? '';

if ($action === 'guardar') {
    $nombre      = $_POST['nombre_proceso'] ?? '';
    $descripcion = $_POST['descripcion'] ?? '';

    try {
        $db = (new Database())->getConnection();
        $sql = "INSERT INTO procesos (nombre_proceso, descripcion) VALUES (?, ?)";
        $stmt = $db->prepare($sql);
        $stmt->execute([$nombre, $descripcion]);
        
        $lastId = $db->lastInsertId();

        // Redirigir a la configuración de pasos del proceso recién creado
        header("Location: ../views/procesos/configurar_pasos?id=" . $lastId . "&status=new");
    } catch (PDOException $e) {
        error_log($e->getMessage());
        header("Location: ../views/procesos/admin_procesos?error=db");
    }
}