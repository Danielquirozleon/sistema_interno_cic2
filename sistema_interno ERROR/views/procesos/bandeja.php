<?php
require_once "../includes/header.php";
require_once "../../config/database.php";

$db = (new Database())->getConnection();
$id_usuario = $_SESSION['user_id'];
$id_rol     = $_SESSION['id_rol'];

// Consulta para traer trámites pendientes:
// 1. Asignados directamente al usuario.
// 2. O asignados al perfil/rol del usuario pero aún no tomados por nadie.
$sql = "SELECT i.*, p.nombre_proceso, d.nombre_paso, u.nombre as creador
        FROM procesos_instancias i
        JOIN procesos p ON i.id_proceso = p.id_proceso
        JOIN pasos_definicion d ON i.paso_actual_id = d.id_paso
        JOIN usuarios u ON i.id_usuario_creador = u.id_usuario
        WHERE i.estado != 'finalizado'
        AND (i.id_usuario_asignado_actual = :u OR (i.id_usuario_asignado_actual IS NULL AND d.id_perfil_responsable = :r))
        ORDER BY i.fecha_inicio DESC";

$stmt = $db->prepare($sql);
$stmt->execute(['u' => $id_usuario, 'r' => $id_rol]);
$pendientes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container-fluid animate-fade-in">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-0">Mi Bandeja de Tareas</h2>
            <p class="text-muted">Gestión de trámites y procesos bajo tu responsabilidad.</p>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-outline-secondary shadow-sm" onclick="location.reload()">
                <i class="fas fa-sync-alt"></i>
            </button>
            <a href="views/procesos/lista_procesos" class="btn btn-primary shadow-sm">
                <i class="fas fa-plus me-2"></i>Nuevo Trámite
            </a>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">Trámite</th>
                            <th>Proceso</th>
                            <th>Paso Actual</th>
                            <th>Iniciado por</th>
                            <th>Fecha</th>
                            <th class="text-end pe-4">Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($pendientes as $item): ?>
                        <tr>
                            <td class="ps-4">
                                <span class="fw-bold text-dark">#<?php echo $item['id_instancia']; ?></span>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="icon-sm bg-light text-primary rounded me-2 px-2 py-1">
                                        <i class="fas fa-file-alt"></i>
                                    </div>
                                    <span><?php echo htmlspecialchars($item['nombre_proceso']); ?></span>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-warning text-dark px-3 py-2">
                                    <i class="fas fa-arrow-right me-1 small"></i> <?php echo htmlspecialchars($item['nombre_paso']); ?>
                                </span>
                            </td>
                            <td class="small text-muted">
                                <?php echo htmlspecialchars($item['creador']); ?>
                            </td>
                            <td class="small">
                                <?php echo date('d/m/Y H:i', strtotime($item['fecha_inicio'])); ?>
                            </td>
                            <td class="text-end pe-4">
                                <a href="views/procesos/ejecutar_paso?id=<?php echo $item['id_instancia']; ?>" 
                                   class="btn btn-primary btn-sm rounded-pill px-4">
                                    Atender <i class="fas fa-chevron-right ms-1"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>

                        <?php if(empty($pendientes)): ?>
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <div class="opacity-25 mb-3">
                                    <i class="fas fa-check-circle fa-4x text-success"></i>
                                </div>
                                <h5 class="text-muted">¡Todo al día!</h5>
                                <p class="text-muted small">No tienes tareas pendientes por el momento.</p>
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