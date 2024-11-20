<?php
//require_once('../Modelo/conexion_bbdd.php');
require_once '../Modelo/MOD_SitioTuristico.php'; 

header('Content-Type: application/json');

// Capturar errores y evitar que interfieran en el JSON
ini_set('display_errors', 0);
error_reporting(E_ALL);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Decodificar la entrada JSON
    $data = json_decode(file_get_contents('php://input'), true);
    
    // Validar y obtener los parámetros necesarios
    if (isset($data['id_sitio']) && isset($data['nombre_sitio']) && isset($data['descripcion']) ) {
        $id_sitio = $data['id_sitio'];
        $nombre_sitio = $data['nombre_sitio'];
        $descripcion = $data['descripcion'];
        $categoria = $data['categoria'];

        try {
            // Llamar al método para editar el sitio
            $resultado = SitioTuristico::EditarSitio($id_sitio, $nombre_sitio, $descripcion, $categoria);

            // Responder en JSON según el resultado
            if ($resultado) {
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Error al editar el sitio']);
            }
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'ID del sitio o nombre no proporcionado']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
}
