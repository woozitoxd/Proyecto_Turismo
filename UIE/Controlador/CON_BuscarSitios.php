<?php
// Archivo: CON_BuscarSitios.php
require_once '../Modelos/ModeloSitios.php'; // Asegúrate de tener un modelo para manejar los sitios turísticos.

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $query = trim($_POST['query']);

    if (!empty($query)) {
        // Realizar la consulta de búsqueda en la base de datos
        $sitios = buscarSitiosPorNombre($query); // Función que realiza la búsqueda en la base de datos

        // Enviar los resultados como JSON
        echo json_encode($sitios);
    } else {
        echo json_encode([]);
    }
}

function buscarSitiosPorNombre($nombre) {
    // Esta función debe conectarse a la base de datos y realizar la búsqueda.
    // Aquí hay un ejemplo simple (ajusta a tu lógica y estructura):
    
    // Asumiendo que tienes un objeto de conexión a base de datos $db
    global $db;
    $stmt = $db->prepare("SELECT id_sitio, nombre, titulo, descripcion, bin_imagen FROM sitios WHERE nombre LIKE ?");
    $stmt->execute(["%$nombre%"]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>
