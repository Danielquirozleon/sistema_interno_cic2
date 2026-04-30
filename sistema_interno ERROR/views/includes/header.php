<?php
ob_start();
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Seguridad: Si no hay sesión, redirigir al login
if (!isset($_SESSION['user_id'])) {
    header("Location: /sistema_interno/views/auth/login.php");
    exit();
}

$es_admin = ($_SESSION['id_rol'] == 1);
$nombre_usuario = $_SESSION['nombre'] ?? 'Usuario';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <base href="/sistema_interno/">
    
    <title>Sistema Interno</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary-bg: #f8f9fa;
            --sidebar-bg: #212529;
            --card-bg: #ffffff;
            --text-main: #333333;
            --accent: #0d6efd;
        }

        /* Variables para MODO DARK */
        [data-theme="dark"] {
            --primary-bg: #121212;
            --sidebar-bg: #000000;
            --card-bg: #1e1e1e;
            --text-main: #e0e0e0;
            --accent: #375a7f;
        }

        body { 
            font-family: 'Inter', sans-serif; 
            background: var(--primary-bg); 
            color: var(--text-main); 
            display: flex; 
            min-height: 100vh; 
            margin: 0;
            transition: background 0.3s ease;
        }
        
        /* Sidebar Estilizado */
        .sidebar { 
            width: 280px; 
            background: var(--sidebar-bg); 
            color: white; 
            position: fixed; 
            height: 100vh; 
            padding: 1.5rem; 
            display: flex; 
            flex-direction: column;
            z-index: 1000;
        }

        .main-content { 
            margin-left: 280px; 
            width: 100%; 
            padding: 2.5rem; 
        }
        
        .nav-link { 
            color: #adb5bd; 
            padding: 0.8rem 1rem; 
            border-radius: 0.5rem; 
            transition: 0.2s; 
            margin-bottom: 0.3rem; 
            display: flex;
            align-items: center;
            text-decoration: none;
        }

        .nav-link:hover, .nav-link.active { 
            background: var(--accent); 
            color: white; 
        }

        .nav-link i { margin-right: 12px; width: 20px; text-align: center; }

        .admin-divider {
            height: 1px;
            background: rgba(255,255,255,0.1);
            margin: 1.5rem 0;
        }

        /* Animación suave al cargar */
        .animate-fade { animation: fadeIn 0.4s ease-in-out; }
        @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
    </style>

    <script>
        // Aplicar tema antes de renderizar para evitar el parpadeo blanco
        if (localStorage.getItem('theme') === 'dark') {
            document.documentElement.setAttribute('data-theme', 'dark');
        }
    </script>
</head>
<body>

<div class="sidebar shadow">
    <div class="text-center mb-4">
        <h4 class="fw-bold text-uppercase" style="letter-spacing: 1px;">Sistema</h4>
        <small class="opacity-50">V 2.0 - XAMPP</small>
    </div>
    
    <nav class="nav flex-column mb-auto">
        <a href="views/dashboard" class="nav-link"><i class="fas fa-th-large"></i> Dashboard</a>
        <a href="views/procesos/bandeja" class="nav-link"><i class="fas fa-inbox"></i> Mi Bandeja</a>
        <a href="views/procesos/lista_procesos" class="nav-link"><i class="fas fa-plus-circle"></i> Nuevo Trámite</a>
        
        <?php if($es_admin): ?>
            <div class="admin-divider"></div>
            <div class="px-3 mb-2 small text-uppercase opacity-50 fw-bold" style="font-size: 0.7rem;">Admin</div>
            <a href="views/auth/admin/usuarios_lista" class="nav-link"><i class="fas fa-users-cog"></i> Usuarios</a>
            <a href="views/procesos/admin_procesos" class="nav-link"><i class="fas fa-project-diagram"></i> Flujos</a>
        <?php endif; ?>
    </nav>

    <div class="mt-auto pt-3 border-top border-secondary">
        <button onclick="toggleTheme()" class="btn btn-outline-light btn-sm w-100 mb-2">
            <i class="fas fa-adjust me-2"></i> Cambiar Tema
        </button>
        <a href="controllers/AuthController.php?action=logout" class="btn btn-danger btn-sm w-100">
            <i class="fas fa-power-off me-2"></i> Cerrar Sesión
        </a>
    </div>
</div>

<div class="main-content animate-fade">