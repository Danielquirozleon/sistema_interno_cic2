<?php
require_once "../includes/header.php";
require_once "../../config/database.php";

$db = (new Database())->getConnection();
$procesos = $db->query("SELECT * FROM procesos ORDER BY nombre_proceso ASC")->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container animate-fade-in">
    <div class="text-center mb-5">
        <h2 class="fw-bold">Iniciar Nuevo Trámite</h2>
        <p class="text-muted">Selecciona el tipo de proceso que deseas comenzar.</p>
    </div>

    <div class="row g-4 justify-content-center">
        <?php foreach($procesos as $p): ?>
        <div class="col-md-6 col-lg-4">
            <div class="card h-100 shadow-sm border-0 card-hover-primary">
                <div class="card-body p-4 text-center">
                    <div class="icon-lg bg-primary-soft text-primary rounded-circle mb-4 mx-auto d-flex align-items-center justify-content-center" style="width: 70px; height: 70px; background: rgba(13, 110, 253, 0.1);">
                        <i class="fas fa-folder-plus fa-2x"></i>
                    </div>
                    <h5 class="fw-bold"><?php echo htmlspecialchars($p['nombre_proceso']); ?></h5>
                    <p class="text-muted small mb-4"><?php echo htmlspecialchars($p['descripcion']); ?></p>
                    <a href="views/procesos/iniciar_instancia?id_proceso=<?php echo $p['id_proceso']; ?>" 
                       class="btn btn-outline-primary w-100 rounded-pill">
                        Comenzar Solicitud
                    </a>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>

<?php require_once "../includes/footer.php"; ?>