<?php
require_once('../Modelo/MOD_ObtenerDenuncias.php'); // Modelo de denuncias

// Configuración de errores (para desarrollo)
error_reporting(E_ALL); 
ini_set('display_errors', 1);

// Inicia el buffer de salida
ob_start();

$denunciaObj = new Denuncias();
$denuncias = $denunciaObj->obtenerDenuncias();

// Envía el encabezado de JSON antes de cualquier salida
header('Content-Type: application/json');

// Verifica si el resultado es un array antes de codificar a JSON
if (is_array($denuncias)) {
    echo json_encode($denuncias);  // Devolvemos las denuncias en formato JSON
} else {
    echo json_encode(['error' => $denuncias]); // En caso de error, enviamos el mensaje de error
}

// Finaliza el buffer de salida y envía el contenido
ob_end_flush();
?>
