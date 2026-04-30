<?php
require_once "includes/header.php";
require_once "../config/database.php";

$id_instancia = $_GET['id'] ?? null;
if (!$id_instancia) { header("Location: dashboard"); exit(); }

$db = (new Database())->getConnection();

// Consulta completa del estado actual
$sql = "SELECT i.*, p.nombre_proceso, p.descripcion as desc_proceso, d.nombre_paso, u.nombre as creador
        FROM procesos_instancias i
        JOIN procesos p ON i.id_proceso = p.id_proceso
        JOIN pasos_definicion d ON i.paso_actual_id = d.id_paso
        JOIN usuarios u ON i.id_usuario_creador = u.id_usuario
        WHERE i.id_instancia = ?";
$stmt = $db->prepare($sql);
$stmt->execute([$id_instancia]);
$instancia = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<div class="container animate-fade-in">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <a href="javascript:history.back()" class="btn btn-link text-decoration-none text-muted p-0">
            <i class="fas fa-arrow-left me-2"></i> Volver
        </a>
        <button class="btn btn-outline-primary btn-sm" onclick="window.print()">
            <i class="fas fa-print me-2"></i> Imprimir Reporte
        </button>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center mb-3">
                        <div class="icon-box bg-primary-soft text-primary rounded-circle p-3 me-3" style="background: rgba(13,110,253,0.1);">
                            <i class="fas fa-file-invoice fa-2x"></i>
                        </div>
                        <div>
                            <h2 class="fw-bold mb-0">Trámite #<?php echo $id_instancia; ?></h2>
                            <span class="badge <?php echo $instancia['estado'] == 'finalizado' ? 'bg-success' : 'bg-warning text-dark'; ?> rounded-pill">
                                <?php echo strtoupper($instancia['estado']); ?>
                            </span>
                        </div>
                    </div>
                    
                    <hr>

                    <div class="row mt-4">
                        <div class="col-sm-6 mb-3">
                            <label class="text-muted small fw-bold text-uppercase">Proceso</label>
                            <p class="fs-5 fw-semibold"><?php echo htmlspecialchars($instancia['nombre_proceso']); ?></p>
                        </div>
                        <div class="col-sm-6 mb-3">
                            <label class="text-muted small fw-bold text-uppercase">Paso Actual</label>
                            <p class="fs-5 text-primary fw-bold"><?php echo htmlspecialchars($instancia['nombre_paso']); ?></p>
                        </div>
                        <div class="col-sm-6">
                            <label class="text-muted small fw-bold text-uppercase">Iniciado por</label>
                            <p><?php echo htmlspecialchars($instancia['creador']); ?></p>
                        </div>
                        <div class="col-sm-6">
                            <label class="text-muted small fw-bold text-uppercase">Fecha de Apertura</label>
                            <p><?php echo date('d/m/Y H:i', strtotime($instancia['fecha_inicio'])); ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow-sm border-0 bg-light">
                <div class="card-body">
                    <h5 class="fw-bold mb-3 small text-uppercase text-muted">Progreso Visual</h5>
                    <div class="progress mb-3" style="height: 10px;">
                        <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" 
                             style="width: <?php echo ($instancia['estado'] == 'finalizado') ? '100%' : '65%'; ?>;"></div>
                    </div>
                    <p class="small text-muted mb-0">
                        <?php echo ($instancia['estado'] == 'finalizado') 
                            ? 'Este trámite ha completado todos los pasos satisfactoriamente.' 
                            : 'El trámite se encuentra en revisión por el departamento correspondiente.'; ?>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once "includes/footer.php"; ?>