<?php
session_start();
require_once __DIR__ . '/../../config/database.php';

// Seguridad: Solo admin
if (!isset($_SESSION['user_id']) || $_SESSION['rol'] != 1) {
    header("Location: ../auth/login.php");
    exit();
}

$db = (new Database())->getConnection();

// Traemos los Departamentos
$deptos = $db->query("SELECT * FROM departamentos")->fetchAll(PDO::FETCH_ASSOC);

// Traemos los Puestos
$puestos = $db->query("SELECT * FROM puestos")->fetchAll(PDO::FETCH_ASSOC);

// Traemos los Roles (si tienes una tabla, si no, los dejamos fijos)
$roles = [
    ['id' => 1, 'nombre' => 'Administrador'],
    ['id' => 2, 'nombre' => 'Empleado']
];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registrar Usuario</title>
    <style>
        body { font-family: sans-serif; background: #f4f7f6; padding: 40px; }
        .form-container { max-width: 500px; margin: auto; background: white; padding: 30px; border-radius: 10px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); }
        .form-group { margin-bottom: 15px; }
        label { display: block; font-weight: bold; margin-bottom: 5px; }
        input, select { width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 5px; box-sizing: border-box; }
        .btn-save { background: #27ae60; color: white; border: none; padding: 12px; width: 100%; cursor: pointer; font-size: 1rem; border-radius: 5px; }
        .btn-save:hover { background: #219150; }
    </style>
</head>
<body>

<div class="form-container">
    <h2>Registrar Nuevo Usuario</h2>
    <form action="../../controllers/UsuarioController.php?action=registrar" method="POST">
        
        <div class="form-group">
            <label>Nombre Completo</label>
            <input type="text" name="nombre" placeholder="Ej. Juan Pérez" required>
        </div>

        <div class="form-group">
            <label>Usuario (Login)</label>
            <input type="text" name="usuario" placeholder="Ej. jperez" required>
        </div>

        <div class="form-group">
            <label>Contraseña</label>
            <input type="password" name="password" required>
        </div>

        <div class="form-group">
            <label>Departamento</label>
            <select name="id_departamento" required>
                <option value="">Seleccione Departamento...</option>
                <?php foreach($deptos as $d): ?>
                    <option value="<?php echo $d['id_departamento']; ?>">
                        <?php echo htmlspecialchars($d['nombre_depto']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label>Puesto de Trabajo</label>
            <select name="id_puesto" required>
                <option value="">Seleccione Puesto...</option>
                <?php foreach($puestos as $p): ?>
                    <option value="<?php echo $p['id_puesto']; ?>">
                        <?php 
                            // Aquí usamos el nombre de columna que tengas en la tabla puestos (ej. nombre_puesto)
                            echo htmlspecialchars($p['nombre_puesto'] ?? $p['nombre']); 
                        ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label>Rol de Sistema</label>
            <select name="id_rol" required>
                <?php foreach($roles as $r): ?>
                    <option value="<?php echo $r['id']; ?>"><?php echo $r['nombre']; ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <button type="submit" class="btn-save">Crear Usuario</button>
    </form>
    <br>
    <a href="../dashboard.php" style="text-decoration: none; color: #7f8c8d;">⬅ Cancelar y volver</a>
</div>

</body>
</html>