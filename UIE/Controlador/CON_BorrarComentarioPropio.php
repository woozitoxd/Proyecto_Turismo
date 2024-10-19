<?php
header('Content-Type: application/json'); // Asegura que siempre envíes JSON
require_once('../Modelo/MOD_Comentario.php');
require_once('../Controlador/CON_IniciarSesion.php');
require_once('../Controlador/CON_VerificarPermisos.php');

$usuarioID = $_SESSION['id'] ?? null; // Verifica si el ID de usuario existe

// Verificar que los datos necesarios estén presentes
if (isset($_POST['id_comentario']) && isset($usuarioID)) {
    
    $idComentario = $_POST['id_comentario'];

    // Opcional: puedes registrar el usuarioID para depuración
    error_log("ID del usuario: " . $usuarioID);

    $comentarioObj = new Comentarios("", "", "", "");
    $resultado = $comentarioObj->eliminarComentario($idComentario, $usuarioID);

    // Verificar si se eliminó el comentario
    if ($resultado) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => 'No se pudo eliminar el comentario.']);
    }
} else {
    // Enviar error si faltan datos o la sesión no es válida
    echo json_encode(['success' => false, 'error' => 'Datos faltantes o sesión expirada.']);
}
?>
