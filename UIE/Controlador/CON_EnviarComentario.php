<?php

session_start();

require_once('../Modelo/conexion_bbdd.php');  //todos los archivos que se necesitan
require_once('../Controlador/CON_VerificarPermisos.php');
require_once('../Modelo/MOD_Comentario.php');

$usuarioID = $_SESSION['id'];//establezco el usuario id con el id de la sesion

if (!Permisos::tienePermiso('Comentar Sitio', $usuarioID)) {//validamos que tenga permiso para comentar, de lo contrario, mostramos error
    echo("error al comentar, no tiene permiso.");
    header('Location: ../Vistas/index.php'); //Si el usuario intento comentar y no tiene permiso, vuelvo al indice, mejorar en versiones futuras*
    exit();
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $coment = isset($_POST["descripcion"]) ? $_POST["descripcion"] : NULL; //Seteamos los campos de descripcion, nombre y el id del sitio que corresponda.
    $nombreUsuario = $_SESSION['nombre'];
    $valoracion = isset($_POST["valoracion"]) ? $_POST["valoracion"] : NULL;
    $id_sitio = isset($_POST["id_sitio"]) ? $_POST["id_sitio"] : NULL;

    if ( empty($coment) ) {
        echo json_encode(['success' => false, 'message' => 'Texto de opinión no puede estar vacío..', 'id_sitio' => $id_sitio]);
        exit();
    }
    if ( empty($valoracion) ) {
        echo json_encode(['success' => false, 'message' => 'Tienes que valorar el sitio para publicar tu opinión..', 'id_sitio' => $id_sitio]);
        exit();
    }

    $fechaYHoraActual = new DateTime('now', new DateTimeZone('America/Argentina/Buenos_Aires'));

    $ComentObj = new Comentarios("", "", "", "");  //instancio objeto de clase comentario

    $OpinionYaExiste = $ComentObj->VerificarComentarioExistente($id_sitio, $usuarioID);

    if ( $OpinionYaExiste == false) { //Si devuelve false aún no existe opinion de usuario

        $resultado = $ComentObj->AgregarComentario($usuarioID, $coment, $valoracion, $id_sitio, $fechaYHoraActual->format('Y-m-d H:i:s')); //invocom etodo que agrega los comentarios pasandole por parametro los datos
    
        if (!empty($resultado) && is_numeric($resultado)) { //si se pudo comentar, devuelvo los datos del nuevo comentario al front
            
            // Devuelve el JSON con el header correcto
            header('Content-Type: application/json');
            
            echo json_encode([
                'success' => true,
                'id_usuario' => $usuarioID,
                'id_sitio' => $id_sitio,
                'nombre' => $nombreUsuario,
                'fechaPublicacion' => $fechaYHoraActual->format('Y-m-d H:i:s'),
                'comentario' => $coment,
                'valoracion' => $valoracion,
                'id_comentario' => $resultado
            ]);
    
        }else{
            echo json_encode(['success' => false, 'message' => 'Error al agregar comentario..', 'id_sitio' => $id_sitio]);
        }

    }else if($OpinionYaExiste == true){
        echo json_encode(['success' => false, 'message' => 'Ya has publicado tu opinión sobre este sitio..', 'id_sitio' => $id_sitio]);
    }else{
        echo json_encode(['success' => false, 'message' => 'Error al comprobar existencia de reseña..', 'id_sitio' => $id_sitio]);
    }

} else {
    echo json_encode(['success' => false, 'message' => 'Faltan campos en la solicitud..', 'id_sitio' => $id_sitio]);
}
?>