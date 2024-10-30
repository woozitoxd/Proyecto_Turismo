<?php
//require_once('../Modelo/conexion_bbdd.php');
//require_once('../Controlador/CON_IniciarSesion.php');
require_once("../Modelo/MOD_Perfil.php");
//require_once('../controlador/CON_VerificarPermisos.php'); // averiguar lo de la logica de permisos.

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener los datos JSON de la solicitud
    $data = json_decode(file_get_contents('php://input'), true);
    
    $NombreUsuario = $data['NombreUsuario'] ?? null;
    $Email = $data['Email'] ?? null;
    $IDUsuario = $data['IDUsuario'] ?? null;

    // Validaciones de backend

    if (!preg_match('/[a-zA-Z]/', $NombreUsuario)) {
        echo json_encode(['success' => false, 'error' => 'El nombre de usuario debe contener al menos una letra.']);
        exit;
    }

    if (strlen($NombreUsuario) <= 0) {
        echo json_encode(['success' => false, 'error' => 'El nombre de usuario debe tener al menos 1 caracter.']);
        exit;
    }
    //$Email = 'matippepotmail.com'; //forzamos el error
    if (!filter_var($Email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['success' => false, 'error' => 'El correo electronico '.$Email.' no es valido. Error de formato']);
        exit;
    }

    if (!is_numeric($IDUsuario)) {
        echo json_encode(['success' => false, 'error' => 'ID de usuario no válido.']);
        exit;
    }

    $modelo = new perfilUser();
    $resultado = $modelo->ActualizarPerfilUsuario($IDUsuario, $NombreUsuario, $Email);

    // Comprobación de éxito o error
    if ($resultado === true) {
        $_SESSION['usuario'] = $Email;
        $_SESSION['nombre'] = $NombreUsuario;
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Disculpe las molestias ocasionadas, error en la solicitud. ' . $resultado]);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Método no permitido']);
}
?>