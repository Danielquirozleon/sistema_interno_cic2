<?php
// El header ya incluye session_start(), seguridad y el inicio del diseño
require_once "includes/header.php"; 
require_once "../config/database.php";

$db = (new Database())->getConnection();

$id_usuario = $_SESSION['user_id'];
$id_rol     = $_SESSION['id_rol'] ?? 0;

// KPI: Consultamos tareas pendientes de forma segura
$sql_kpi = "SELECT COUNT(i.id_instancia) as total 
            FROM procesos_instancias i 
            JOIN pasos_definicion d ON i.paso_actual_id = d.id_paso
            WHERE i.estado != 'finalizado' 
            AND (i.id_usuario_asignado_actual = :u OR (i.id_usuario_asignado_actual IS NULL AND d.id_perfil_responsable = :r))";
$stmt = $db->prepare($sql_kpi);
$stmt->execute(['u' => $id_usuario, 'r' => $id_rol]);
$total_pendientes = $stmt->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;
?>

<div class="container-fluid animate-fade-in">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 fw-bold">Hola, <?php echo htmlspecialchars(explode(' ', $_SESSION['nombre'])[0]); ?></h1>
            <p class="text-muted">Este es el resumen de tus actividades hoy.</p>
        </div>
        <div class="text-end">
            <span class="badge bg-primary rounded-pill px-3 py-2">
                <i class="fas fa-calendar-alt me-2"></i><?php echo date('d/m/Y'); ?>
            </span>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm overflow-hidden" style="border-left: 5px solid var(--accent) !important;">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h6 class="text-uppercase fw-bold text-muted small mb-1">Tareas Pendientes</h6>
                            <h2 class="display-5 fw-bold mb-0" style="color: var(--accent);"><?php echo $total_pendientes; ?></h2>
                        </div>
                        <div class="ms-3">
                            <i class="fas fa-clipboard-check fa-3x opacity-25"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <h5 class="fw-bold mb-3"><i class="fas fa-th-large me-2"></i> Accesos Rápidos</h5>
    <div class="row g-4">
        
        <div class="col-12 col-sm-6 col-xl-3">
            <a href="views/procesos/bandeja" class="card h-100 border-0 shadow-sm text-decoration-none">
                <div class="card-body text-center p-4">
                    <div class="mb-3">
                        <i class="fas fa-inbox fa-3x" style="color: var(--accent);"></i>
                    </div>
                    <h5 class="fw-bold">Mi Bandeja</h5>
                    <p class="text-muted small mb-0">Atiende tus tareas y solicitudes.</p>
                </div>
            </a>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <a href="views/procesos/lista_procesos" class="card h-100 border-0 shadow-sm text-decoration-none">
                <div class="card-body text-center p-4">
                    <div class="mb-3 text-info">
                        <i class="fas fa-plus-circle fa-3x"></i>
                    </div>
                    <h5 class="fw-bold">Nuevo Trámite</h5>
                    <p class="text-muted small mb-0">Inicia un flujo de trabajo nuevo.</p>
                </div>
            </a>
        </div>

        <?php if($es_admin): ?>
            <div class="col-12 col-sm-6 col-xl-3">
                <a href="views/auth/admin/usuarios_lista" class="card h-100 border-0 shadow-sm text-decoration-none" style="border-top: 3px solid #2ecc71 !important;">
                    <div class="card-body text-center p-4">
                        <div class="mb-3 text-success">
                            <i class="fas fa-user-shield fa-3x"></i>
                        </div>
                        <h5 class="fw-bold">Usuarios</h5>
                        <p class="text-muted small mb-0">Administra accesos y perfiles.</p>
                    </div>
                </a>
            </div>

            <div class="col-12 col-sm-6 col-xl-3">
                <a href="views/procesos/admin_procesos" class="card h-100 border-0 shadow-sm text-decoration-none" style="border-top: 3px solid #e67e22 !important;">
                    <div class="card-body text-center p-4">
                        <div class="mb-3 text-warning">
                            <i class="fas fa-project-diagram fa-3x"></i>
                        </div>
                        <h5 class="fw-bold">Flujos</h5>
                        <p class="text-muted small mb-0">Configura pasos y procesos.</p>
                    </div>
                </a>
            </div>
        <?php endif; ?>

    </div>
</div>

<?php 
// El footer incluye los scripts de Bootstrap, SweetAlert2 y el cierre del diseño
require_once "includes/footer.php"; 
?>