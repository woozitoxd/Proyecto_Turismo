<?php

class SitioTuristicoContoller
{
    public function MostrarSitiosTuristicos()
    {

        require_once '../Modelo/MOD_SitioTuristico.php';

        $sitios = SitioTuristico::ObtenerSitios();
        require '../Vistas/VIS_sitioTuristicos.php';
    }

}
?>