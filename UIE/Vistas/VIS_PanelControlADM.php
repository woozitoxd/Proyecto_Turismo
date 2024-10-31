<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Administrativo - Turismo</title>
    <script defer src="../Vistas/javascript/AJAX_VerReportes.js"></script>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
        <!-- En la sección <head> de tu HTML -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Antes de cerrar el </body> -->
     <script defer src="./javascript/AJAX_TraerUsuarios.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
    <style>
        /* Estilos para el modal de denuncias */
        .modal-body {
            max-height: 400px; /* Altura máxima del cuerpo del modal */
            overflow-y: auto; /* Habilitar desplazamiento vertical */
        }
   

    </style>
</head>
<body>
    <!-- barra de navegacion -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Panel de Administración</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="./index.php">Inicio</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Perfil</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Salir</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main>
            <div class="content">
                <h1>Bienvenido, Administrador</h1>
                <p>Aquí puedes gestionar los usuarios, comentarios y destinos turísticos.</p>
                <div class="row">
                    <div class="col-md-4">
                        <div class="card text-white bg-primary mb-3">
                            <div class="card-body">
                                <h5 class="card-title">Usuarios Activos</h5>
                                <p class="card-text">Gestión de usuarios registrados en la plataforma.</p>
                                <button class="btn btn-light" id="verDetallesBtn">Ver Detalles</button>
                            </div>
                        </div>
                    </div>


                    <div class="modal fade" id="usuariosModal" tabindex="-1" aria-labelledby="usuariosModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable"> <!-- Modal amplio y centrado -->
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="usuariosModalLabel">Lista de Usuarios registrados en "TURISMO"</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="table-responsive"> <!-- Hace la tabla adaptativa sin scroll horizontal -->
                                        <table class="table table-hover">
                                            <thead>
                                                <tr>
                                                    <th>Nombre</th>
                                                    <th>Rol</th>
                                                    <th>Email</th>
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








                    
                    <!------------------------>
                    <div class="col-md-4">
                        <div class="card text-white bg-success mb-3">
                            <div class="card-body">
                                <h5 class="card-title">Comentarios</h5>
                                <p class="card-text">Administrar todos los reportes de comentarios de los usuarios.</p>
                                <button id="verDenunciasBtn" class="btn btn-light" data-bs-toggle="modal" data-bs-target="#denunciasModal">Ver Denuncias</button>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Modal para mostrar denuncias -->
                    <div class="modal fade" id="denunciasModal" tabindex="-1" aria-labelledby="denunciasModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="denunciasModalLabel">Denuncias realizadas hacia comentarios</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
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
                                <h5 class="card-title">Ver Sitios Sugeridos</h5>
                                <p class="card-text">Ver los destinos turísticos más solicitados.</p>
                                <button class="btn btn-light">Ver Detalles</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </main>

    <!-- Bootstrap JS -->
</body>
</html>
