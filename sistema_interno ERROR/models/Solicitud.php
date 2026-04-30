<?php
class Solicitud {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getDetalle($id_instancia) {
        $query = "SELECT i.*, p.nombre_proceso, d.nombre_paso 
                  FROM procesos_instancias i
                  JOIN procesos p ON i.id_proceso = p.id_proceso
                  JOIN pasos_definicion d ON i.paso_actual_id = d.id_paso
                  WHERE i.id_instancia = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$id_instancia]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}