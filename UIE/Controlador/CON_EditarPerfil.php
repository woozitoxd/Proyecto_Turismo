<?php
session_start(); // Inicia la sesión
//require_once('../Modelo/conexion_bbdd.php');
//require_once('../Controlador/CON_IniciarSesion.php');
require_once("../Modelo/MOD_Perfil.php");
//require_once('../controlador/CON_VerificarPermisos.php'); // averiguar lo de la logica de permisos.

$usuarioID = $_SESSION['id']; // Establezco el usuario ID con el ID de la sesión

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $NombreUsuario_Input = $_POST['NombreUsuario'] ?? null;
    $Email_Input = $_POST['Email'] ?? null;

    $UsernameSession = $_SESSION['nombre'] ?? null;
    $EmailSession = $_SESSION['usuario'] ?? null;

    // Validaciones de backend

    if ( ($NombreUsuario_Input == $UsernameSession) && ($Email_Input == $EmailSession)) {
        echo json_encode(['success' => false, 'error' => 'Debe cambiar aunque sea un campo!']);
        exit;
    }

    if (!preg_match('/[a-zA-Z]/', $NombreUsuario_Input)) {
        echo json_encode(['success' => false, 'error' => 'El nombre de usuario debe contener al menos una letra.']);
        exit;
    }

    if (strlen($NombreUsuario_Input) <= 0) {
        echo json_encode(['success' => false, 'error' => 'El nombre de usuario debe tener al menos 1 caracter.']);
        exit;
    }
    //$Email = 'matippepotmail.com'; //forzamos el error
    if (!filter_var($Email_Input, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['success' => false, 'error' => 'El correo electronico '.$Email_Input.' no es valido. Error de formato']);
        exit;
    }

    if (!is_numeric($usuarioID)) {
        echo json_encode(['success' => false, 'error' => 'ID de usuario no válido.']);
        exit;
    }

    $modelo = new perfilUser();
    $resultado = $modelo->ActualizarPerfilUsuario($usuarioID, $NombreUsuario_Input, $Email_Input);

    // Comprobación de éxito o error
    if ($resultado === true) {

        $_SESSION['usuario'] = $Email_Input;
        $_SESSION['nombre'] = $NombreUsuario_Input;

        echo json_encode([
            'success' => true,
            'usuario_actualizado' => $NombreUsuario_Input,
            'email_actualizado' => $Email_Input
        ]);
        
    } else {
        echo json_encode(['success' => false, 'error' => 'Disculpe las molestias ocasionadas, error en la solicitud. ' . $resultado]);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Método no permitido']);
}
?>
