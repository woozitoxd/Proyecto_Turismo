<?php
include '../Modelo/conexion_bbdd.php';  // Asegúrate de incluir tu conexión a la base de datos

// Verifica que se recibió el ID del sitio turístico por POST
if (!isset($_POST['id']) || empty($_POST['id'])) {
    echo json_encode(['error' => 'No se recibió el ID del sitio.']);
    exit();
}

$id_sitio = $_POST['id'];  // Obtener el ID del sitio turístico

try {
    // Consulta para obtener los comentarios
    $stmt = $conn->prepare("CALL SP_ObtenerComentariosPorSitio(:id_sitio)"); //Uso el store procedure que me trae los datos de las tablas usuario, sitio y comentario que trabaja con la caja de comentario
    $stmt->bindParam(':id_sitio', $id_sitio, PDO::PARAM_INT);
    $stmt->execute();

    $comentarios = [];

    // Recorrer los resultados
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $comentarios[] = [
            'idUsuario' => $row['id_usuario'],
            'idComentario' => $row['id_comentario'],  //id del comentario
            'nombre' => $row['nombre_usuario'],  // Nombre del usuario
            'fechaPublicacion' => $row['fechaPublicacion'], //fecha del comentario
            'Comentario' => $row['comentario'],  //descripcion del comentario
            'valoracion' => $row['valoracion']  //valoracion (del 1 al 5) por ahora en deshuso
        ];
    }

    // Devolver los comentarios como JSON
    echo json_encode($comentarios);
} catch (PDOException $e) {
    // Manejar cualquier error en la consulta o conexión
    echo json_encode(['error' => $e->getMessage()]);
}
?>


