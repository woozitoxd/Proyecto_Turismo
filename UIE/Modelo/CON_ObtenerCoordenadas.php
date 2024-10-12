<?php
// Incluir el archivo de conexión
require 'conexion_bbdd.php';


if (isset($_POST['id'])) {
    $sitioID = (int) $_POST['id']; // Asegúrate de convertir el ID a un entero para evitar inyecciones SQL
    
    // Preparar la consulta SQL
    $stmt = $GLOBALS['conn']->prepare("SELECT latitud, longitud FROM sitio_turistico WHERE id_sitio = :id");
    $stmt->bindParam(':id', $sitioID, PDO::PARAM_INT);
    
    // Ejecutar la consulta
    if ($stmt->execute()) {
        // Obtener los resultados
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($result) {
            // Si se encontró el sitio, devolver las coordenadas
            echo json_encode([
                'latitud' => $result['latitud'], //Se setean los valores de latitud y longitud que se trajeron de la consulta
                'longitud' => $result['longitud']
            ]);
        } else {
            // Si no se encuentra el sitio, devolver un error
            echo json_encode(['error' => 'Sitio turístico no encontrado.']);
        }
    } else {
        // Error en la ejecución de la consulta
        echo json_encode(['error' => 'Error en la ejecución de la consulta.']);
    }
} else {
    // Si no se recibe el ID, devolver un error
    echo json_encode(['error' => 'ID no proporcionado.']);
}
?>
