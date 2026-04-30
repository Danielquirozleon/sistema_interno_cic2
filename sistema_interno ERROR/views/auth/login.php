<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
if (isset($_SESSION['user_id'])) { header("Location: ../dashboard.php"); exit(); }
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Login - Sistema Interno</title>
    <style>
        body { font-family: sans-serif; background-color: #f4f7f6; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .login-container { background: white; padding: 2rem; border-radius: 8px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); width: 100%; max-width: 400px; }
        .error-alert { background: #f8d7da; color: #721c24; padding: 15px; border-radius: 4px; margin-bottom: 1rem; text-align: center; }
        input { width: 100%; padding: 12px; margin: 10px 0; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box; }
        button { width: 100%; padding: 12px; background: #007bff; color: white; border: none; border-radius: 4px; cursor: pointer; font-weight: bold; }
    </style>
</head>
<body>
    <div class="login-container">
        <h2 style="text-align:center;">Iniciar Sesión</h2>
        
        <?php if (isset($_GET['error'])): ?>
            <div class="error-alert">
                <?php 
                    if($_GET['error'] == '1') echo 'Contraseña incorrecta';
                    elseif($_GET['error'] == 'usuario_no_existe') echo 'El usuario no existe';
                    else echo 'Error en el sistema';
                ?>
            </div>
        <?php endif; ?>

        <form action="../../controllers/AuthController.php?action=login" method="POST">
            <label>Usuario</label>
            <input type="text" name="usuario" placeholder="Usuario" required autofocus>
            <label>Contraseña</label>
            <input type="password" name="password" placeholder="Contraseña" required>
            <button type="submit">Ingresar</button>
        </form>
    </div>
</body>
</html>