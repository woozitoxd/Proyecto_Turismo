<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

require_once("../Modelo/MOD_SitioTuristico.php");

$usuarioID = $_SESSION['id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recibir el objeto JSON enviado desde el cliente
    $nombreSitioTuristico = $_POST['NombreSitioTuristico'];
    $optionCategoriaValue = $_POST['OptionCategoriaValue'];
    $optionCategoriaTitulo = $_POST['OptionCategoriaTitulo'];
    $descripcionSitioTuristico = $_POST['DescripcionSitioTuristico'];
    $latitud = $_POST['latitud'];
    $longuitud = $_POST['longuitud'];
    $optionLocalidadValue = $_POST['OptionLocalidadValue'];
    $optionLocalidadTitulo = $_POST['OptionLocalidadTitulo'];
    $arancelamiento = $_POST['Arancelamiento'];
    //$Horarios = $_POST['Horarios'];//agregar input
    $Horarios = "de 9 a 12";
    date_default_timezone_set('America/Argentina/Buenos_Aires');
    $fechaHoraActual = date('Y-m-d H:i:s');
    

    $estado = 1;//ver lo de la logica de permitos de cada session, si es admin el sitio se publica directamente, osea estado en 1, si no lo es, el esta se pone en 0


// Verifica si existen etiquetas en el FormData
$etiquetas = [];
//$cantidadEtiquetas = 0;
foreach ($_POST['Etiquetas'] as $etiquett) {
    // Decodificar el JSON a un array asociativo
    $etiquettArray = json_decode($etiquett, true);

    // Verificamos si la decodificación fue exitosa antes de acceder a los valores
    if ($etiquettArray !== null) {
        $etiquetas[] = [
            'titulo' => $etiquettArray['titulo'],
            'id_etiqueta' => $etiquettArray['id_etiqueta']
        ];

    } else {
        echo "Error al decodificar el JSON";
    }
    //$cantidadEtiquetas += 1; 
}
//echo json_encode([print_r($etiquetas)]);

                /*if($etiquetas[0]['titulo']=='Caminatas'){
                    echo json_encode([print_r($cantidadEtiquetas)]);
                }*/
// Manejar cada archivo de imagen
/* $imagenes = [];
if (isset($_FILES['images']['tmp_name'])) {
    foreach ($_FILES['images']['tmp_name'] as $index => $tmpName) {
        $fileName = $_FILES['images']['name'][$index];
        $fileData = file_get_contents($tmpName);
        $imagenes[] = [
            'name' => $fileName,
            'data' => $fileData
        ];
    }
    //echo json_encode([print_r($imagenes)]);
} */

 // Procesar imágenes
$imagenes = [];
if (isset($_FILES['images']['tmp_name'])) {
    foreach ($_FILES['images']['tmp_name'] as $index => $tmpName) {
        $fileData = file_get_contents($tmpName);
        $imagenes[] = [
            'name' => $_FILES['images']['name'][$index],
            'data' => $fileData
        ];
    }
    //echo json_encode([print_r($imagenes)]);
}


//validaciones de back

if(!isset($usuarioID)){
    echo json_encode(['success' => false, 'error' => 'ID de session inexistente.']);
    exit();
}

$modelo = new SitioTuristico("","","","","","","");
//ignorar $resultado = $modelo->PublicarSitio($optionCategoriaValue, $optionLocalidadValue, $IDUsuario, $nombreSitioTuristico, $descripcionSitioTuristico, $fechaHoraActual, $arancelamiento, $latitud, $longuitud, $estado);
//$resultado = $modelo->PublicarSitio(todas los datos para insertar el sitio);



$resultado = $modelo->PublicarSitio(
    $optionCategoriaValue, $optionLocalidadValue, $usuarioID, $nombreSitioTuristico,
    $descripcionSitioTuristico, $fechaHoraActual, $arancelamiento, $latitud, $longuitud,
    $estado, $Horarios, $etiquetas, $imagenes
);

    // Respuesta de éxito
    if ($resultado === true) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => $resultado]);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Método no permitido']);
}
?>
