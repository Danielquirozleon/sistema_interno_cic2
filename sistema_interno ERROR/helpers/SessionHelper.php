<?php
class SessionHelper {
    
    public static function init() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public static function restrictToAdmin() {
        self::init();
        if (!isset($_SESSION['id_rol']) || $_SESSION['id_rol'] != 1) {
            header("Location: /sistema_interno/views/dashboard?error=unauthorized");
            exit();
        }
    }

    public static function isLoggedIn() {
        self::init();
        return isset($_SESSION['user_id']);
    }
}