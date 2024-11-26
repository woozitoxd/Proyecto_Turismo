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
    <link rel="stylesheet" href="./estilos/SugerirSitio.css">
    <link rel="stylesheet" href="./estilos/navbar.css">
    <link rel="stylesheet" href="./estilos/header.css">
    <script defer src="./javascript/AJAX_MensajesErrorDenuncia.js"></script>
    <script defer src="./javascript/AJAX_CargarRazonesDenuncia.js"></script>
    <script defer src="../Vistas/javascript/ContenidoSeccion.js"></script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css"> <!-- Iconos -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <!-- Bootstrap JS más reciente -->
    <script src="./javascript/AJAX_MAPinSugerirSitio.js" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Bootstrap CSS más reciente -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>
    <nav id="header-main" class="navbar-color">
        <div class="container-fluid">
            <div class="row mt-4 justify-content-center">
                <div class="text-center order-element-1">
                    <div class="navbar-brand fs-2">
                        <img src="./media/Logo-MateAR.svg" alt="Logo MateAR" class="img-fluid" style="width: 70px; height: auto;">
                        <strong style="color:#0078be;">MATE</strong><strong style="color:#fff;">AR</strong><strong style="color:#0078be;">CAMINOS</strong>
                    </div>
                </div>
            </div>
        </div>
    </nav>
    <main>
        <div class="container">
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
                            <textarea id="observacion" name="observacion" class="form-control" rows="3"></textarea>
                        </div>
                        <button type="submit" class="btn btn-danger">Denunciar</button>
                        <button type="button" class="btn btn-secondary" onclick="window.close();">Cancelar</button>
                        <button type="button" class="btn btn-primary" onclick="window.close();">Volver</button>


                    </form>
                </div>
            </div>
        </div>
        <div id="mensaje" class="alert" style="display:none;"></div>
    </main>
    
</body>
</html>

