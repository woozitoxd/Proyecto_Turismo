<?php
require_once('../Modelo/conexion_bbdd.php');

try {
    $queryString = "SELECT * FROM razon";
    $objQuery = $conn->prepare($queryString);
    $objQuery->execute();
    $razones = $objQuery->fetchAll(PDO::FETCH_ASSOC);

    // Asegúrate de que la respuesta sea JSON
    header('Content-Type: application/json');
    echo json_encode($razones);
} catch (PDOException $e) {
    // En caso de error, devuelve un mensaje JSON
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Error al obtener las razones: ' . $e->getMessage()]);
}
die();
?>
