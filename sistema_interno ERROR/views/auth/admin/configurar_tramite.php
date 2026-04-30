<?php
require_once "../../includes/header.php";
require_once "../../../config/database.php";

$id_instancia = $_GET['id'] ?? null;
if (!$id_instancia) { header("Location: ../../dashboard"); exit(); }

$db = (new Database())->getConnection();

// Información del trámite y pasos disponibles para el salto manual
$stmt = $db->prepare("SELECT i.*, p.nombre_proceso, d.nombre_paso 
                      FROM procesos_instancias i 
                      JOIN procesos p ON i.id_proceso = p.id_proceso
                      JOIN pasos_definicion d ON i.paso_actual_id = d.id_paso
                      WHERE i.id_instancia = ?");
$stmt->execute([$id_instancia]);
$instancia = $stmt->fetch(PDO::FETCH_ASSOC);

$pasos_disponibles = $db->prepare("SELECT * FROM pasos_definicion WHERE id_proceso = ? ORDER BY orden_paso");
$pasos_disponibles->execute([$instancia['id_proceso']]);
$pasos = $pasos_disponibles->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container animate-fade-in">
    <div class="row justify-content-center">
        <div class="col-lg-7">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-dark text-white pt-4 px-4">
                    <h5 class="fw-bold mb-0"><i class="fas fa-tools me-2 text-warning"></i>Intervención Administrativa</h5>
                    <p class="small opacity-75 mb-0">Trámite #<?php echo $id_instancia; ?> - <?php echo htmlspecialchars($instancia['nombre_proceso']); ?></p>
                </div>
                <div class="card-body p-4">
                    <form action="controllers/AdminController.php?action=intervenir_tramite" method="POST">
                        <input type="hidden" name="id_instancia" value="<?php echo $id_instancia; ?>">
                        
                        <div class="mb-4">
                            <label class="form-label small fw-bold">Estado del Trámite</label>
                            <select name="estado" class="form-select">
                                <option value="en_progreso" <?php echo $instancia['estado'] == 'en_progreso' ? 'selected' : ''; ?>>En Progreso (Activo)</option>
                                <option value="pausado" <?php echo $instancia['estado'] == 'pausado' ? 'selected' : ''; ?>>Pausado / Detenido</option>
                                <option value="cancelado" <?php echo $instancia['estado'] == 'cancelado' ? 'selected' : ''; ?>>Cancelado / Rechazado</option>
                                <option value="finalizado" <?php echo $instancia['estado'] == 'finalizado' ? 'selected' : ''; ?>>Finalizado (Cerrar)</option>
                            </select>
                        </div>

                        <div class="mb-4">
                            <label class="form-label small fw-bold">Mover a un Paso Específico</label>
                            <select name="nuevo_paso" class="form-select">
                                <?php foreach($pasos as $paso): ?>
                                    <option value="<?php echo $paso['id_paso']; ?>" <?php echo $paso['id_paso'] == $instancia['paso_actual_id'] ? 'selected' : ''; ?>>
                                        Paso <?php echo $paso['orden_paso']; ?>: <?php echo htmlspecialchars($paso['nombre_paso']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <div class="form-text small">Usa esto solo si el flujo automático se detuvo o requiere un salto.</div>
                        </div>

                        <div class="alert alert-warning border-0 small">
                            <i class="fas fa-exclamation-triangle me-2"></i> 
                            Cualquier cambio aquí afectará la bandeja del usuario responsable de inmediato.
                        </div>

                        <div class="mt-4 d-flex justify-content-between">
                            <a href="views/dashboard" class="btn btn-light">Volver</a>
                            <button type="submit" class="btn btn-warning px-4 fw-bold">Aplicar Cambios</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once "../../includes/footer.php"; ?>