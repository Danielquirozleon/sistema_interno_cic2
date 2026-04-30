<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
require_once "../config/database.php";

$action = $_GET['action'] ?? '';
$db = (new Database())->getConnection();

if ($action === 'exportar_csv') {
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=reporte_tramites_' . date('Ymd') . '.csv');

    $output = fopen('php://output', 'w');
    fputcsv($output, ['ID', 'Proceso', 'Estado', 'Creador', 'Fecha Inicio']);

    $query = "SELECT i.id_instancia, p.nombre_proceso, i.estado, u.nombre, i.fecha_inicio 
              FROM procesos_instancias i
              JOIN procesos p ON i.id_proceso = p.id_proceso
              JOIN usuarios u ON i.id_usuario_creador = u.id_usuario";
    
    $result = $db->query($query);
    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        fputcsv($output, $row);
    }
    fclose($output);
    exit();
}