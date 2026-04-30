<?php
require_once "../includes/header.php";
require_once "../../config/database.php";

$id_proceso = $_GET['id'] ?? null;
if (!$id_proceso) { header("Location: admin_procesos.php"); exit(); }

$db = (new Database())->getConnection();

// Obtener información del proceso
$stmt = $db->prepare("SELECT * FROM procesos WHERE id_proceso = ?");
$stmt->execute([$id_proceso]);
$proceso = $stmt->fetch(PDO::FETCH_ASSOC);

// Obtener los pasos ya configurados
$stmt = $db->prepare("SELECT p.*, r.nombre_rol 
                      FROM pasos_definicion p 
                      JOIN roles r ON p.id_perfil_responsable = r.id_rol 
                      WHERE p.id_proceso = ? ORDER BY p.orden_paso ASC");
$stmt->execute([$id_proceso]);
$pasos = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Obtener roles para el selector
$roles = $db->query("SELECT * FROM roles")->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container-fluid animate-fade-in">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="views/procesos/admin_procesos">Procesos</a></li>
            <li class="breadcrumb-item active"><?php echo htmlspecialchars($proceso['nombre_proceso']); ?></li>
        </ol>
    </nav>

    <div class="row g-4">
        <div class="col-lg-7">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-transparent border-0 pt-4 px-4">
                    <h5 class="fw-bold"><i class="fas fa-stream me-2 text-primary"></i>Secuencia del Flujo</h5>
                </div>
                <div class="card-body px-4">
                    <?php if(empty($pasos)): ?>
                        <div class="text-center py-5">
                            <img src="https://cdn-icons-png.flaticon.com/512/4076/4076549.png" style="width: 80px; opacity: 0.3;" class="mb-3">
                            <p class="text-muted">No hay pasos definidos. Comienza agregando el primero a la derecha.</p>
                        </div>
                    <?php else: ?>
                        <div class="timeline-simple">
                            <?php foreach($pasos as $paso): ?>
                                <div class="d-flex mb-4 position-relative">
                                    <div class="step-number bg-primary text-white rounded-circle d-flex align-items-center justify-content-center fw-bold shadow-sm" style="width: 40px; height: 40px; min-width: 40px; z-index: 2;">
                                        <?php echo $paso['orden_paso']; ?>
                                    </div>
                                    <div class="ms-3 card border w-100 p-3 shadow-sm-hover">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div>
                                                <h6 class="fw-bold mb-1"><?php echo htmlspecialchars($paso['nombre_paso']); ?></h6>
                                                <span class="badge bg-light text-dark border">
                                                    <i class="fas fa-user-tag me-1 text-primary"></i> <?php echo $paso['nombre_rol']; ?>
                                                </span>
                                            </div>
                                            <button class="btn btn-sm btn-outline-danger border-0" onclick="notify('Eliminar paso en desarrollo', 'warning')">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="col-lg-5">
            <div class="card shadow-sm border-0 sticky-top" style="top: 100px;">
                <div class="card-header bg-transparent border-0 pt-4 px-4">
                    <h5 class="fw-bold"><i class="fas fa-plus-circle me-2 text-success"></i>Nuevo Paso</h5>
                </div>
                <div class="card-body p-4">
                    <form action="controllers/EstructuraController.php?action=agregar_paso" method="POST">
                        <input type="hidden" name="id_proceso" value="<?php echo $id_proceso; ?>">
                        
                        <div class="mb-3">
                            <label class="form-label small fw-bold">Nombre del Paso</label>
                            <input type="text" name="nombre_paso" class="form-control" placeholder="Ej: Aprobación de Gerencia" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label small fw-bold">Orden en la secuencia</label>
                            <input type="number" name="orden_paso" class="form-control" value="<?php echo count($pasos) + 1; ?>" required>
                        </div>

                        <div class="mb-4">
                            <label class="form-label small fw-bold">Perfil Responsable</label>
                            <select name="id_rol" class="form-select" required>
                                <option value="">Seleccione quién atiende este paso...</option>
                                <?php foreach($roles as $rol): ?>
                                    <option value="<?php echo $rol['id_rol']; ?>"><?php echo $rol['nombre_rol']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-success w-100 py-2 fw-bold">
                            <i class="fas fa-plus me-2"></i>Registrar Paso
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .timeline-simple::before {
        content: '';
        position: absolute;
        left: 19px;
        top: 20px;
        bottom: 20px;
        width: 2px;
        background: #dee2e6;
        z-index: 1;
    }
    [data-theme="dark"] .timeline-simple::before { background: #333; }
</style>

<?php require_once "../includes/footer.php"; ?>