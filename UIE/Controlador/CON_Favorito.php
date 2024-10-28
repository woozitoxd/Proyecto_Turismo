<?php

session_start();
require_once('../Controlador/CON_VerificarPermisos.php');

$ID_Sitio = $_POST["id_sitio"];
$ID_Usuario = $_SESSION['id'];

require_once '../Modelo/MOD_SitioTuristico.php';

$estadoFavorito = SitioTuristico::VerificarSitioFavorito($ID_Sitio, $ID_Usuario);

if ($estadoFavorito == true) {

    if(SitioTuristico::EliminarFavorito($ID_Sitio, $ID_Usuario)){
        echo json_encode([
            'favoritoestado' => "eliminado"
        ]);
    }else{
        echo "Error al guardar/eliminar favorito";
    }

}else{

    if(SitioTuristico::GuardarFavorito($ID_Sitio, $ID_Usuario)){
        echo json_encode([
            'favoritoestado' => "guardado"
        ]);
    }else{
        echo "Error al guardar/eliminar favorito";
    }

}

?>