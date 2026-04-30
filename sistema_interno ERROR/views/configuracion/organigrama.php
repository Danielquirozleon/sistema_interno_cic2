<?php
session_start();
require_once __DIR__ . '/../../config/database.php';

if (!isset($_SESSION['user_id']) || $_SESSION['rol'] != 1) {
    header("Location: ../auth/login.php");
    exit();
}

$db = (new Database())->getConnection();

// Consultas para las tablas
$deptos   = $db->query("SELECT * FROM departamentos ORDER BY id_departamento ASC")->fetchAll(PDO::FETCH_ASSOC);
$puestos  = $db->query("SELECT p.*, d.nombre_depto FROM puestos p LEFT JOIN departamentos d ON p.id_departamento = d.id_departamento ORDER BY p.id_puesto ASC")->fetchAll(PDO::FETCH_ASSOC);
$usuarios = $db->query("SELECT u.*, d.nombre_depto, p.nombre_puesto 
                        FROM usuarios u 
                        LEFT JOIN departamentos d ON u.id_departamento = d.id_departamento 
                        LEFT JOIN puestos p ON u.id_puesto = p.id_puesto")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Consola Organizacional</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; background: #f4f7f6; margin: 0; padding: 20px; color: #333; }
        .container { max-width: 1400px; margin: auto; }
        
        /* Cabecera y Botones */
        .header-flex { display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px; background: white; padding: 20px; border-radius: 10px; box-shadow: 0 2px 5px rgba(0,0,0,0.05); }
        .btn-main { background: #3498db; color: white; padding: 12px 20px; text-decoration: none; border-radius: 5px; font-weight: bold; transition: 0.3s; }
        .btn-main:hover { background: #2980b9; }
        .btn-user { background: #27ae60; }
        .btn-user:hover { background: #219150; }

        /* Grid de Configuración */
        .config-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 30px; }
        .card { background: white; padding: 20px; border-radius: 10px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); }
        h2 { margin-top: 0; font-size: 1.1rem; color: #2c3e50; border-left: 4px solid #3498db; padding-left: 10px; margin-bottom: 20px; }

        /* Tablas */
        .table-wrapper { max-height: 300px; overflow-y: auto; margin-top: 15px; border: 1px solid #eee; }
        table { width: 100%; border-collapse: collapse; font-size: 0.85rem; }
        table th { background: #f8f9fa; position: sticky; top: 0; padding: 10px; text-align: left; border-bottom: 2px solid #dee2e6; }
        table td { padding: 10px; border-bottom: 1px solid #f1f1f1; }
        
        /* Buscador y Formatos */
        .search-bar { width: 100%; padding: 12px; margin-bottom: 15px; border: 1px solid #ddd; border-radius: 8px; box-sizing: border-box; }
        .status-pill { padding: 4px 8px; border-radius: 20px; font-weight: bold; font-size: 0.75rem; border: none; }
        .active { background: #e3f9e5; color: #1f7a33; }
        .inactive { background: #ffebeb; color: #a94442; }
    </style>
</head>
<body>

<div class="container">
    
    <div class="header-flex">
        <div>
            <h1 style="margin:0; font-size: 1.5rem;">Estructura y Personal</h1>
            <a href="../dashboard.php" style="color: #3498db; text-decoration: none; font-size: 0.9rem;">⬅ Volver al Dashboard</a>
        </div>
        <div>
            <a href="../usuarios/crear.php" class="btn-main btn-user">+ Registrar Nuevo Usuario</a>
        </div>
    </div>

    <div class="config-grid">
        <div class="card">
            <h2>🏢 Departamentos Existentes</h2>
            <form action="../../controllers/EstructuraController.php?action=gestionar" method="POST" style="display: flex; gap: 5px;">
                <input type="hidden" name="tipo_accion" value="crear_depto">
                <input type="text" name="nombre_depto" placeholder="Nuevo Depto" required style="flex:2; padding:8px; border-radius:5px; border:1px solid #ccc;">
                <button type="submit" class="btn-main" style="flex:1; padding:8px;">Añadir</button>
            </form>
            <div class="table-wrapper">
                <table>
                    <thead><tr><th>ID</th><th>Nombre</th><th>Acción</th></tr></thead>
                    <tbody>
                        <?php foreach($deptos as $d): ?>
                        <tr>
                            <td>#<?php echo $d['id_departamento']; ?></td>
                            <td><strong><?php echo htmlspecialchars($d['nombre_depto']); ?></strong></td>
                            <td><a href="../../controllers/EstructuraController.php?action=eliminar_depto&id=<?php echo $d['id_departamento']; ?>" style="text-decoration:none;">❌</a></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card">
            <h2>💼 Puestos por Departamento</h2>
            <form action="../../controllers/EstructuraController.php?action=gestionar" method="POST" style="display: flex; flex-direction: column; gap: 5px;">
                <input type="hidden" name="tipo_accion" value="crear_puesto">
                <div style="display: flex; gap: 5px;">
                    <input type="text" name="nombre_puesto" placeholder="Nombre Puesto" required style="flex:2; padding:8px; border-radius:5px; border:1px solid #ccc;">
                    <select name="id_departamento" required style="flex:1; padding:8px; border-radius:5px; border:1px solid #ccc;">
                        <?php foreach($deptos as $d): ?>
                            <option value="<?php echo $d['id_departamento']; ?>"><?php echo $d['nombre_depto']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <button type="submit" class="btn-main" style="padding:8px;">Añadir Puesto</button>
            </form>
            <div class="table-wrapper">
                <table>
                    <thead><tr><th>Puesto</th><th>Área</th></tr></thead>
                    <tbody>
                        <?php foreach($puestos as $p): ?>
                        <tr>
                            <td><strong><?php echo htmlspecialchars($p['nombre_puesto']); ?></strong></td>
                            <td><span style="background:#eee; padding:2px 6px; border-radius:4px; font-size:0.7rem;"><?php echo htmlspecialchars($p['nombre_depto']); ?></span></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="card">
        <h2>👥 Control de Personal y Jerarquías</h2>
        <input type="text" id="userInput" class="search-bar" placeholder="🔍 Buscar empleado por nombre, puesto o departamento...">
        
        <div style="overflow-x: auto;">
            <table>
                <thead>
                    <tr>
                        <th>Empleado</th>
                        <th>Departamento</th>
                        <th>Puesto</th>
                        <th>Jefe Directo</th>
                        <th>Estado</th>
                        <th>Última Conexión</th>
                        <th>Acción</th>
                    </tr>
                </thead>
                <tbody id="userTable">
                    <?php foreach($usuarios as $u): ?>
                    <tr class="user-row">
                        <form action="../../controllers/EstructuraController.php?action=actualizar_usuario" method="POST">
                            <input type="hidden" name="id_usuario" value="<?php echo $u['id_usuario']; ?>">
                            
                            <td><strong><?php echo htmlspecialchars($u['nombre']); ?></strong></td>
                            
                            <td>
                                <select name="id_departamento" style="width:100px;">
                                    <?php foreach($deptos as $d): ?>
                                        <option value="<?php echo $d['id_departamento']; ?>" <?php echo ($d['id_departamento'] == $u['id_departamento']) ? 'selected' : ''; ?>><?php echo $d['nombre_depto']; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </td>

                            <td>
                                <select name="id_puesto" style="width:120px;">
                                    <?php foreach($puestos as $p): ?>
                                        <option value="<?php echo $p['id_puesto']; ?>" <?php echo ($p['id_puesto'] == $u['id_puesto']) ? 'selected' : ''; ?>><?php echo $p['nombre_puesto']; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </td>

                            <td>
                                <select name="id_jefe" style="width:120px;">
                                    <option value="">-- Sin Jefe --</option>
                                    <?php foreach($usuarios as $jefe): ?>
                                        <?php if($jefe['id_usuario'] != $u['id_usuario']): ?>
                                            <option value="<?php echo $jefe['id_usuario']; ?>" <?php echo ($jefe['id_usuario'] == $u['id_jefe']) ? 'selected' : ''; ?>><?php echo $jefe['nombre']; ?></option>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </select>
                            </td>

                            <td>
                                <select name="estado" class="status-pill <?php echo $u['estado'] ? 'active' : 'inactive'; ?>">
                                    <option value="1" <?php echo $u['estado'] == 1 ? 'selected' : ''; ?>>Activo</option>
                                    <option value="0" <?php echo $u['estado'] == 0 ? 'selected' : ''; ?>>Inactivo</option>
                                </select>
                            </td>

                            <td><small><?php echo $u['ultima_conexion'] ? date('d/m/y H:i', strtotime($u['ultima_conexion'])) : '---'; ?></small></td>

                            <td><button type="submit" class="btn-main" style="padding:5px 10px; font-size:0.7rem;">Guardar</button></td>
                        </form>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    // Buscador instantáneo
    document.getElementById('userInput').addEventListener('keyup', function() {
        let filter = this.value.toLowerCase();
        let rows = document.querySelectorAll('.user-row');
        rows.forEach(row => {
            row.style.display = row.innerText.toLowerCase().includes(filter) ? '' : 'none';
        });
    });
</script>

</body>
</html>