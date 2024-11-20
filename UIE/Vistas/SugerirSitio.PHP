<?php
    require_once("../Modelo/MOD_ClaseUsuario.php"); //se incluye los archivos
    require_once("../Modelo/MOD_perfil.php");
    require_once("../Controlador/CON_IniciarSesion.php");
    require_once("../Controlador/CON_GoogleAuthSesion.php");

if (!isset($_SESSION['id'])) {
    header("Location: index.php");
    exit;
}


if (isset($client) && $client->getAccessToken()) {
        // Obtener la información del usuario si está autenticado
        $oauth2 = new Google_Service_Oauth2($client);
        $userInfo = $oauth2->userinfo->get();
}
//seccion en la que obtenemos la url actual.
$scheme = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ? "https" : "http";      
$host = $_SERVER['HTTP_HOST'];
$requestUri = $_SERVER['REQUEST_URI'];
$currentUrl = $scheme . "://" . $host . $requestUri;
$indexPosition = strpos($currentUrl, 'Vistas');
$urlVariable = '';

if ($indexPosition !== false) {
    $urlVariable = substr($currentUrl, 0, $indexPosition + strlen('Vistas'));
} else {

    $indexPosition = strpos($currentUrl, 'Vistas/');
    if ($indexPosition !== false) {
        $urlVariable = substr($currentUrl, 0, $indexPosition + strlen('Vistas/'));
    } else {
        // Fallback: usar el esquema y host si no se encuentran patrones
        $urlVariable = $scheme . "://" . $host . '/';
    }
}

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./estilos/SugerirSitio.css">
    <script src="./javascript/AJAX_MAPinSugerirSitio.js" defer></script>
    <script src="./javascript/SugerirSitio.js" defer></script>
    <script src="./javascript/EditarPerfil.js" defer></script>
    <link rel="stylesheet" href="./estilos/navbar.css">
    <link rel="stylesheet" href="./estilos/header.css">
    <link rel="stylesheet" href="./estilos/cards.css">

    <!-- Bootstrap JS más reciente -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Bootstrap CSS más reciente -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/habibmhamadi/multi-select-tag@3.1.0/dist/css/multi-select-tag.css">

    <title>Sugerir Sitio - Turismo</title>
</head>
<body data-url-base="<?php echo htmlspecialchars($urlVariable); ?>" data-IDUsuario="<?php echo $_SESSION['id'] ?>">

<?php
        require_once '../Controlador/CON_SitioTuristico.php';
        $controlador = new SitioTuristicoContoller();
        $controlador->TraerCategorias();
    ?>
        

    <form class="mt-5" id="form-SugerirSitio" >
        <div class="container-fluid mt-3 row">
            <div class="mb-2 col-lg-6">
                <label class="h5 form-label" for="NombreSitioTuristico">Nombre de Sitio</label>
                <input class="form-control" type="text" name="NombreSitioTuristico" placeholder="Añade el nombre del Sitio Turistico!" aria-label=".form-control" id="NombreSitioTuristico" maxlength="100" required>
                <small class="text-danger" id="NombreSitioTuristicoError"></small>
                <div class="row">
                    <label for="SelectCategoria" class="h5 form-label mt-3 ">Categoria</label>
                    <div class="col-6">
                        <select id="SelectCategoria" class="form-select mt-2" onchange="actualizarCategoria()">
                            <option value=-1 disabled selected >Selecciona una Categoria!</option>
                        </select>
                    <small class="text-danger" id="SelectCategoriaError"></small>
                    </div>
                    <div class="col-6 d-flex justify-content-center">
                        <p class="categoria-lugar" id="CategoriaSeleccionada">-</p><!---aca deberia traerlo del select -->
                    </div>
                    
                </div>

                <div class="row">
                    <label for="SelectEtiqueta" class="h5 form-label mt-3 ">Etiquetas</label> 
                    <div class="col-9">
                        <select id="SelectEtiquetas" multiple >
                        </select>
                    </div>
                    <small class="text-danger" id="SelectEtiquetasError"></small>
                </div>

                <div class="mb-2 form-check form-switch mt-5 d-flex justify-content-center">
                        <div class="">
                            <input class="form-check-input h5 mb-1" type="checkbox" role="switch" id="flexSwitchCheckDefault" name="flexSwitchCheckDefault">
                            <label class="form-check-label h5 mb-1" for="flexSwitchCheckDefault">Es arancelado?</label>
                        </div>
                </div>
            </div>

            <div class="mb-2 col-lg-6 mt-3">
                <div class="mb-2">    
                    <label for="Descripcion" class="h5 form-label">Descripción</label>
                    <textarea class="form-control textarea-resize" id="Descripcion" name="Descripcion" placeholder="Añade una descripción al Sitios Turistico" required ></textarea>
                    <small class="text-danger" id="DescripcionError"></small>
                </div>

                <div class="mb-2">
                    <div class="d-flex align-items-center mt-3">
                        <label for="SelectHorarioApertura" class="h5 form-label mb-0 me-3">Horarios</label>
                        <!-- Checkbox para 24 horas -->
                        <div class="form-check form-switch">
                            <input class="form-check-input h6 mb-1" type="checkbox" role="switch" id="24HorasCheck" name="24HorasCheck" onchange="toggleHorarios(this)">
                            <label class="form-check-label h6 mb-1" for="24HorasCheck">24 Horas</label>
                        </div>
                    </div>

                    <!-- Contenedor de los selects -->
                    <div id="ContenedorSelectHorarios" class="row">
                        <div class="mt-3 col-6">
                            <label for="SelectHorarioApertura" class="form-label h6">Horario de Apertura</label>
                            <select id="SelectHorarioApertura" name="HorarioApertura" class="form-select">
                                <?php 
                                for ($hora = 0; $hora < 24; $hora++): 
                                    for ($minuto = 0; $minuto < 60; $minuto += 15): 
                                        $timeValue = str_pad($hora, 2, '0', STR_PAD_LEFT) . ':' . str_pad($minuto, 2, '0', STR_PAD_LEFT);
                                ?>
                                    <option value="<?php echo $timeValue; ?>"><?php echo $timeValue; ?></option>
                                <?php 
                                    endfor; 
                                endfor; 
                                ?>
                            </select>
                        </div>
                        <div class="mt-3 col-6">
                            <label for="SelectHorarioCierre" class="form-label h6">Horario de Cierre</label>
                            <select id="SelectHorarioCierre" name="HorarioCierre" class="form-select">
                                <?php 
                                for ($hora = 0; $hora < 24; $hora++): 
                                    for ($minuto = 0; $minuto < 60; $minuto += 15): 
                                        $timeValue = str_pad($hora, 2, '0', STR_PAD_LEFT) . ':' . str_pad($minuto, 2, '0', STR_PAD_LEFT);
                                ?>
                                    <option value="<?php echo $timeValue; ?>"><?php echo $timeValue; ?></option>
                                <?php 
                                    endfor; 
                                endfor; 
                                ?>
                            </select>
                        </div>
                        <small class="text-danger mt-1" id="HorariosError"></small>
                    </div>

                </div>
            </div>

            <div class="mb-2 col-lg-6 mt-3">
                <div class="mb-3">
                    <label  class=" h5 form-label">Imagenes de Sitio Turistico</label>
                    <input type="file" id="ImagenSitio" class="form-control" accept="image/*" multiple onchange="previewImages(event)">
                </div>
                <div class="mb-3">
                    <label class="h5 form-label">Imágenes seleccionadas</label>  
                    <small class="text-danger" id="ImagenSitioError"></small>

                    <div id="imagePreviewContainer" ></div>
                    <div id="thumbnailContainer" class="d-flex flex-wrap mt-3"></div>
                </div>
            </div>
            <div class="mb-2 col-lg-6 row">
                <label for="SelectLocalidad" class="h5 form-label mt-3">Localidad</label>
                <div class="col-6">
                    <select id="SelectLocalidad" class="form-select mt-2" >
                        <option value=-1 disabled selected >Selecciona una localidad!</option>
                    </select>
                    <small class="text-danger" id="SelectLocalidadError"></small>
                </div>

                <label  class="h5 form-label mt-4">MAPA</label>
                <input id="place_input" type="text" placeholder="Buscar un lugar" class="form-control" style="margin-bottom: 10px;">
                <div id="map" style="height: 500px; width: 100%;"></div>
                <small class="text-danger" id="mapError"></small>
                <p id="coordenadas" class="alert alert-info" style="margin-top: 10px;">
                    Seleccione un punto en el mapa!<p id="latitud" class="d-none"></p><p id="longuitud" class="d-none"></p><!-- Para mostrar las coordenadas -->
                <!--<script src="./javascript/AJAX_MAPinSugerirSitio.js" defer></script>--->
                <script defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDGurFwiV-ORAOoQDOpQGFVNWSJopP2Vyg&callback=iniciarmapa&v=weekly&libraries=places"></script>
            </div>

            <hr class="mt-4">
            
            <div class="position-relative mt-3 mb-3 p-2">
                <div class="position-absolute top-0 start-50 translate-middle">
                <button type="button" class="btn btn-outline-primary" id="btn-publicar" onclick="ModalVistaPreviaSitio()">Publicar</button>
                </div>
            </div>

        </div>
    </form>

<!-- Modal para tarjeta -->
<div class="modal fade" id="modalVistaPreviaSitio" tabindex="-1" aria-labelledby="modalVistaPreviaSitioLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <h3 class="modal-title fs-5" id="modalVistaPreviaSitioLabel">Vista Previa de Sitio</h3>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body row">

            <div class="mt-2" id="DIVCarrouselImagenesModal">
            </div>
            <h3 class="modal-title" id="IDNombreSitioModal">Nombre Sitio</h3>

            <div id="DivCategoriasYEtiquetasModal">
            </div>

            <div class="p-3 mt-0">
            <p class="descriptionModal" id="IDDescripcionSitioModal"> Descripcion </p>
            </div>
            <hr>
            <div class="col-lg-6">
                <p class="ms-2 descriptionModal" id="IDLocalidadSitioModal">Localidad: </p>
            </div>
            <div class="col-lg-6">
                <p class="ms-2 descriptionModal" id="IDArancelamientoSitioModal">Es arancelado: </p>
            </div>
            <div class="position-relative mt-3 mb-3 p-2">
                <div class="position-absolute top-0 start-50 translate-middle">
                    <p class="ms-2 descriptionModal" id="IDHorariosSitioModal">Horarios: </p>
                </div>
            </div>
            <br>
            <hr>
            <div class="d-flex justify-content-end">
                    <button type="button" class="btn btn-outline-primary me-2" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" onclick="btnPublicarSitio()">PUBLICAR!</button>
            </div>
        </div>
    </div>
    </div>
</div>




<div class="modal fade custom-modal-front" id="resultModal" tabindex="-1" aria-labelledby="modalTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialoggs">
        <div class="modal-content" id="modalContent">
            <div class="modal-body">
                <span id="modalIcon" class="me-2"></span>
                <span id="modalMessage"></span>
            </div>
        </div>
    </div>
</div>



    <script src="https://cdn.jsdelivr.net/gh/habibmhamadi/multi-select-tag@3.1.0/dist/js/multi-select-tag.js"></script>

</body>
</html>