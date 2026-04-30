<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
require_once "../config/database.php";

if ($_SESSION['id_rol'] != 1) { exit("No autorizado"); }

$action = $_GET['action'] ?? '';

if ($action === 'agregar_paso') {
    $id_proceso = $_POST['id_proceso'];
    $nombre     = $_POST['nombre_paso'];
    $orden      = $_POST['orden_paso'];
    $id_rol     = $_POST['id_rol'];

    try {
        $db = (new Database())->getConnection();
        $sql = "INSERT INTO pasos_definicion (id_proceso, nombre_paso, orden_paso, id_perfil_responsable) 
                VALUES (?, ?, ?, ?)";
        $stmt = $db->prepare($sql);
        $stmt->execute([$id_proceso, $nombre, $orden, $id_rol]);

        header("Location: ../views/procesos/configurar_pasos?id=" . $id_proceso . "&status=success");
    } catch (PDOException $e) {
        error_log($e->getMessage());
        header("Location: ../views/procesos/configurar_pasos?id=" . $id_proceso . "&error=db");
    }
}