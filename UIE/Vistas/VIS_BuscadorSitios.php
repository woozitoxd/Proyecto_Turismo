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
                        echo '<li><a class="dropdown-item" href="#">Ver Perfil</a></li>';
                        echo '<li><a class="dropdown-item" href="../Vistas/SugerirSitio.PHP">Sugerir Nuevo Sitio</a></li>';
                        echo '<li><a class="dropdown-item" href="../controlador/CON_CerrarSesion.php">Cerrar Sesión</a></li>';
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