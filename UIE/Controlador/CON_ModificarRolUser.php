<?php
use Google\Service\CloudSearch\Id;


require_once("../Modelo/MOD_Perfil.php");
session_start();


$usuarioID = null; 
if (isset($_SESSION['usuario']) && $_SESSION['usuario']){
    $usuarioID = $_SESSION['id'];
}

if (!Permisos::tienePermiso('Cambiar Rol', $usuarioID) || !Permisos::esRol('administrador', $usuarioID)) {
    echo json_encode(['success' => false, 'error' => 'Error, no posee el permiso para cambiar roles de un usuario.']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener los datos JSON de la solicitud
    $data = json_decode(file_get_contents('php://input'), true);
    
    $idUsuario = $data['idUsuario'] ?? null;
    $idRol = $data['idRol'] ?? null;

    // Validaciones
    if (!is_numeric($idUsuario) || !is_numeric($idRol)) {
        echo json_encode(['success' => false, 'error' => 'Datos inválidos.']);
        exit;
    }

    // Llamar al modelo
    $modelo = new perfilUser();
    $resultado = $modelo->CambiarRolUsuario($idUsuario, $idRol);

    if ($resultado === true) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => $resultado]);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Método no permitido.']);
}

?>
