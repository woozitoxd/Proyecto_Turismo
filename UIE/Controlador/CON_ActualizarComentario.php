<?php
require_once('../Modelo/conexion_bbdd.php');  // Conexión a la base de datos
require_once('../Controlador/CON_IniciarSesion.php');
require_once('../Controlador/CON_VerificarPermisos.php');
require_once('../Modelo/MOD_Comentario.php');

$usuarioID = $_SESSION['id']; // Establezco el usuario ID con el ID de la sesión

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idComentario = $_POST['id_comentario'];
    $nuevoComentario = $_POST['nuevo_comentario'];

    // Validar que el ID del comentario y el nuevo comentario no estén vacíos
    if (empty($idComentario) || empty($nuevoComentario)) {
        echo json_encode(['success' => false, 'message' => 'ID de comentario o texto vacío.']);
        exit();
    }

    $comentarioModel = new Comentarios("", "", "", ""); // Crear instancia del modelo
    $resultado = $comentarioModel->actualizarComentario($idComentario, $nuevoComentario, $usuarioID);

    // Retornar la respuesta como JSON
    echo json_encode($resultado);
} else {
    echo json_encode(['success' => false, 'message' => 'Método no permitido.']);
}
?>
