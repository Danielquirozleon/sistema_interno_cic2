<?php
require_once __DIR__ . '/../../config/database.php';

// Validamos que venga un ID de tipo de solicitud
if (isset($_GET['id'])) {
    $id_tipo = intval($_GET['id']);
    
    try {
        $db = (new Database())->getConnection();
        
        // Consultamos los campos configurados para este trámite específico
        $query = "SELECT * FROM campos_solicitud WHERE id_tipo = :id ORDER BY id_campo ASC";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':id', $id_tipo, PDO::PARAM_INT);
        $stmt->execute();
        $campos = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (count($campos) > 0) {
            echo "<h4>Datos requeridos para este trámite:</h4>";
            echo "<div style='background: #f9f9f9; padding: 15px; border-radius: 8px; border: 1px solid #ddd;'>";
            
            foreach ($campos as $campo) {
                $nombre_input = "extra_" . $campo['id_campo']; // Nombre único para procesar luego
                $label = htmlspecialchars($campo['etiqueta']);
                $tipo = $campo['tipo_dato']; // text, number, date
                $req = $campo['es_obligatorio'] ? 'required' : '';

                echo "<div style='margin-bottom: 15px;'>";
                echo "<label style='display:block; font-weight:bold; margin-bottom:5px;'>$label:</label>";
                
                // Generamos el input dinámicamente según el tipo definido por Sistemas
                echo "<input type='$tipo' name='campos_dinamicos[$nombre_input]' $req 
                        style='width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;' 
                        placeholder='Ingrese $label'>";
                echo "</div>";
            }
            
            echo "</div><br>";
        } else {
            // Si el trámite no requiere campos extras (es solo una notificación)
            echo "<p style='color: #666; font-style: italic;'>Este trámite no requiere información adicional.</p>";
        }

    } catch (PDOException $e) {
        echo "Error al cargar los campos: " . $e->getMessage();
    }
} else {
    echo "No se especificó un tipo de solicitud.";
}