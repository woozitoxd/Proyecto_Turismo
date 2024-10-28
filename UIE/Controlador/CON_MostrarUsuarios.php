<?php
session_start();
header('Content-Type: application/json');
require_once('../Modelo/MOD_ClaseUsuario.php'); 
require_once('../Controlador/CON_VerificarPermisos.php');

$usuarioID = $_SESSION['id'] ?? null;

if (!Permisos::tienePermiso('Ver Usuarios', $usuarioID)) {
    echo json_encode(['success' => false, 'error' => 'No tienes permiso para realizar esta acción.']);
    exit();
}

if ($usuarioID) {
    $usuarioModel = new Usuario('', '', '', '', ''); 

    $usuarios = $usuarioModel->getUsuarios();

    if ($usuarios) {
        echo json_encode(['success' => true, 'usuarios' => $usuarios]);
    } else {
        echo json_encode(['success' => false, 'error' => 'No se pudieron obtener los usuarios.']);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Sesión expirada o permisos insuficientes.']);
}
?>
