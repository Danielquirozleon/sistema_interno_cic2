<?php
session_start();
// Seguridad: Solo admin (Asumiendo Rol 1)
if (!isset($_SESSION['rol']) || $_SESSION['rol'] != 1) { 
    header("Location: ../dashboard.php"); 
    exit(); 
}

require_once __DIR__ . '/../../config/database.php';
$db = (new Database())->getConnection();

// Obtener Departamentos y Roles para los Selects
$deptos = $db->query("SELECT * FROM departamentos")->fetchAll(PDO::FETCH_ASSOC);
$roles = $db->query("SELECT * FROM roles")->fetchAll(PDO::FETCH_ASSOC);

// Obtener lista de usuarios actuales
$usuarios = $db->query("SELECT u.*, d.nombre_depto, r.nombre_rol 
                        FROM usuarios u 
                        LEFT JOIN departamentos d ON u.id_departamento = d.id_departamento 
                        LEFT JOIN roles r ON u.id_rol = r.id_rol")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Usuarios</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; background: #f4f7f6; padding: 20px; }
        .container { max-width: 1000px; margin: auto; background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .form-group { margin-bottom: 15px; display: flex; gap: 10px; flex-wrap: wrap; }
        input, select, button { padding: 10px; border: 1px solid #ccc; border-radius: 4px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 12px; border-bottom: 1px solid #eee; text-align: left; }
        th { background-color: #007bff; color: white; }
        .btn-save { background: #28a745; color: white; border: none; cursor: pointer; }
    </style>
</head>
<body>

<div class="container">
    <h2>👥 Control de Usuarios</h2>
    
    <fieldset>
        <legend>Registrar Nuevo Usuario</legend>
        <form action="../../controllers/AdminController.php?action=crear_usuario" method="POST" class="form-group">
            <input type="text" name="nombre" placeholder="Nombre Completo" required>
            <input type="text" name="usuario" placeholder="Nombre de Usuario" required>
            <input type="password" name="password" placeholder="Contraseña" required>
            
            <select name="id_departamento" required>
                <option value="">Departamento...</option>
                <?php foreach($deptos as $d): ?>
                    <option value="<?= $d['id_departamento'] ?>"><?= $d['nombre_depto'] ?></option>
                <?php endforeach; ?>
            </select>

            <select name="id_rol" required>
                <option value="">Rol...</option>
                <?php foreach($roles as $r): ?>
                    <option value="<?= $r['id_rol'] ?>"><?= $r['nombre_rol'] ?></option>
                <?php endforeach; ?>
            </select>

            <button type="submit" class="btn-save">Guardar Usuario</button>
        </form>
    </fieldset>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Usuario</th>
                <th>Departamento</th>
                <th>Rol</th>
                <th>Estado</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($usuarios as $user): ?>
            <tr>
                <td><?= $user['id_usuario'] ?></td>
                <td><?= htmlspecialchars($user['nombre']) ?></td>
                <td><?= htmlspecialchars($user['usuario']) ?></td>
                <td><?= htmlspecialchars($user['nombre_depto']) ?></td>
                <td><?= htmlspecialchars($user['nombre_rol']) ?></td>
                <td>🟢 Activo</td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <br>
    <a href="../dashboard.php">⬅ Volver al Dashboard</a>
</div>

</body>
</html>