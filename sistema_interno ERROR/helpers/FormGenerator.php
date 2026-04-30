<?php
class FormGenerator {
    public static function render($json_config) {
        $config = json_decode($json_config, true);
        $html = "";

        foreach ($config['campos'] as $campo) {
            $html .= "<div style='margin-bottom: 15px;'>";
            $html .= "<label style='display:block; font-weight:bold;'>" . $campo['label'] . "</label>";
            
            if ($campo['tipo'] == 'text') {
                $html .= "<input type='text' name='{$campo['nombre']}' required='{$campo['requerido']}' style='width:100%; padding:8px;'>";
            } elseif ($campo['tipo'] == 'select') {
                $html .= "<select name='{$campo['nombre']}' style='width:100%; padding:8px;'>";
                foreach ($campo['opciones'] as $opcion) {
                    $html .= "<option value='{$opcion}'>{$opcion}</option>";
                }
                $html .= "</select>";
            } elseif ($campo['tipo'] == 'textarea') {
                $html .= "<textarea name='{$campo['nombre']}' style='width:100%; padding:8px;'></textarea>";
            }
            
            $html .= "</div>";
        }
        return $html;
    }
}