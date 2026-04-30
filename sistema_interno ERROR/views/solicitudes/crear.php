<?php
require_once "../includes/header.php";
require_once "../../config/database.php";

$id_proceso = $_GET['id_proceso'] ?? null;
if (!$id_proceso) { header("Location: ../procesos/lista_procesos"); exit(); }

$db = (new Database())->getConnection();

// Obtener datos del proceso para el encabezado
$stmt = $db->prepare("SELECT * FROM procesos WHERE id_proceso = ?");
$stmt->execute([$id_proceso]);
$proceso = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<div class="container animate-fade-in">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white border-0 pt-4 px-4">
                    <div class="d-flex align-items-center">
                        <div class="icon-box bg-primary text-white rounded-circle p-3 me-3">
                            <i class="fas fa-file-signature fa-xl"></i>
                        </div>
                        <div>
                            <h4 class="fw-bold mb-0"><?php echo htmlspecialchars($proceso['nombre_proceso']); ?></h4>
                            <p class="text-muted small mb-0">Nueva Solicitud de Trámite</p>
                        </div>
                    </div>
                </div>
                
                <div class="card-body p-4">
                    <div class="alert alert-light border-start border-primary border-4 py-3 mb-4">
                        <i class="fas fa-info-circle me-2 text-primary"></i> 
                        Al enviar esta solicitud, se iniciará el flujo de aprobación automática.
                    </div>

                    <form action="controllers/SolicitudController.php?action=registrar" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="id_proceso" value="<?php echo $id_proceso; ?>">
                        
                        <div class="mb-4">
                            <label class="form-label fw-bold small">Título o Motivo de la Solicitud</label>
                            <input type="text" name="titulo" class="form-control form-control-lg" placeholder="Ej: Solicitud de Laptop - Departamento IT" required>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold small">Descripción Detallada</label>
                            <textarea name="descripcion" class="form-control" rows="5" placeholder="Explica los detalles de tu requerimiento..."></textarea>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold small">Prioridad</label>
                            <div class="d-flex gap-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="prioridad" id="p1" value="baja" checked>
                                    <label class="form-check-label" for="p1">Baja</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="prioridad" id="p2" value="media">
                                    <label class="form-check-label" for="p2">Media</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="prioridad" id="p3" value="alta">
                                    <label class="form-check-label" for="p3 text-danger">Alta</label>
                                </div>
                            </div>
                        </div>

                        <div class="mt-5 d-flex justify-content-between">
                            <a href="views/procesos/lista_procesos" class="btn btn-light px-4">Cancelar</a>
                            <button type="submit" class="btn btn-primary px-5 fw-bold shadow-sm">
                                Enviar Solicitud <i class="fas fa-paper-plane ms-2"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once "../includes/footer.php"; ?>