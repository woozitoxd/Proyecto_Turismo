<?php

session_start();
require_once('../Controlador/CON_VerificarPermisos.php');
require_once '../Modelo/MOD_SitioTuristico.php';

if (isset($_SESSION['id']) && $_SESSION['id']) {
    
    $SitiosPropios = SitioTuristico::ObtenerSitiosPropios($_SESSION['id']);
    
    if ($SitiosPropios) {
    
        echo json_encode($SitiosPropios);
    
    }
}

?>