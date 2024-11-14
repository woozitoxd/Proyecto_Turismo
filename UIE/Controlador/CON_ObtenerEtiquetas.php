<?php

//require_once('../Controlador/CON_IniciarSesion.php');
require_once('../Controlador/CON_VerificarPermisos.php');
require_once '../Modelo/MOD_SitioTuristico.php';

$etiquetas = SitioTuristico::obtenerTodasLasEtiquetas();
    
if ($etiquetas) {
    
    echo json_encode($etiquetas);
    
}

?>