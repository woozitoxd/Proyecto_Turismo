<?php

require_once('../Controlador/CON_IniciarSesion.php');
require_once('../Controlador/CON_VerificarPermisos.php');
require_once '../Modelo/MOD_SitioTuristico.php';

if (isset($_SESSION['id']) && $_SESSION['id']) {
    
    $SitiosFavoritos = SitioTuristico::ObtenerSitiosFavoritos($_SESSION['id']);
    
    if ($SitiosFavoritos) {
    
        echo json_encode($SitiosFavoritos);
    
    }
}

?>