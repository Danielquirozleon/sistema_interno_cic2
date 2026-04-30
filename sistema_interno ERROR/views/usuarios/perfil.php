<?php
require_once "../includes/header.php";
require_once "../../config/database.php";

$db = (new Database())->getConnection();
$id_usuario = $_SESSION['user_id'];

// Obtenemos datos frescos del usuario
$stmt = $db->prepare("SELECT u.*, r.nombre_rol FROM usuarios u JOIN roles r ON u.id_rol = r.id_rol WHERE u.id_usuario = ?");
$stmt->execute([$id_usuario]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<div class="container animate-fade-in">
    <div class="row">
        <div class="col-lg-4">
            <div class="card shadow-sm border-0 text-center p-4 mb-4">
                <div class="mb-3">
                    <img src="assets/img/default_user.png.png" class="rounded-circle img-thumbnail shadow-sm" style="width: 150px; height: 150px; object-fit: cover;">
                </div>
                <h4 class="fw-bold mb-0"><?php echo htmlspecialchars($user['nombre']); ?></h4>
                <p class="text-muted"><?php echo $user['nombre_rol']; ?></p>
                <div class="badge bg-light text-primary border px-3 py-2">ID: #<?php echo $user['id_usuario']; ?></div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card shadow-sm border-0 p-4">
                <h5 class="fw-bold mb-4 border-bottom pb-2">Información Personal</h5>
                <form action="controllers/PerfilController.php?action=actualizar" method="POST">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-muted">Nombre Completo</label>
                            <input type="text" name="nombre" class="form-control" value="<?php echo htmlspecialchars($user['nombre']); ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-muted">Correo Electrónico</label>
                            <input type="email" class="form-control bg-light" value="<?php echo htmlspecialchars($user['email']); ?>" readonly title="El correo no puede cambiarse">
                        </div>
                        <div class="col-12 mt-4">
                            <h5 class="fw-bold mb-3 border-bottom pb-2">Seguridad</h5>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-muted">Nueva Contraseña</label>
                            <input type="password" name="new_password" class="form-control" placeholder="Dejar en blanco para no cambiar">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-muted">Confirmar Contraseña</label>
                            <input type="password" name="confirm_password" class="form-control">
                        </div>
                    </div>

                    <div class="text-end mt-4">
                        <button type="submit" class="btn btn-primary px-5">
                            <i class="fas fa-check-circle me-2"></i>Actualizar Perfil
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once "../includes/footer.php"; ?>