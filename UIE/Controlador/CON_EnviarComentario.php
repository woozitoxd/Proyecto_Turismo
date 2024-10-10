<?php
require_once('../Modelo/conexion_bbdd.php');  //todos los archivos que se necesitan
require_once('../Controlador/CON_IniciarSesion.php');
require_once('../Controlador/CON_VerificarPermisos.php');
require_once('../Modelo/MOD_Comentario.php');

$usuarioID = $_SESSION['id'];//establezco el usuario id con el id de la sesion

if (!Permisos::tienePermiso('Comentar Sitio', $usuarioID)) {//validamos que tenga permiso para comentar, de lo contrario, mostramos error
    echo("error al comentar, no tiene permiso.");
    header('Location: ../Vistas/index.php'); //Si el usuario intento comentar y no tiene permiso, vuelvo al indice, mejorar en versiones futuras*
    exit();
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $coment = $_POST["descripcion"]; //Seteamos los campos de descripcion, nombre y el id del sitio que corresponda,
    $nombreUsuario = $_SESSION['nombre'];
    $id_sitio = $_POST["id_sitio"];
    $fechaYHoraActual = new DateTime('now', new DateTimeZone('America/Argentina/Buenos_Aires'));

    $ComentObj = new Comentarios("", "", "", "");  //instancio objeto de clase comentario
    $resultado = $ComentObj->AgregarComentario($usuarioID, $coment, $id_sitio, $fechaYHoraActual->format('Y-m-d H:i:s')); //invocom etodo que agrega los comentarios pasandole por parametro los datos

    if (!empty($resultado) && is_numeric($resultado)) { //si se pudo comentar, devuelvo los datos del nuevo comentario al front
        
        // Devuelve el JSON con el header correcto
        header('Content-Type: application/json');
        
        echo json_encode([
            'id_sitio' => $id_sitio,
            'nombre' => $nombreUsuario,
            'fechaPublicacion' => $fechaYHoraActual->format('Y-m-d H:i:s'),
            'comentario' => $coment,
            'id_comentario' => $resultado
        ]);

    }else{
        echo "Error al publicar comentario";
    }
} else {
    echo "Faltan campos en la solicitud.";
}
?>