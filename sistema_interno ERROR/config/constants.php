<?php
// Configuración General del Sistema
define('APP_NAME', 'Sistema de Gestión Interna');
define('APP_VERSION', '2.0.0');
define('APP_URL', 'http://localhost/sistema_interno/');

// Rutas de Directorios
define('DIR_INCLUDES', __DIR__ . '/../views/includes/');
define('DIR_ASSETS', APP_URL . 'assets/');

// Configuración de visualización
define('THEME_DEFAULT', 'light');
define('MAX_FILE_SIZE', 5242880); // 5MB en bytes

// Mensajes Globales
define('MSG_ERROR_DB', 'Error de conexión con la base de datos.');
define('MSG_UNAUTHORIZED', 'No tienes permisos para acceder a esta sección.');
?>