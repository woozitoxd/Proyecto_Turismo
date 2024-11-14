<?php

//require_once('../Controlador/CON_IniciarSesion.php');
require_once('../Controlador/CON_VerificarPermisos.php');
require_once '../Modelo/MOD_SitioTuristico.php';

$localidades = SitioTuristico::obtenerTodasLasLocalidades();
    
if ($localidades) {
    
    echo json_encode($localidades);
    
}

?>