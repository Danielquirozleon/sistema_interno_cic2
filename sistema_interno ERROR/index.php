<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (isset($_SESSION['user_id'])) {
    header("Location: views/dashboard");
} else {
    header("Location: views/auth/login");
}
exit();