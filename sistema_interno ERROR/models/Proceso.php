<?php
class Proceso {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    // Obtener todos los procesos con conteo de pasos
    public function getAll() {
        $sql = "SELECT p.*, (SELECT COUNT(*) FROM pasos_definicion WHERE id_proceso = p.id_proceso) as total_pasos 
                FROM procesos p ORDER BY p.nombre_proceso ASC";
        return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtener los pasos de un proceso específico
    public function getPasos($id_proceso) {
        $sql = "SELECT d.*, r.nombre_rol 
                FROM pasos_definicion d 
                JOIN roles r ON d.id_perfil_responsable = r.id_rol 
                WHERE d.id_proceso = ? ORDER BY d.orden_paso ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id_proceso]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}