<?php
require_once('../Modelo/MOD_Comentario.php');
require_once('../Controlador/CON_IniciarSesion.php');
require_once('../Controlador/CON_VerificarPermisos.php');

$usuarioID = $_SESSION['id'];

if (!Permisos::tienePermiso('Denunciar Comentario', $usuarioID)) {
    echo json_encode(['error' => 'No tienes permiso para realizar esta acción.']);
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Quitar var_dump($_POST); 
    
    $idDenuncia = isset($_POST['listaRazones']) ? $_POST['listaRazones'] : null;
    $idComentario = isset($_POST['comentarioId']) ? $_POST['comentarioId'] : null;
    $observacion = isset($_POST['observacion']) ? $_POST['observacion'] : null;

    if (!$idDenuncia || !$idComentario || !$observacion) {
        echo json_encode(['error' => 'Faltan campos en la solicitud.']);
        exit();
    }

    $ComentObj = new Comentarios("", "", "", "");
    
    // Se reporta el comentario
    $resultado = $ComentObj->ReportarComentario($idDenuncia, $idComentario, $usuarioID, $observacion);

    if ($resultado === true) {
        // Enviar respuesta de éxito
        echo json_encode(['success' => true, 'message' => 'Denuncia enviada correctamente. Gracias por usar nuestro sistema de denuncias.']);
    } else {
        // Enviar respuesta de error
        echo json_encode(['error' => $resultado]); // Mostrar el error si no fue exitoso
    }
} else {
    echo json_encode(['error' => 'Método no permitido.']);
}
?>
