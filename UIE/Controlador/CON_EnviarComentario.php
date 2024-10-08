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

    $ComentObj = new Comentarios("", "", "", "");  //instancio objeto de clase comentario
    $resultado = $ComentObj->AgregarComentario($usuarioID, $coment, $id_sitio); //invocom etodo que agrega los comentarios pasandole por parametro los datos

    if ($resultado === true) { //si se pudo comentar, vuelvo al inicio
        header('Location: ../Vistas/index.php');
        exit();
    } else {
        echo $resultado;
    }
} else {
    echo "Faltan campos en la solicitud.";
}
?>