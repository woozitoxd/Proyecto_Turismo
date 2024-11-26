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
// Verifica si el usuario ha iniciado sesión
if (isset($_SESSION['id'])) {
    $usuarioID = $_SESSION['id']; // Aquí obtienes el ID del usuario desde la sesión
} else {
    // Si no hay una sesión activa, puedes redirigir al usuario a la página de inicio de sesión
    header("Location: index.php");
    exit();
}

require_once('../Controlador/CON_VerificarPermisos.php');
$esAdministrador = Permisos::esRol('administrador', $usuarioID);
?>
<!DOCTYPE html>
<html lang="es">
<?php


?>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Administrativo - Turismo</title>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
    <script defer src="./javascript/AJAX_TraerUsuarios.js"></script>
    <script defer src="../Vistas/javascript/AJAX_VerReportes.js"></script>
    <script src="./javascript/AJAX_AprobarSitioTuristico.js" defer></script>
    <script src="./javascript/AJAX_RechazarSitioTuristico.js" defer></script>
    <script src="./javascript/AJAX_ContarSitiosTuristicos.js" defer></script>
    <script src="./javascript/MostrarVistaPreviaAdmin.js" defer></script>
    <script src="./javascript/MostrarVistaEditarSitio.js" defer></script>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.0/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/habibmhamadi/multi-select-tag@3.1.0/dist/css/multi-select-tag.css">

    <link rel="stylesheet" href="./estilos/navbar.css">
    <link rel="stylesheet" href="./estilos/modalVistaPrevia.css">
    <link rel="stylesheet" href="./estilos/SugerirSitio.css">
    <script src="./javascript/EditarSitio.js" defer></script>
    <script src="./javascript/FormEditarSitio.js" defer></script>

</head>

<body data-url-base="<?php echo htmlspecialchars($urlVariable); ?>" data-IDUsuario="<?php echo $_SESSION['id']; ?>">
    <!-- barra de navegacion -->
    <nav class="navbar navbar-expand-lg navbar-color">
        <div class="container-fluid">
            <h2>Panel de Administración</h2>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="./index.php">Volver al Inicio</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <?php if ($esAdministrador): ?>
    <!-- Main Content -->
    <main>
        <div class="content">
            <h1>Bienvenido, Administrador</h1>
            <p>Panel de Control Administrativo de MateAR Caminos.</p>
            <div class="row">
                <div class="col-md-4">
                    <div class="card text-white bg-primary mb-3">
                        <div class="card-body">
                            <i class="bi bi-people" style="font-size:15rem;"></i>
                            <h5 class="card-title">Usuarios Activos</h5>
                            <p class="card-text">Gestión de usuarios registrados en la plataforma.</p>
                            <button class="btn btn-light" id="verDetallesBtn" data-bs-toggle="modal" data-bs-target="#usuariosModal">Ver Detalles</button>
                        </div>
                    </div>
                </div>


                <div class="modal fade" id="usuariosModal" tabindex="-1" aria-labelledby="usuariosModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
                        <!-- Modal amplio y centrado -->
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="usuariosModalLabel">Lista de Usuarios registrados en
                                    "TURISMO"</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="table-responsive"> <!-- Hace la tabla adaptativa sin scroll horizontal -->
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>Nombre</th>
                                                <th>Rol</th>
                                                <th>Email</th>
                                                <th>Estado</th>
                                                <th>Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody id="usuariosTabla"></tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                            </div>
                        </div>
                    </div>
                </div>



                <!-- --------- Modal para cambiar rol -------- -->

                <div class="modal fade" id="cambiarRolModal" tabindex="-1" aria-labelledby="cambiarRolModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="cambiarRolModalLabel">Cambiar Rol</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form id="cambiarRolForm">
                                    <input type="hidden" id="idUsuario" name="idUsuario">
                                    <div class="mb-3">
                                        <label for="nuevoRol" class="form-label">Selecciona un nuevo rol:</label>
                                        <select id="nuevoRol" class="form-select" name="nuevoRol" placeholder="Cambiar rol">
                                            <option value="1">Administrador</option>
                                            <option value="2">Usuario</option>
                                        </select>
                                    </div>
                                </form>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                <button type="button" class="btn btn-primary" onclick="guardarNuevoRol()">Guardar Cambios</button>
                            </div>
                        </div>
                    </div>
                </div>
             
           



                <!------------------------>
                <div class="col-md-4">
                    <div class="card text-white bg-success mb-3">
                        <div class="card-body">
                            <i class="bi bi-chat-left-dots" style="font-size:15rem"></i>
                            <h5 class="card-title">Comentarios</h5>
                            <p class="card-text">Administrar todos los reportes de comentarios de los usuarios.</p>
                            <button id="verDenunciasBtn" class="btn btn-light" data-bs-toggle="modal"
                                data-bs-target="#denunciasModal">Ver Denuncias</button>
                        </div>
                    </div>
                </div>
               
                <!-- Modal para mostrar denuncias -->
                <div class="modal fade" id="denunciasModal" tabindex="-1" aria-labelledby="denunciasModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="denunciasModalLabel">Denuncias realizadas hacia comentarios
                                </h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th scope="col">ID de Denuncia</th>
                                                <th scope="col">Razón</th>
                                                <th scope="col">Comentario</th>
                                                <th scope="col">Usuario Denunciante</th>
                                                <th scope="col">Observación</th>
                                                <th scope="col">Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody id="denunciasTableBody">
                                            <!-- Las denuncias se agregarán aquí mediante JavaScript -->
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                            </div>
                        </div>
                    </div>
                    <div id="mensajeExito" class="alert alert-success" style="display: none;"></div>
                </div>






                <div class="col-md-4">
                    <div class="card text-white bg-black mb-3">
                        <div class="card-body">
                            <i class="bi bi-globe-americas" style="font-size:15rem"></i>
                            <h5 class="card-title">Ver Sitios Sugeridos</h5>
                            <p class="card-text">Ver los destinos turísticos más solicitados.</p>
                            <p id="pendientesAprobacion" class="card-text"></p>
                            <button class="btn btn-light" data-bs-toggle="modal" data-bs-target="#sitiosModal">Ver
                                Detalles</button>
                        </div>
                    </div>
                </div>


                <?php
                require_once '../Controlador/CON_SitioTuristico.php';
                $controlador = new SitioTuristicoContoller();
                $controlador->MostrarSitiosParaAprobar();
                ?>
            </div>
        </div>
        <?php else: ?>
            <!-- Mostrar mensaje de error si no tiene permisos -->
            <div class="alert alert-danger text-center mt-5" role="alert">
                <h4 class="alert-heading">Acceso denegado</h4>
                <p>No tienes los permisos necesarios para acceder a esta página.</p>
            </div>
        <?php endif; ?>
    </main>
    <script src="https://cdn.jsdelivr.net/gh/habibmhamadi/multi-select-tag@3.1.0/dist/js/multi-select-tag.js"></script>

    <!-- Bootstrap JS -->
</body>

</html>