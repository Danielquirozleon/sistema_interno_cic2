<?php
require_once "../includes/header.php";
require_once "../../config/database.php";

// Verificación de seguridad: solo admin
if ($_SESSION['id_rol'] != 1) {
    header("Location: ../dashboard");
    exit();
}

$db = (new Database())->getConnection();

// Consultamos los procesos definidos
$query = "SELECT p.*, (SELECT COUNT(*) FROM pasos_definicion WHERE id_proceso = p.id_proceso) as total_pasos 
          FROM procesos p ORDER BY p.id_proceso DESC";
$procesos = $db->query($query)->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container-fluid animate-fade-in">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-0">Administración de Procesos</h2>
            <p class="text-muted">Define y gestiona los flujos de trabajo del sistema.</p>
        </div>
        <a href="views/procesos/crear_proceso" class="btn btn-primary shadow-sm">
            <i class="fas fa-plus me-2"></i>Nuevo Proceso
        </a>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">ID</th>
                            <th>Nombre del Proceso</th>
                            <th>Descripción</th>
                            <th class="text-center">Pasos</th>
                            <th class="text-center">Estado</th>
                            <th class="text-end pe-4">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($procesos as $p): ?>
                        <tr>
                            <td class="ps-4 text-muted">#<?php echo $p['id_proceso']; ?></td>
                            <td>
                                <span class="fw-bold text-primary"><?php echo htmlspecialchars($p['nombre_proceso']); ?></span>
                            </td>
                            <td class="text-muted small"><?php echo htmlspecialchars($p['descripcion']); ?></td>
                            <td class="text-center">
                                <span class="badge bg-info text-dark rounded-pill">
                                    <?php echo $p['total_pasos']; ?> pasos
                                </span>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-success">Activo</span>
                            </td>
                            <td class="text-end pe-4">
                                <div class="btn-group">
                                    <a href="views/procesos/configurar_pasos?id=<?php echo $p['id_proceso']; ?>" 
                                       class="btn btn-sm btn-outline-primary" title="Configurar Pasos">
                                        <i class="fas fa-project-diagram"></i>
                                    </a>
                                    <a href="views/procesos/editar_proceso?id=<?php echo $p['id_proceso']; ?>" 
                                       class="btn btn-sm btn-outline-warning" title="Editar Nombre">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button onclick="notify('Funcionalidad para deshabilitar en proceso', 'info')" 
                                            class="btn btn-sm btn-outline-danger">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        
                        <?php if(empty($procesos)): ?>
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                <i class="fas fa-folder-open fa-3x mb-3 d-block opacity-25"></i>
                                No hay procesos creados todavía.
                            </td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php require_once "../includes/footer.php"; ?>