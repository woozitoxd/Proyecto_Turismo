<?php
//require_once('../Modelo/conexion_bbdd.php');
require_once '../Modelo/MOD_SitioTuristico.php'; 
require_once('../controlador/CON_VerificarPermisos.php');

header('Content-Type: application/json');

// Capturar errores y evitar que interfieran en el JSON
ini_set('display_errors', 0);
error_reporting(E_ALL);


session_start();

$usuarioID = null; 
if (isset($_SESSION['usuario']) && $_SESSION['usuario']){
    $usuarioID = $_SESSION['id'];
}

if (!Permisos::esRol('administrador', $usuarioID)) {
    echo json_encode(['success' => false, 'error' => 'Credenciales requeridas.']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Decodificar la entrada JSON
    $data = json_decode(file_get_contents('php://input'), true);

    // Validar y obtener el id_sitio
    if (isset($data['id_sitio'])) {
        $id_sitio = $data['id_sitio'];

        try {
            // Llamar al método para aprobar el sitio
            $resultado = SitioTuristico::AprobarSitioTuristico($id_sitio);

            // Responder en JSON según el resultado
            echo json_encode(['success' => $resultado]);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'ID del sitio no proporcionado']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
}