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
        
    <nav id="header-main" class="navbar-color">

        <div class="container-fluid">

            <div class="row mt-4 justify-content-between">

                <div class="col-md-4 text-center order-element-1">
                    <a class="navbar-brand fs-2" href="index.php">
                        <img src="./media/Logo-MateAR.svg" alt="Logo MateAR" class="img-fluid" style="width: 70px; height: auto;">
                        <strong style="color:#0078be;">MATE</strong><strong style="color:#fff;">AR</strong><strong style="color:#0078be;">CAMINOS</strong>
                    </a>
                </div>

                <div class="col-md-3 order-element-3">
                    <nav class="navbar navbar-expand-lg navbar-dark">
                        <div class="container-fluid justify-content-end">

                            <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar" aria-label="Toggle navigation">
                                <span class="navbar-toggler-icon"></span>
                            </button>

                            <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasNavbar" aria-labelledby="offcanvasNavbarLabel">

                                <div class="offcanvas-header">
                                    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                                </div>
                                
                                <div class="offcanvas-body">
                                    <ul id="nav-header" class="d-flex justify-content-around">
                                        <?php
                                            if (isset($_SESSION['usuario'])) {

                                                if (isset($_SESSION['nombre_rol']) && $_SESSION['nombre_rol'] === 'administrador') {
                                                    echo '<li class="hidden-option"><a class="nav-link" href="VIS_PanelControlADM.php">Panel de Control</a></li>';
                                                }
                                                echo '<li class="hidden-option nav-link nav-decoracion navbar-nav fw-medium" data-bs-toggle="modal" data-bs-target="#modalPerfil">PERFIL</li>';
                                                echo '<li class="hidden-option nav-link nav-decoracion navbar-nav"><a class="text-danger-emphasis nav-link" href="../controlador/CON_CerrarSesion.php">Cerrar Sesión</a></li>';
                                            }
                                        ?>
                                        <div class="dropdown">
                                            <button class="btn custom-hamburger-btn dropdown-toggle fs-5 text-light d-flex align-items-center gap-2" 
                                                type="button" id="hamburgerMenu" data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="bi bi-person-fill"></i>
                                                <span>
                                                    <?php
                                                    if (isset($_SESSION['usuario'])) {
                                                        echo '<strong class="text-light" id="NombreEnMenu">' . $_SESSION["nombre"] . '</strong>';
                                                    } else {
                                                        echo 'Cuenta';
                                                    }
                                                    ?>
                                                </span>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end shadow-sm p-2">
                                                <?php
                                                if (isset($_SESSION['usuario'])) {
                                                    if (isset($_SESSION['nombre_rol']) && $_SESSION['nombre_rol'] === 'administrador') {
                                                        echo '<li><a class="nav-link px-3 py-2 d-flex align-items-center gap-2" href="VIS_PanelControlADM.php">
                                                                <i class="bi bi-tools"></i>Panel de Control
                                                            </a></li>';
                                                    }
                                                    echo '<li><a class="nav-link px-3 py-2 d-flex align-items-center gap-2" role="button" data-bs-toggle="modal" data-bs-target="#modalPerfil">
                                                            <i class="bi bi-person-circle"></i>Perfil
                                                        </a></li>';
                                                    echo '<li><hr class="dropdown-divider"></li>';
                                                    echo '<li><a class="nav-link px-3 py-2 d-flex align-items-center gap-2 text-danger" href="../controlador/CON_CerrarSesion.php">
                                                            <i class="bi bi-box-arrow-right"></i>Cerrar Sesión
                                                        </a></li>';
                                                }
                                                ?>
                                            </ul>
                                        </div>

                                    </ul>
                                </div>
                            </div>
                        </div>
                    </nav>
                    
                </div>

            </div>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

        </div>
    </nav>

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

<!-- Modal Perfil -->
<div class="modal fade" id="modalPerfil" tabindex="-1" aria-labelledby="modalPerfilLabel" aria-hidden="true"
    data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="modalPerfilLabel">Perfil</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body container-fluid">
                <form id="FormPerifl" class="row" data-url-base="<?php echo htmlspecialchars($urlVariable); ?>">
                    <div class="mb-3 col-lg-6">
                        <label for="NombreUsuario" class="form-label">Nombre de Usuario</label>
                        <input type="text" class="form-control" id="NombreUsuario" name="NombreUsuario"
                            value="<?php echo $_SESSION['nombre'] ?>">
                        <small class="text-danger" id="NombreCompletoError"></small>
                    </div>
                    <div class="mb-3 col-lg-6">
                        <label for="FechaNacimiento" class="form-label">Fecha de Nacimiento</label>
                        <input type="date" id="FechaNacimiento" class="form-control input-dark" name="FechaNacimiento"
                            value="<?php echo $_SESSION['fecha_nacimiento'] ?>" readonly>
                    </div>
                    <div class="mb-3 col-12">
                        <label for="Email" class="form-label">Correo Electrónico</label>
                        <input type="text" class="form-control" id="Email" name="Email"
                            value="<?php echo $_SESSION['usuario'] ?>">
                        <small class="text-danger" id="EmailError"></small>
                    </div>
                    <div class="mt-1 col-12 ">
                        <label type="button" class="text-primary-emphasis" data-bs-toggle="modal"
                            data-bs-target="#modalCambiarContraseña">Cambiar Contraseña</label>
                    </div>
                    <div class="mt-1 col-12 ">
                        <label type="button" class="text-danger" data-bs-toggle="modal"
                            data-bs-target="#modalEliminarCuenta">Eliminar Cuenta</label>
                    </div>

                    <!--- footer--->
                    <div class="modal-footer mt-2">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Cambiar Contraseña -->
<div class="modal fade" id="modalCambiarContraseña" tabindex="-1" aria-labelledby="modalCambiarContraseñaLabel"
    data-bs-backdrop="static" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title " id="modalCambiarContraseñaLabel">Cambiar Contraseña</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body container-fluid">
                <form id="FormCambiarContraseña" class="row"
                    data-url-base="<?php echo htmlspecialchars($urlVariable); ?>"
                    data-IDUsuario="<?php echo $_SESSION['id'] ?>">
                    <div class="mb-3 col-12">
                        <label for="ContraseñaActual" class="form-label">Contraseña Actual</label>
                        <div class="input-group">
                            <input type="password" class="form-control" id="ContraseñaActual" name="ContraseñaActual" placeholder="">
                            <button class="btn btn-outline-secondary" type="button" onclick="togglePasswordVisibility('ContraseñaActual', this)">
                                <i class="bi bi-eye-slash-fill"></i>
                            </button>
                        </div>
                        <small class="text-danger" id="ContraseñaActualError"></small>
                    </div>

                    <div class="mb-3 col-lg-6">
                        <label for="NuevaContraseña" class="form-label">Nueva Contraseña</label>
                        <div class="input-group">
                            <input type="password" class="form-control" id="NuevaContraseña" name="NuevaContraseña" placeholder="">
                            <button class="btn btn-outline-secondary" type="button" onclick="togglePasswordVisibility('NuevaContraseña', this)">
                                <i class="bi bi-eye-slash-fill"></i>
                            </button>
                        </div>
                        <small class="text-danger" id="NuevaContraseñaError"></small>
                    </div>

                    <div class="mb-3 col-lg-6">
                        <label for="ConfirmaciónNuevaContraseña" class="form-label">Confirmación de Nueva Contraseña</label>
                        <div class="input-group">
                            <input type="password" class="form-control" id="ConfirmaciónNuevaContraseña" name="ConfirmaciónNuevaContraseña" placeholder="">
                            <button class="btn btn-outline-secondary" type="button" onclick="togglePasswordVisibility('ConfirmaciónNuevaContraseña', this)">
                                <i class="bi bi-eye-slash-fill"></i>
                            </button>
                        </div>
                        <small class="text-danger" id="ConfirmaciónNuevaContraseñaError"></small>
                    </div>

                    <!--- footer--->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-target="#modalPerfil"
                            data-bs-toggle="modal">Volver</button>
                        <button type="submit" class="btn btn-danger" id="IDBotonEliminarCuenta">Confirmar
                            Cambios</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Eliminar cUENTA -->
<div class="modal fade" id="modalEliminarCuenta" tabindex="-1" aria-labelledby="modalEliminarCuentaLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content bg-danger-subtle">
            <div class="modal-header">
                <h5 class="modal-title" id="modalEliminarCuentaLabel">Eliminar Cuenta</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body container-fluid">
                <p class="h5">¿Estás seguro de que quieres eliminar tu cuenta? Esta acción no se puede deshacer.</p>
                <form id="formEliminarCuenta" class="row" data-url-base="<?php echo htmlspecialchars($urlVariable); ?>"
                    data-IDUsuario="<?php echo $_SESSION['id'] ?>">
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" data-bs-target="#modalPerfil"
                            data-bs-toggle="modal">Volver</button>
                        <button type="button" class="btn btn-danger" id="IDbtnEliminarCuenta">Confirmar "Eliminar
                            Cuenta"</button>
                    </div>
                </form>
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