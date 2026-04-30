<?php
require_once "../includes/header.php";
?>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-transparent border-0 pt-4 px-4 text-center">
                    <div class="icon-box bg-primary text-white rounded-circle d-inline-flex p-3 mb-3">
                        <i class="fas fa-layer-group fa-2x"></i>
                    </div>
                    <h4 class="fw-bold mb-0">Crear Definición de Proceso</h4>
                </div>
                <div class="card-body p-4">
                    <form action="controllers/ProcesoController.php?action=guardar" method="POST">
                        <div class="mb-3">
                            <label class="form-label small fw-bold">Nombre del Proceso</label>
                            <input type="text" name="nombre_proceso" class="form-control" 
                                   placeholder="Ej: Solicitud de Vacaciones" required>
                        </div>
                        <div class="mb-4">
                            <label class="form-label small fw-bold">Descripción General</label>
                            <textarea name="descripcion" class="form-control" rows="3" 
                                      placeholder="Explica brevemente para qué sirve este flujo..."></textarea>
                        </div>
                        
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Guardar y Continuar a Pasos
                            </button>
                            <a href="views/procesos/admin_procesos" class="btn btn-light">Cancelar</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once "../includes/footer.php"; ?>