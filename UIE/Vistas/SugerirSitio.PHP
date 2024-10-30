<?php
    require_once("../Modelo/MOD_ClaseUsuario.php"); //se incluye los archivos
    require_once("../Modelo/MOD_perfil.php");
    require_once("../Controlador/CON_IniciarSesion.php");
    require_once("../Controlador/CON_GoogleAuthSesion.php");

if (isset($client) && $client->getAccessToken()) {
        // Obtener la información del usuario si está autenticado
        $oauth2 = new Google_Service_Oauth2($client);
        $userInfo = $oauth2->userinfo->get();
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
    <link rel="stylesheet" href="./estilos/navbar.css">
    <link rel="stylesheet" href="./estilos/header.css">

    <!-- Bootstrap JS más reciente -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Bootstrap CSS más reciente -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <title>Sugerir Sitio - Turismo</title>
</head>
<body >

<?php
        require_once '../Controlador/CON_SitioTuristico.php';
        $controlador = new SitioTuristicoContoller();
        $controlador->TraerCategorias();
    ?>
        
    <div class="mt-4"></div>
    <form class="needs-validation mt-5" action="" method="get" id="form-">
        <div class="container-fluid mt-3 row ">

            <div class="mb-2 col-lg-6">
                <label class="h5 form-label" for="NombreSitioTuristico">Nombre de Sitio</label>
                <input class="form-control" type="text" name="NombreSitioTuristico" placeholder="Añade el nombre del Sitio Turistico!" aria-label=".form-control" id="NombreSitioTuristico" maxlength="100" required>
                
                <label for="SelectCategoria" class="h5 form-label mt-3 ">Categoria</label>
                    <div class="d-inline">
                        <p class="d-inline EstilosCategorias" id="CategoriaSeleccionada"> Selecciona una Categoria!</p><!---aca deberia traerlo del select -->
                    </div>
                <select id="SelectCategoria" class="form-select mt-2" onchange="actualizarCategoria()">
                    <option>Selecciona una Categoria!</option><!---aca deberia traerlo de la BDD-->
                    <option value="Categoria1" name="Categoria1">Categoria 1</option><!---aca deberia traerlo de la BDD-->
                    <option value="Categoria2" name="Categoria2">Categoria 2</option>
                </select>


            <label for="SelectEtiqueta" class="h5 form-label mt-3 ">Etiquetas</label> 
            <button type="button" onclick="AgregarSelect()" class="btn btn-outline-primary d-inline">+</button>
            <button type="button"  onclick="LimpiarEtiquetas()" class="btn btn-outline-danger d-inline">Limpiar Etiquetas</button>
                <div class="row" id="IDContenedorSelectEtiquetas">
                <!--<div class="col-6" id="IDContenedorSelectEtiquetas">
                    </div>
                    <div class="col-6" id="IDContenedorNombreEtiquetas">
                    </div>-->
                </div>  
            </div>

            <div class="mb-2 col-lg-6">
                <label for="Descripcion" class="h5 form-label">Descripción</label>
                <textarea class="form-control textarea-resize" id="Descripcion" name="Descripcion" placeholder="Añade una descripción al Sitios Turistico" required ></textarea>
            </div>

            <div class="mb-2 col-lg-6">
                <div class="mb-3">
                    <label  class=" h5 form-label">Imagenes de Sitio Turistico</label>
                    <input type="file" id="ImagenSitio" class="form-control" accept="image/*" multiple onchange="previewImages(event)">
                </div>
                <div class="mb-3">
                    <label class="h5 form-label">Imágenes seleccionadas</label>  
                    <div id="imagePreviewContainer" ></div>
                </div>
            </div>
            <!--<div class="mb-2 col-lg-6">
                <div class="mb-3">
                    <label class="h5 form-label">Imágenes seleccionadas</label>  
                    <div id="imagePreviewContainer" ></div>
                </div>
            </div>-->
            
            <div class="mb-2 col-lg-6">
                <div class="">
                <label for="Latitud" class="form-label h5">Latitud & Longitud</label>
                <div class="input-group mb-1">
                    <!-- Campo de Latitud: rango permitido -90 a 90, con 6 decimales -->
                    <input type="number" class="form-control" id="Latitud" placeholder="Latitud ej. -34.853546" name="Latitud" min="-90" max="90" step="0.000001" required>
                    <span class="input-group-text">&</span>
                    <!-- Campo de Longitud: rango permitido -180 a 180, con 6 decimales -->
                    <input type="number" class="form-control" id="Longitud" placeholder="Longitud ej. -58.523937" name="Longitud" min="-180" max="180" step="0.000001" required>
                </div>  
                    <p>MAPA</p>
                    <p id="coordenadas" class="alert alert-info" style="margin-top: 10px;">
                        Coordenadas: Latitud 0, Longitud 0
                    </p> <!-- Para mostrar las coordenadas -->
                    <input id="place_input" type="text" placeholder="Buscar un lugar" class="form-control" style="margin-bottom: 10px;">
                    <div id="map" style="height: 500px; width: 100%;"></div>
                    <script src="./javascript/AJAX_MAPinSugerirSitio.js" defer></script>
                    <script defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDGurFwiV-ORAOoQDOpQGFVNWSJopP2Vyg&callback=iniciarmapa&v=weekly&libraries=places"></script>
                </div>
            </div>
            <div class="mb-2 col-lg-6 form-check form-switch">
                <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckDefault">
                <label class="form-check-label" for="flexSwitchCheckDefault"> Es arancelado?</label>
            </div>

            <hr>
            
            
            <div class="position-relative mt-3 mb-3 p-2">
                <div class="position-absolute top-0 start-50 translate-middle">
                    <button type="submit" class="btn btn-outline-primary "  id="btn-ss" >Publicar</button>
                </div>
            </div>
            
        </div>
    </form>

    
</body>
</html>