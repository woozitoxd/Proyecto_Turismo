<?php
//require_once('../Modelo/conexion_bbdd.php');
//require_once('../controlador/CON_IniciarSesion.php');
//require_once('../controlador/CON_VerificarPermisos.php'); // averiguar lo de la logica de permisos.

use Google\Service\CloudSearch\Id;

require_once("../Modelo/MOD_Perfil.php");
session_start();


$usuarioID = null; 
if (isset($_SESSION['usuario']) && $_SESSION['usuario']){
    $usuarioID = $_SESSION['id'];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener los datos JSON de la solicitud
    $data = json_decode(file_get_contents('php://input'), true);
    
    $IDUsuario = $data['IDUsuario'] ?? null;
    if (!is_numeric($IDUsuario)) {
        echo json_encode(['success' => false, 'error' => 'ID de usuario no válido.']);
        exit;
    }

    $modelo = new perfilUser();
    $resultado = $modelo->DesbloquearCuenta($IDUsuario);
    // Comprobación de éxito o error
    if ($resultado === true) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => $resultado]);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Método no permitido']);
}
?>
