<?php
require_once "../includes/header.php";
require_once "../../config/database.php";

$id_instancia = $_GET['id'] ?? null;
$db = (new Database())->getConnection();

// Traer información de la instancia, el proceso y el paso actual
$sql = "SELECT i.*, p.nombre_proceso, d.nombre_paso, d.orden_paso
        FROM procesos_instancias i
        JOIN procesos p ON i.id_proceso = p.id_proceso
        JOIN pasos_definicion d ON i.paso_actual_id = d.id_paso
        WHERE i.id_instancia = ?";
$stmt = $db->prepare($sql);
$stmt->execute([$id_instancia]);
$instancia = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$instancia) {
    header("Location: bandeja.php");
    exit();
}
?>

<div class="container animate-fade-in">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card shadow-sm border-0 mb-4 bg-primary text-white">
                <div class="card-body p-4 d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-uppercase opacity-75 small fw-bold mb-1">Trámite #<?php echo $id_instancia; ?></h6>
                        <h3 class="fw-bold mb-0"><?php echo htmlspecialchars($instancia['nombre_proceso']); ?></h3>
                    </div>
                    <div class="text-end">
                        <span class="badge bg-white text-primary px-3 py-2 rounded-pill">
                            Paso <?php echo $instancia['orden_paso']; ?>: <?php echo htmlspecialchars($instancia['nombre_paso']); ?>
                        </span>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-8">
                    <div class="card shadow-sm border-0 p-4">
                        <h5 class="fw-bold mb-4">Completar Información</h5>
                        <form action="controllers/WorkflowController.php?action=avanzar" method="POST">
                            <input type="hidden" name="id_instancia" value="<?php echo $id_instancia; ?>">
                            
                            <div class="mb-4">
                                <label class="form-label fw-bold small text-muted">Comentarios u Observaciones</label>
                                <textarea name="comentario" class="form-control" rows="5" placeholder="Escribe aquí los detalles del paso actual..." required></textarea>
                            </div>

                            <div class="d-flex justify-content-between border-top pt-4">
                                <button type="button" class="btn btn-light" onclick="history.back()">Regresar</button>
                                <button type="submit" class="btn btn-success px-5 shadow-sm">
                                    Enviar al Siguiente Paso <i class="fas fa-paper-plane ms-2"></i>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card shadow-sm border-0">
                        <div class="card-header bg-transparent fw-bold py-3">
                            <i class="fas fa-history me-2 text-muted"></i> Historial
                        </div>
                        <div class="card-body p-3">
                            <div class="small text-muted mb-2">Iniciado el:</div>
                            <div class="fw-bold mb-3"><?php echo date('d/m/Y H:i', strtotime($instancia['fecha_inicio'])); ?></div>
                            <hr>
                            <div class="alert alert-info py-2 small border-0">
                                <i class="fas fa-info-circle me-1"></i> Una vez enviado, el trámite pasará a la bandeja del siguiente responsable.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once "../includes/footer.php"; ?>