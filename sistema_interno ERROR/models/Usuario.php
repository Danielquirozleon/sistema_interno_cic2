<?php
class Usuario {
    private $conn;
    private $table_name = "usuarios";

    public $id_usuario;
    public $nombre;
    public $email;
    public $password;
    public $id_rol;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Obtener todos los usuarios con su rol
    public function readAll() {
        $query = "SELECT u.*, r.nombre_rol 
                  FROM " . $this->table_name . " u 
                  JOIN roles r ON u.id_rol = r.id_rol 
                  ORDER BY u.nombre ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Buscar un usuario por email (para login)
    public function findByEmail($email) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE email = ? LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}