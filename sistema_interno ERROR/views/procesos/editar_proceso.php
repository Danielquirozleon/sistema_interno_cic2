<?php
ob_start(); // Previene errores de "Headers already sent"
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Validación de seguridad absoluta
if (!isset($_SESSION['user_id']) || !isset($_SESSION['id_rol']) || $_SESSION['id_rol'] != 1) {
    header("Location: ../auth/login.php?error=expired");
    exit();
}

require_once "../../config/database.php";
$db = (new Database())->getConnection();

$id_paso = $_GET['id'] ?? null;

if (!$id_paso) {
    header("Location: admin_procesos.php");
    exit();
}

// Consultar el paso
$stmt = $db->prepare("SELECT * FROM pasos_definicion WHERE id_paso = ?");
$stmt->execute([$id_paso]);
$paso = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$paso) {
    die("El paso no existe en la base de datos.");
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Paso</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root { --bg: #f4f7f6; --card: #ffffff; --text: #2c3e50; }
        [data-theme="dark"] { --bg: #121212; --card: #1e1e1e; --text: #e0e0e0; }
        body { font-family: 'Segoe UI', sans-serif; background: var(--bg); color: var(--text); padding: 40px; transition: 0.3s; }
        .container { max-width: 500px; margin: auto; background: var(--card); padding: 30px; border-radius: 12px; box-shadow: 0 5px 20px rgba(0,0,0,0.1); }
        input { width: 100%; padding: 10px; margin: 10px 0 20px; border: 1px solid #ddd; border-radius: 6px; background: transparent; color: inherit; }
        .btn { background: #2ecc71; color: white; border: none; padding: 12px; border-radius: 6px; width: 100%; cursor: pointer; font-weight: bold; }
    </style>
</head>
<body>

<div class="container">
    <a href="configurar_pasos.php?id=<?php echo $paso['id_proceso']; ?>" style="text-decoration:none; color:#3498db;">← Volver</a>
    <h2>Editar Paso</h2>
    
    <form action="../../controllers/ProcesosController.php?action=actualizar_paso" method="POST">
        <input type="hidden" name="id_paso" value="<?php echo $paso['id_paso']; ?>">
        
        <label>Nombre del Paso:</label>
        <input type="text" name="nombre_paso" value="<?php echo htmlspecialchars($paso['nombre_paso']); ?>" required>
        
        <label>Orden:</label>
        <input type="number" name="orden" value="<?php echo $paso['orden']; ?>" required>
        
        <button type="submit" class="btn">Guardar Cambios</button>
    </form>
</div>

<script>
    if(localStorage.getItem('theme') === 'dark') document.body.setAttribute('data-theme', 'dark');
</script>

</body>
</html>
<?php ob_end_flush(); ?>