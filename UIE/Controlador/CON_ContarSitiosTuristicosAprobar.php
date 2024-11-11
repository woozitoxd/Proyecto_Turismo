<?php
require_once '../Modelo/MOD_SitioTuristico.php'; 

header('Content-Type: application/json');

ini_set('display_errors', 0);
error_reporting(E_ALL);

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    try {
        // Llamar al método sin parámetros
        $cantidadPendientes = SitioTuristico::ContarSitiosPendientesAprobar();

        echo json_encode(['cantidad' => $cantidadPendientes]);
    } catch (Exception $e) {
        echo json_encode(['error' => true, 'message' => $e->getMessage()]);
    }
} else {
    echo json_encode(['error' => true, 'message' => 'Método no permitido']);
}

