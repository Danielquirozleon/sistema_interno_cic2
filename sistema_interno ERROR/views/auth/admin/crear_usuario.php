<?php
require_once "../../includes/header.php";
require_once "../../../config/database.php";

// Solo Admin puede ver esto
if (!$_SESSION['id_rol'] == 1) {
    echo "<script>window.location.href='views/dashboard';</script>";
    exit();
}

$db = (new Database())->getConnection();
$roles = $db->query("SELECT * FROM roles")->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-transparent border-0 pt-4 px-4">
                    <h4 class="fw-bold mb-0"><i class="fas fa-user-plus me-2 text-primary"></i> Registrar Nuevo Usuario</h4>
                    <p class="text-muted small">Completa la información para dar de alta un perfil.</p>
                </div>
                <div class="card-body p-4">
                    <form action="controllers/UsuarioController.php?action=guardar" method="POST">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label small fw-bold">Nombre Completo</label>
                                <input type="text" name="nombre" class="form-control" placeholder="Ej: Juan Pérez" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold">Correo Electrónico</label>
                                <input type="email" name="email" class="form-control" placeholder="juan@ejemplo.com" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold">Contraseña Temporal</label>
                                <input type="password" name="password" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold">Asignar Rol</label>
                                <select name="id_rol" class="form-select" required>
                                    <option value="">Seleccione un rol...</option>
                                    <?php foreach($roles as $rol): ?>
                                        <option value="<?= $rol['id_rol'] ?>"><?= $rol['nombre_rol'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <div class="mt-4 pt-3 border-top d-flex justify-content-end gap-2">
                            <a href="views/auth/admin/usuarios_lista" class="btn btn-light text-muted">Cancelar</a>
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="fas fa-save me-2"></i> Guardar Usuario
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once "../../includes/footer.php"; ?>