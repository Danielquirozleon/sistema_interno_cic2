<?php
class Model {
    protected $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
    }

    // Método genérico para limpiar datos
    protected function sanitize($data) {
        return htmlspecialchars(strip_tags($data));
    }
}