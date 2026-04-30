<?php
class DateHelper {
    public static function formatNice($fecha) {
        $timestamp = strtotime($fecha);
        $meses = ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"];
        
        $dia = date('d', $timestamp);
        $mes = $meses[date('n', $timestamp) - 1];
        $anio = date('Y', $timestamp);
        $hora = date('H:i', $timestamp);
        
        return "$dia de $mes, $anio ($hora)";
    }
}