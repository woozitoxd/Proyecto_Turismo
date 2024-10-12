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
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($resultado) {
            // Convertir el BLOB a base64
            $imagenBase64 = base64_encode($resultado['bin_imagen']);
            echo json_encode(['imagen' => $imagenBase64]);
        } else {
            echo json_encode(['error' => 'No se encontró imagen para este sitio.']);
        }
    } else {
        echo json_encode(['error' => 'Error en la ejecución de la consulta.']);
    }
} else {
    echo json_encode(['error' => 'ID no proporcionado.']);
}
?>
