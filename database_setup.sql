-- Crear base de datos
CREATE DATABASE IF NOT EXISTS sistema_interno;
USE sistema_interno;

-- Crear tabla usuarios
CREATE TABLE IF NOT EXISTS usuarios (
    id_usuario INT PRIMARY KEY AUTO_INCREMENT,
    usuario VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100),
    password VARCHAR(255) NOT NULL,
    nombre VARCHAR(100),
    id_rol INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insertar usuario de prueba (contraseña: admin123)
-- El hash es: $2y$10$N9qo8uLOickgx2ZMRZoMyeIjZAgcg7b3XeKeUxWdeS86E36MM.fs.m
INSERT INTO usuarios (usuario, email, password, nombre, id_rol) 
VALUES ('admin', 'admin@sistema.com', '$2y$10$N9qo8uLOickgx2ZMRZoMyeIjZAgcg7b3XeKeUxWdeS86E36MM.fs.m', 'Administrador', 1)
ON DUPLICATE KEY UPDATE password=VALUES(password);

-- Verificar que se creó correctamente
SELECT * FROM usuarios;