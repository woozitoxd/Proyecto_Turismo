<?php
require_once '../Modelo/MOD_DeleteComentario.php'; // Asegúrate de incluir el modelo
require_once("../Modelo/MOD_ClaseUsuario.php");
require_once("../Modelo/MOD_Perfil.php");
require_once('../controlador/CON_IniciarSesion.php');
require_once('../controlador/CON_VerificarPermisos.php');
require_once('../Modelo/conexion_bbdd.php');

$usuarioID = null; 
if (isset($_SESSION['usuario']) && $_SESSION['usuario']){
    $usuarioID = $_SESSION['id'];
}

if (!Permisos::tienePermiso('Eliminar Comentario', $usuarioID) || !Permisos::esRol('administrador', $usuarioID)) {
    echo json_encode(['success' => false, 'error' => 'Error, no posee el permiso para eliminar comentario.']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $idComentario = $data['id'] ?? null; 

    if ($idComentario === null) {
        echo json_encode(['success' => false, 'error' => 'ID del comentario no proporcionado.']);
        exit;
    }

    if (!is_numeric($idComentario) || $idComentario <= 0) {
        echo json_encode(['success' => false, 'error' => 'ID de comentario no válido.']);
        exit;
    }

    $modelo = new ModeloComentarios();
    $resultado = $modelo->eliminarComentario($idComentario);

    if ($resultado) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => 'No se pudo eliminar el comentario.']);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Método no permitido.']);
}
