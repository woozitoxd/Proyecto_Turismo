<?php

require_once('../Controlador/CON_IniciarSesion.php');
require_once('../Controlador/CON_VerificarPermisos.php');
require_once '../Modelo/MOD_SitioTuristico.php';

$categorias = SitioTuristico::obtenerTodasLasCategorias();
    
if ($categorias) {
    
    echo json_encode($categorias);
    
}

?>