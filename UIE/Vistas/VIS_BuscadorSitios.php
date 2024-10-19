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
    
    // Comprobar si la sesión del usuario está iniciada y almacenar la información
    $usuario_name = null;
    $nombre_rol = null;
    if (isset($_SESSION['usuario']) && isset($_SESSION['nombre']) && isset($_SESSION['nombre_rol'])) {
        $usuario_name = $_SESSION['nombre'];
        $nombre_rol = $_SESSION['nombre_rol'];
    }
?>
<nav class="navbar navbar-expand-lg fixed-top custom-navbar">
        <div class="container">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <!-- Botón de menú hamburguesa alineado a la izquierda, se despliegan los elementos y es responsive -->
            <div class="dropdown">
                <button class="btn custom-hamburger-btn dropdown-toggle" type="button" id="hamburgerMenu" data-bs-toggle="dropdown" aria-expanded="false">
                    <?php
                    if (isset($_SESSION['usuario'])) {
                        echo '<i class="bi bi-person-fill text-primary"></i> <strong class="text-primary">'.$_SESSION["nombre"].'</strong>';
                    } else {
                        echo '<i class="bi bi-person-fill"></i> CUENTA';
                    }
                    ?>
                </button>
                <ul class="dropdown-menu dropdown-menu-end custom-dropdown" aria-labelledby="hamburgerMenu">
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
            <!-- Logo turismo alineado al centro -->
            <a class="navbar-brand" href="index.php">
                <strong class="text-primary">TURI</strong><span class="text-danger">SMO</span>
            </a>

            <div class="collapse navbar-collapse" id="navbarNav"><!-- Resto de elementos del navbar -->
                <ul class="navbar-nav me-auto">
                    <?php
                    if (isset($_SESSION['usuario'])) {
                        echo '<li class="nav-item"><a class="nav-link" href="#favoritos">Favoritos</a></li>';
                        echo '<li class="nav-item"><a class="nav-link" href="#MisSitios">Mis sitios</a></li>';
                    }
                    ?>
                </ul>
                <form class="d-flex ms-3 search-form" role="search" id="form-busqueda">
                    <input class="form-control custom-input me-2" id="buscador" type="search" placeholder="Buscar" aria-label="Buscar">
                    <div class="dropdown ms-2">
                        <button class="btn custom-filter-btn dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                            Descubrir
                        </button>
                        <ul class="dropdown-menu custom-dropdown" aria-labelledby="dropdownMenuButton">
                        <?php foreach ($categorias as $categoria):?>
                            <li><a class="dropdown-item filtro" data-filtro="<?= $categoria['titulo'] ?>" href="#"><?= $categoria['titulo'] ?></a></li>
                        <?php endforeach; ?>
                        </ul>
                    </div>
                    <button class="btn custom-search-btn" type="submit">BUSCAR</button>
                </form>
            </div>
        </div>
    </nav>

<!-- Modal Perfil -->
<div class="modal fade " id="modalPerfil" tabindex="-1" aria-labelledby="modalPerfilLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">    
    <div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <h1 class="modal-title fs-5" id="modalPerfilLabel">Perfil</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

    <div class="modal-body container-fluid">
        <form id="FormPerifl" class="row">
            <div class="mb-3 col-lg-6">
                <label for="NombreUsuario" class="form-label">Nombre de Usuario</label>
                <input type="text" class="form-control" id="NombreUsuario" name="NombreUsuario" value="<?php echo $_SESSION['nombre']?>">
            </div>
            <div class="mb-3 col-lg-6">
                <label for="FechaNacimiento" class="form-label">Fecha de Nacimiento</label>
                <input type="date" id="FechaNacimiento" class="form-control input-readonly" name="FechaNacimiento" value="<?php echo $_SESSION['fecha_nacimiento']?>">
            </div>
            <div class="mb-3 col-12">
                <label for="Email" class="form-label">Correo Electrónico</label>
                <input type="text" class="form-control" id="Email" name="Email" value="<?php echo $_SESSION['usuario']?>">
            </div>
            <div class="mt-1 col-12 ">
                <label type="button" class="text-primary-emphasis" data-bs-toggle="modal" data-bs-target="#modalCambiarContraseña">Cambiar Contraseña</label>
            </div>
            <div class="mt-1 col-12 ">
                <label type="button" class="text-danger" data-bs-toggle="modal" data-bs-target="#modalEliminarCuenta">Eliminar Cuenta</label>
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
<div class="modal fade" id="modalCambiarContraseña" tabindex="-1" aria-labelledby="modalCambiarContraseñaLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
                    <h5 class="modal-title " id="modalCambiarContraseñaLabel">Cambiar Contraseña</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
    <div class="modal-body container-fluid">
        <form id="FormPerifl" class="row">
            <div class="mb-3 col-12">
                <label for="ContraseñaActual" class="form-label">Contraseña Actual</label>
                <input type="text" class="form-control" id="ContraseñaActual" name="ContraseñaActual">
            </div>
            <div class="mb-3 col-lg-6">
                <label for="NuevaContraseña" class="form-label">Nueva Contraseña</label>
                <input type="text" class="form-control" id="NuevaContraseña" name="NuevaContraseña">
            </div>
            <div class="mb-3 col-lg-6">
                <label for="ConfirmaciónNuevaContraseña" class="form-label">Confirmacion de Nueva Contraseña</label>
                <input type="text" class="form-control" id="ConfirmaciónNuevaContraseña" name="ConfirmaciónNuevaContraseña">
            </div>
            <!--- footer--->
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-target="#modalPerfil" data-bs-toggle="modal">Volver</button>
                <button type="button" class="btn btn-danger" id="IDBotonEliminarCuenta">Confirmar Cambios</button>
            </div>
        </form>
    </div>
    </div>
    </div>
</div>

<!-- Modal Cambiar Contraseña -->
<div class="modal fade" id="modalEliminarCuenta" tabindex="-1" aria-labelledby="modalEliminarCuentaLabel" aria-hidden="true">
    <div class="modal-dialog">
    <div class="modal-content bg-danger-subtle">
        <div class="modal-header">
            <h5 class="modal-title" id="modalEliminarCuentaLabel">Eliminar Cuenta</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
    <div class="modal-body container-fluid">
        <p class="h5">¿Estás seguro de que quieres eliminar tu cuenta? Esta acción no se puede deshacer.</p>
        <!--- footer--->
        <div class="modal-footer">
                <button type="button" class="btn btn-primary " data-bs-target="#modalPerfil" data-bs-toggle="modal">Volver</button>
                <button type="button" class="btn btn-danger" id="EliminarCuenta">Confirmar Cambios</button>
        </div>

    </div>
    </div>
    </div>
</div>