<?php
require_once "../includes/header.php";
require_once "../../config/database.php";

$id_paso = $_GET['id'] ?? null;
if (!$id_paso) { header("Location: admin_procesos.php"); exit(); }

$db = (new Database())->getConnection();

// Obtener datos del paso
$stmt = $db->prepare("SELECT * FROM pasos_definicion WHERE id_paso = ?");
$stmt->execute([$id_paso]);
$paso = $stmt->fetch(PDO::FETCH_ASSOC);

// Obtener roles para el selector
$roles = $db->query("SELECT * FROM roles")->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container animate-fade-in">
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white border-0 pt-4 px-4">
                    <h5 class="fw-bold mb-0"><i class="fas fa-edit me-2 text-warning"></i>Editar Paso de Proceso</h5>
                </div>
                <div class="card-body p-4">
                    <form action="controllers/EstructuraController.php?action=actualizar_paso" method="POST">
                        <input type="hidden" name="id_paso" value="<?php echo $id_paso; ?>">
                        <input type="hidden" name="id_proceso" value="<?php echo $paso['id_proceso']; ?>">
                        
                        <div class="mb-3">
                            <label class="form-label small fw-bold">Nombre del Paso</label>
                            <input type="text" name="nombre_paso" class="form-control" 
                                   value="<?php echo htmlspecialchars($paso['nombre_paso']); ?>" required>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label small fw-bold">Orden</label>
                                <input type="number" name="orden_paso" class="form-control" 
                                       value="<?php echo $paso['orden_paso']; ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold">Perfil Responsable</label>
                                <select name="id_rol" class="form-select" required>
                                    <?php foreach($roles as $rol): ?>
                                        <option value="<?php echo $rol['id_rol']; ?>" 
                                            <?php echo ($rol['id_rol'] == $paso['id_perfil_responsable']) ? 'selected' : ''; ?>>
                                            <?php echo $rol['nombre_rol']; ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <div class="d-grid gap-2 mt-4">
                            <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                            <a href="views/procesos/configurar_pasos?id=<?php echo $paso['id_proceso']; ?>" class="btn btn-light">Cancelar</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once "../includes/footer.php"; ?>