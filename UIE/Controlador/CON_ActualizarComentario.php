<?php
session_start();
require_once('../Modelo/conexion_bbdd.php');  // Conexión a la base de datos
require_once('../Controlador/CON_VerificarPermisos.php');
require_once('../Modelo/MOD_Comentario.php');

$usuarioID = $_SESSION['id']; // Establezco el usuario ID con el ID de la sesión

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idComentario = $_POST['id_comentario'];
    $nuevoComentario = $_POST['nuevo_comentario'];
    $nuevaValoracion = $_POST['nueva_valoracion']; // Asegúrate de incluir esto

    // Validar que el ID del comentario, el nuevo comentario y la nueva valoración no estén vacíos
    if (empty($idComentario) || empty($nuevoComentario) || empty($nuevaValoracion)) {
        echo json_encode(['success' => false, 'message' => 'Por favor, completa todos los campos.']);
        exit(); // Asegúrate de salir aquí
    }

    // Crear instancia del modelo
    $comentarioModel = new Comentarios("", "", "", ""); 
    $resultado = $comentarioModel->actualizarComentario($idComentario, $nuevoComentario, $usuarioID, $nuevaValoracion);

    // Retornar la respuesta como JSON
    echo json_encode($resultado);
    exit(); // Asegúrate de salir aquí
} else {
    echo json_encode(['success' => false, 'message' => 'Método no permitido.']);
    exit(); // Asegúrate de salir aquí
}
?>
