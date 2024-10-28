<?php
//require_once('../Modelo/conexion_bbdd.php');
require_once("../Modelo/MOD_Perfil.php");
//require_once('../controlador/CON_IniciarSesion.php');
//require_once('../controlador/CON_VerificarPermisos.php'); // averiguar lo de la logica de permisos.




if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener los datos JSON de la solicitud
    $data = json_decode(file_get_contents('php://input'), true);
    
    $ContraseñaActual = $data['ContraseñaActual'] ?? null;
    $NuevaContraseña = $data['NuevaContraseña'] ?? null;
    $ConfirmaciónNuevaContraseña = $data['ConfirmaciónNuevaContraseña'] ?? null;
    $IDUsuario = $data['IDUsuario'] ?? null;


    if ($ConfirmaciónNuevaContraseña != $NuevaContraseña) {
        echo json_encode(['success' => false, 'error' => 'Las contraseñas no coinciden']);
        exit;
    }

    if (strlen($NuevaContraseña) < 8) {
        echo json_encode(['success' => false, 'error' => 'La contraseña nueva debe tener al menos 8 caracteres.']);
        exit;
    }

    if (strlen($ContraseñaActual) < 8) {
        echo json_encode(['success' => false, 'error' => 'La contraseña actual debe tener al menos 8 caracteres.']);
        exit;
    }

    if (!is_numeric($IDUsuario)) {
        echo json_encode(['success' => false, 'error' => 'ID de usuario no válido.']);
        exit;
    }
    //$ContraseñaActual = '123';
    $email = 'matiaspaezws@gmail.com';

    $modelo = new perfilUser();
    //$resultado1 = $modelo->consultar($IDUsuario, $ContraseñaActual, $ContraseñaNueva);
    $resultado = $modelo->ActualizarContraseñaUsuario($IDUsuario, $ContraseñaActual, $NuevaContraseña);
    //$resultado = $modelo->consultar($email, $ContraseñaActual);
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
