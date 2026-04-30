<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }

// 1. Verificación de ruta de base de datos
$db_path = "../config/database.php";
if (!file_exists($db_path)) {
    die("Error crítico: No se encuentra el archivo en $db_path. Revisa la estructura de carpetas.");
}
require_once $db_path;

$action = $_GET['action'] ?? '';

if ($action === 'login') {
    $usuario_ingresado = trim($_POST['usuario'] ?? '');
    $pass_ingresada    = trim($_POST['password'] ?? '');

    try {
        $database = new Database();
        $db = $database->getConnection();

        // 2. Consulta ultra-flexible (busca en usuario o email)
        $stmt = $db->prepare("SELECT * FROM usuarios WHERE usuario = ? OR email = ? LIMIT 1");
        $stmt->execute([$usuario_ingresado, $usuario_ingresado]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            // 3. Verificación de contraseña (Hash o Texto Plano)
            $hash_valido = password_verify($pass_ingresada, $user['password']);
            $plano_valido = ($pass_ingresada === $user['password']);

            if ($hash_valido || $plano_valido) {
                $_SESSION['user_id'] = $user['id_usuario'];
                $_SESSION['nombre']  = $user['nombre'];
                $_SESSION['id_rol']  = $user['id_rol'];
                
                header("Location: ../views/dashboard.php");
                exit();
            } else {
                // Password incorrecto
                header("Location: ../views/auth/login.php?error=1");
                exit();
            }
        } else {
            // DIAGNÓSTICO: Si llegamos aquí, la base de datos no encontró el string
            // Vamos a mostrar qué está buscando para detectar caracteres invisibles
            die("Error: El usuario '$usuario_ingresado' no existe en la base de datos. Verifica que no haya espacios extra en el campo 'usuario' de tu tabla SQL.");
        }
    } catch (PDOException $e) {
        die("Error de base de datos: " . $e->getMessage());
    }
}