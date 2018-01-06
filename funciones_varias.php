<?php

function getURLPrecios($cliente) {

    global $Servidor;
    $URL = "";
    switch ($cliente) {
        case 7: // Nivea            
            $URL = $Servidor . 'tables/ReportePrecios_formarto_nivea.php';
            break;

        default:
            $URL = $Servidor . 'tables/ReportePrecios.php';
            break;
    }
    return $URL;
}

function getURLFechasVencimiento($cliente) {

    global $Servidor;
    $URL = "";
    switch ($cliente) {
        case 1: // Cafe soluble       
            $URL = $Servidor . 'tables/ReporteFechaVencimientos_formato_cssa.php';
            break;

        default:
            $URL = $Servidor . 'tables/ReporteFechaVencimientos.php';
            break;
    }
    return $URL;
}
?>