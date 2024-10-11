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

$idComentario = $_GET['idComentario'] ?? null;

// Aquí podrías agregar lógica para verificar si el usuario está autenticado, etc.
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Denunciar Comentario</title>
    <link rel="stylesheet" href="./estilos/header.css">
    <link rel="stylesheet" href="./estilos/navbar.css">
    <script defer src="./javascript/AJAX_MensajeDenuncia.js"></script>
    <script defer src="./javascript/Ajax_CargarRazones.js"></script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css"> <!-- Iconos -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</head>
<body>

<hr>
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
                            if ($_SESSION['nombre_rol'] === 'administrador') {
                                echo '<li><a class="dropdown-item" href="panelControlADM.html">Panel de Control</a></li>';
                            }
                            echo '<li><a class="dropdown-item" href="#">Ver Perfil</a></li>';
                            echo '<li><a class="dropdown-item" href="/Proyecto_Turismo/SugerirSitio.php">Sugerir Nuevo Sitio</a></li>';
                            echo '<li><a class="dropdown-item" href="../controlador/CON_CerrarSesion.php">Cerrar Sesión</a></li>';
                        } else {
                            echo '<li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#myModalInicio">Iniciar Sesión</a></li>';
                            echo '<li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#myModalRegistro">Registrarse</a></li>';
                            echo '<li><a class="dropdown-item" href="'.$authUrl.'"><img alt="Google Logo" src="./media/google_logo.webp" class="google-logo">Ingresar con Google</a></li>';
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
            </div>
        </div>
    </nav>
        
    <main>
        <div class="container mt-5">
            <h1 class="text-center">Denunciar Comentario</h1>
            <div id="mensaje" class="alert" style="display:none;"></div>
            <div class="card">
                <div class="card-body">
                    <form id="denuncia-form" action="../Controlador/CON_ProcesarDenuncia.php" method="POST">
                        <input type="hidden" name="comentarioId" value="<?= htmlspecialchars($idComentario) ?>">
                        <div class="form-group">
                            <label for="razon">Razón de la denuncia:</label>
                            <select id="razon" name="listaRazones" class="form-control" required>
                                <option value="">Selecciona una razón</option>
                                <!-- Las razones se cargarán dinámicamente mediante AJAX -->
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="observacion">Observación:</label>
                            <textarea id="observacion" name="observacion" class="form-control" rows="3" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-danger">Denunciar</button>
                        <button type="button" class="btn btn-secondary" onclick="window.location.href='index.php';">Cancelar</button>
                        <button type="button" class="btn btn-primary" onclick="window.location.href='index.php';">Volver al inicio</button>

                    </form>
                </div>
            </div>
        </div>
        <div id="mensaje" class="alert" style="display:none;"></div>
    </main>
    
</body>
</html>

