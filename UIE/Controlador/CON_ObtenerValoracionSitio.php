<?php
require_once('../Modelo/conexion_bbdd.php');  //todos los archivos que se necesitan
require_once('../Controlador/CON_IniciarSesion.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $id_sitio = isset($_POST["id_sitio_valorado"]) ? $_POST["id_sitio_valorado"] : NULL;

    if ( empty($id_sitio) ) {
        echo json_encode(['success' => false, 'message' => 'No se recibió ID de Sitio..', 'id_sitio' => $id_sitio]);
        exit();
    }

    require_once '../Modelo/MOD_SitioTuristico.php';

    $ValoracionPromedioSitio = SitioTuristico::ObtenerValoracionPromedioSitio($id_sitio);

    if ($ValoracionPromedioSitio) {
            
        echo json_encode([
            'success' => true,
            'valoracion' => $ValoracionPromedioSitio["valoracion_promedio"],
            'id_sitio' => $id_sitio
        ]);

    }else{
        echo json_encode(['success' => false, 'message' => 'Error al recibir valoracion de sitio..', 'id_sitio' => $id_sitio]);
    }

    /* if (!empty($ValoracionPromedioSitio) && is_numeric($ValoracionPromedioSitio)) { //si se pudo comentar, devuelvo los datos del nuevo comentario al front
            
        // Devuelve el JSON con el header correcto
        header('Content-Type: application/json');
        
        echo json_encode([
            'success' => true,
            'valoracion' => $usuarioID,
            'id_sitio' => $id_sitio
        ]);

    }else{
        echo json_encode(['success' => false, 'message' => 'Error al recibir valoracion de sitio..', 'id_sitio' => $id_sitio]);
    } */

} else {
    echo json_encode(['success' => false, 'message' => 'Faltan campos en la solicitud..', 'id_sitio' => $id_sitio]);
}
?>