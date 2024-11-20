<?php
// Incluir el archivo de conexión
require '../Modelo/conexion_bbdd.php';

// Verificar si se recibió el ID
if (isset($_POST['id'])) {
    $idSitio = (int)$_POST['id'];

    // Preparar la consulta SQL para obtener la imagen del sitio
    $stmt = $GLOBALS['conn']->prepare("SELECT bin_imagen FROM Imagen WHERE id_sitio = :id");
    $stmt->bindParam(':id', $idSitio, PDO::PARAM_INT);

    // Ejecutar la consulta
    if ($stmt->execute()) {

        $listaDeSitios = [];

        while ($sitio = $stmt->fetch(PDO::FETCH_ASSOC)) {
            // Convertir la imagen en base64
            if (isset($sitio['bin_imagen'])) {
                $sitio['bin_imagen'] = base64_encode($sitio['bin_imagen']);
            }
            // Añadir la publicación al array de publicaciones
            $listaDeSitios[] = $sitio;
        }

        echo json_encode($listaDeSitios);


    } else {
        echo json_encode(['error' => 'Error en la ejecución de la consulta.']);
    }
} else {
    echo json_encode(['error' => 'ID no proporcionado.']);
}
?>
