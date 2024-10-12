<?php
// Incluir el archivo de conexión
require '../Modelo/conexion_bbdd.php';

// Verificar si se recibió el ID (opcional)
if (isset($_POST['id'])) {
    $sitioID = (int)$_POST['id']; // Convertir el ID a un entero para evitar inyecciones SQL

    // Preparar la consulta SQL para un sitio específico
    $stmt = $GLOBALS['conn']->prepare("SELECT id_sitio, latitud, longitud, descripcion FROM sitio_turistico WHERE id_sitio = :id");
    $stmt->bindParam(':id', $sitioID, PDO::PARAM_INT);
} else {
    // Preparar la consulta SQL para obtener todas las coordenadas, descripciones y IDs
    $stmt = $GLOBALS['conn']->prepare("SELECT id_sitio, latitud, longitud, descripcion FROM sitio_turistico");
}

// Ejecutar la consulta
if ($stmt->execute()) {
    // Obtener los resultados
    $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if ($resultados) {
        // Devolver todas las coordenadas, descripciones e IDs como un array JSON
        echo json_encode($resultados);
    } else {
        // Si no se encuentran resultados, devolver un error
        echo json_encode(['error' => 'No se encontraron sitios turísticos.']);
    }
} else {
    // Error en la ejecución de la consulta
    echo json_encode(['error' => 'Error en la ejecución de la consulta.']);
}
?>
