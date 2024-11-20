<?php
require_once("../Modelo/MOD_ClaseUsuario.php"); //se incluye los archivos
require_once("../Modelo/MOD_perfil.php");
/* require_once("../Controlador/CON_IniciarSesion.php"); */
require_once("../Controlador/CON_GoogleAuthSesion.php");

// if (isset($client) && $client->getAccessToken()) {
//     // Obtener la información del usuario si está autenticado
//     $oauth2 = new Google_Service_Oauth2($client);
//     $userInfo = $oauth2->userinfo->get();
// }

// Comprobar si la sesión del usuario está iniciada y almacenar la información
$usuario_name = null;
$nombre_rol = null;
if (isset($_SESSION['usuario']) && isset($_SESSION['nombre']) && isset($_SESSION['nombre_rol'])) {
    $usuario_name = $_SESSION['nombre'];
    $nombre_rol = $_SESSION['nombre_rol'];
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

<nav id="header-main" class="navbar-color">
    <div class="container-fluid">
        <div class="row mt-4 justify-content-between">
            <div class="col-md-3 text-center order-element-1">
                <a class="navbar-brand fs-2" href="index.php">
                    <strong class="text-primary">TOURI</strong><span class="text-danger">SMO</span>
                </a>
            </div>

            <div class="col-md-<?php if(isset($_SESSION['usuario'])) echo '5'; else echo'7'; ?> d-flex flex-column justify-content-center order-element-2">
                <form class="d-flex ms-3 search-form justify-content-center" id="form-busqueda">
                    <input class="form-control custom-input" id="buscador" type="search" placeholder="Buscar">
                    <button class="btn custom-search-btn" type="submit"><i class="bi bi-trash3"></i></button>
                </form>

                <div class="d-flex w-75 justify-content-between align-self-center">
                    <div class="dropdown me-2">
                        <button class="btn custom-filter-btn dropdown-toggle w-100 filtro-categoria" type="button"
                            id="dropdownCategorias" data-bs-toggle="dropdown"> <!-- Cambié el id -->
                            Categorías
                        </button>
                        <ul class="dropdown-menu" style="max-height: 200px; overflow-y: auto;">
                            <?php foreach ($categorias as $categoria): ?>
                                <li><a class="dropdown-item filtro filtro-categoria"
                                        data-filtro="<?= $categoria['titulo'] ?>" href="#"><?= $categoria['titulo'] ?></a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                    <div class="dropdown me-2 ">
                        <button class="btn custom-filter-btn dropdown-toggle w-100 filtro-etiqueta" type="button"
                            id="dropdownEtiquetas" data-bs-toggle="dropdown"> <!-- Cambié el id -->
                            Etiquetas
                        </button>
                        <ul class="dropdown-menu" style="max-height: 200px; overflow-y: auto;">
                            <?php foreach ($etiquetas as $etiqueta): ?>
                                <li><a class="dropdown-item filtro filtro-etiqueta" data-filtro="<?= $etiqueta['titulo'] ?>"
                                        href="#"><?= $etiqueta['titulo'] ?></a></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                    <div class="dropdown">
                        <button class="btn custom-filter-btn dropdown-toggle w-100 filtro-localidad " type="button"
                            id="dropdownLocalidad" data-bs-toggle="dropdown"> <!-- Cambié el id -->
                            Localidad
                        </button>
                        <ul class="dropdown-menu" style="max-height: 200px; overflow-y: auto;">
                            <?php foreach ($localidades as $localidad): ?>
                                <li><a class="dropdown-item filtro filtro-localidad"
                                        data-filtro="<?= $localidad['nombre'] ?>" href="#"><?= $localidad['nombre'] ?></a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="col-md-<?php if(isset($_SESSION['usuario'])) echo '4'; else echo'2'; ?> order-element-3">
                <nav class="navbar navbar-expand-lg navbar-dark">
                    <div class="container-fluid justify-content-end">

                        <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar" aria-label="Toggle navigation">
                            <span class="navbar-toggler-icon"></span>
                        </button>

                        <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasNavbar" aria-labelledby="offcanvasNavbarLabel">
                            <div class="offcanvas-body">
                                <ul id="nav-header" class="d-flex justify-content-around">
                                    <?php
                                        if (isset($_SESSION['usuario'])) {

                                            if (isset($_SESSION['nombre_rol']) && $_SESSION['nombre_rol'] === 'administrador') {
                                                echo '<li class="hidden-option"><a class="nav-link" href="VIS_PanelControlADM.php">Panel de Control</a></li>';
                                            }
                                            echo '<li class="hidden-option nav-link nav-decoracion navbar-nav fw-medium" data-bs-toggle="modal" data-bs-target="#modalPerfil">PERFIL</li>';
                                            echo '<li class="hidden-option nav-link nav-decoracion navbar-nav"><a class="nav-link" href="../Vistas/SugerirSitio.PHP">Sugerir Nuevo Sitio</a></li>';
                                            echo '<li class="nav-item nav-decoracion navbar-nav" data-bs-dismiss="offcanvas" aria-label="Close"><a class="nav-link link-seccion" href="#favoritos">Favoritos</a></li>';
                                            echo '<li class="nav-item nav-decoracion navbar-nav" data-bs-dismiss="offcanvas" aria-label="Close"><a class="nav-link link-seccion" href="#MisSitios">Mis sitios</a></li>';
                                            echo '<li class="hidden-option nav-link nav-decoracion navbar-nav"><a class="text-danger-emphasis nav-link" href="../controlador/CON_CerrarSesion.php">Cerrar Sesión</a></li>';
                                        }else{
                                            echo '<li class="hidden-option nav-item nav-decoracion navbar-nav fw-medium my-2" data-bs-toggle="modal" data-bs-target="#myModalInicio">INICIAR SESIÓN</li>';
                                            echo '<li class="hidden-option nav-item nav-decoracion navbar-nav fw-medium my-2" data-bs-toggle="modal" data-bs-target="#myModalRegistro">REGISTRARSE</li>';
                                            echo '<li class="hidden-option nav-item nav-decoracion navbar-nav my-2"><a class="nav-link" href="' . $authUrl . '"><img alt="Google Logo" src="./media/google_logo.webp" class="google-logo"> Ingresar con Google</a></li>';
                                        }
                                    ?>
                                    <div class="dropdown">
                                        <button class="btn custom-hamburger-btn dropdown-toggle fs-5 text-light" type="button" id="hamburgerMenu"
                                            data-bs-toggle="dropdown">
                                            <?php
                                            if (isset($_SESSION['usuario'])) {
                                                echo '<i class="bi bi-person-fill"></i> <strong class="text-light" id="NombreEnMenu">' . $_SESSION["nombre"] . '</strong>';
                                            } else {
                                                echo '<i class="bi bi-person-fill"></i> Cuenta ';
                                            }
                                            ?>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            <?php
                                            if (isset($_SESSION['usuario'])) {
                                                if (isset($_SESSION['nombre_rol']) && $_SESSION['nombre_rol'] === 'administrador') {
                                                    echo '<li><a class="dropdown-item" href="VIS_PanelControlADM.php">Panel de Control</a></li>';
                                                }
                                                echo '<li><a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#modalPerfil">Perfil</a></li>';
                                                echo '<li><a class="dropdown-item" href="../Vistas/SugerirSitio.PHP">Sugerir Nuevo Sitio</a></li>';
                                                echo '<li><hr class="dropdown-divider"></li>';
                                                echo '<li><a class="dropdown-item text-danger-emphasis" href="../controlador/CON_CerrarSesion.php">Cerrar Sesión</a></li>';
                                            } else {
                                                echo '<li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#myModalInicio">Iniciar Sesión</a></li>';
                                                echo '<li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#myModalRegistro">Registrarse</a></li>';
                                                echo '<li><a class="dropdown-item" href="' . $authUrl . '"><img alt="Google Logo" src="./media/google_logo.webp" class="google-logo"> Ingresar con Google</a></li>';
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
                        <input type="text" class="form-control" id="ContraseñaActual" name="ContraseñaActual">
                        <small class="text-danger" id="ContraseñaActualError"></small>
                    </div>
                    <div class="mb-3 col-lg-6">
                        <label for="NuevaContraseña" class="form-label">Nueva Contraseña</label>
                        <input type="text" class="form-control" id="NuevaContraseña" name="NuevaContraseña">
                        <small class="text-danger" id="NuevaContraseñaError"></small>
                    </div>
                    <div class="mb-3 col-lg-6">
                        <label for="ConfirmaciónNuevaContraseña" class="form-label">Confirmacion de Nueva
                            Contraseña</label>
                        <input type="text" class="form-control" id="ConfirmaciónNuevaContraseña"
                            name="ConfirmaciónNuevaContraseña">
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


<div class="modal fade custom-modal-position" id="resultModal" tabindex="-1" aria-labelledby="modalTitle"
    aria-hidden="true">
    <div class="modal-dialog modal-dialoggs">
        <div class="modal-content" id="modalContent">

            <div class="modal-body">
                <span id="modalIcon" class="me-2"></span>
                <span id="modalMessage"></span>
            </div>
        </div>
    </div>
</div>