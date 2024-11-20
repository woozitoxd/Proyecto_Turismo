<?php
use Google\Service\CloudSearch\Id;

require_once("../Modelo/MOD_Perfil.php");
session_start();

// Verificar el método de solicitud
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $IDUsuario = $data['IDUsuario'] ?? null;
    $accion = $data['accion'] ?? null;

    // Validaciones
    if (!is_numeric($IDUsuario)) {
        echo json_encode(['success' => false, 'error' => 'ID de usuario no válido.']);
        exit;
    }
    if (!in_array($accion, ['bloquear', 'desbloquear'])) {
        echo json_encode(['success' => false, 'error' => 'Acción no válida.']);
        exit;
    }

    $modelo = new perfilUser();
    $resultado = false;

    // Llamar al método correspondiente
    if ($accion === 'bloquear') {
        $resultado = $modelo->BloquearCuenta($IDUsuario);
    } elseif ($accion === 'desbloquear') {
        $resultado = $modelo->DesbloquearCuenta($IDUsuario);
    }

    // Enviar respuesta al cliente
    if ($resultado === true) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => 'No se pudo cambiar el estado del usuario.']);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Método no permitido']);
}
?>
