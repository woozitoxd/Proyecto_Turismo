<?php

class SitioTuristicoContoller
{
    public function MostrarSitiosTuristicos()
    {

        require_once '../Modelo/MOD_SitioTuristico.php';

        $sitios = SitioTuristico::ObtenerSitios();
        require '../Vistas/VIS_sitioTuristicos.php';
    }

    public function TraerCategorias(){
        require_once '../Modelo/MOD_SitioTuristico.php';
        $categorias =SitioTuristico::obtenerTodasLasCategorias();
        $localidades = SitioTuristico::obtenerTodasLasLocalidades();
        $etiquetas = SitioTuristico::obtenerTodasLasEtiquetas();
        require '../Vistas/VIS_BuscadorSitios.php';
    }

}
