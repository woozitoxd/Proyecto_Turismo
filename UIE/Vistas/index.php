<?php
    require_once("../Modelo/MOD_ClaseUsuario.php"); //se incluye los archivos
    require_once("../Modelo/MOD_perfil.php");
    require_once("../Controlador/CON_IniciarSesion.php");

    $usuario_id = null;

    if (isset($_SESSION['usuario']) && $_SESSION['usuario']){
        $usuario_id = $_SESSION['id']; // inicio de sesion, comprobacion de que la sesion haya sido iniciada
    }

?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./estilos/menu.css">
    <link rel="stylesheet" href="./estilos/header.css">
    <link rel="stylesheet" href="./estilos/navbar.css">
    <link rel="stylesheet" href="./estilos/maps.css">
    <link rel="stylesheet" href="./estilos/botonesNav.css">
    <link rel="stylesheet" href="./estilos/inputBuscar.css">
    <script defer src="./javascript/navbar.js"></script>
    <script defer src="./javascript/mapa.js"></script>
    <script defer src="./javascript/maps.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <title>Inicio - Turismo</title>
</head>
<body>

    <nav class="navbar navbar-expand-lg fixed-top custom-navbar">
        <div class="container">
            <div class="dropdown">
                <button class="btn custom-hamburger-btn dropdown-toggle" type="button" id="hamburgerMenu" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-bars"></i> MENÚ
                </button>
                <ul class="dropdown-menu dropdown-menu-end custom-dropdown" aria-labelledby="hamburgerMenu">
                    <li><a class="dropdown-item" href="#inicio">Inicio</a></li>
                    <li><a class="dropdown-item" href="#favoritos">Favoritos</a></li>
                    <?php //Fragmento de codigo PHP que trabaja con la muestra dinamica de botones en funcion del inicio de sesion
                    if (isset($_SESSION['usuario'])) {
                        // Usuario ha iniciado sesión, muestra "Ver Perfil" y "Cerrar Sesión"
                        echo '<li><a class="dropdown-item" href="#">Ver Perfil</a></li>';
                        echo '<li><a class="dropdown-item" href="../controlador/CON_CerrarSesion.php">Cerrar Sesión</a></li>';
                    } else {
                        // Usuario no ha iniciado sesión, muestra "Iniciar Sesión" y "Registrarse"
                        echo '<li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#myModalInicio">Iniciar Sesión</a></li>';
                        echo '<li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#myModalRegistro">Registrarse</a></li>';
                    }
                    ?>
                </ul>
            </div>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-between" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="#favoritos">Favoritos</a>
                    </li>
                </ul>
                <a class="navbar-brand" href="#">
                    <strong class="text-primary">TURI</strong><span class="text-danger">SMO</span>
                </a>
                <form class="d-flex ms-3 search-form" role="search">
                    <input class="form-control custom-input me-2" type="search" placeholder="Buscar" aria-label="Buscar">
                    <div class="dropdown ms-2">
                        <button class="btn custom-filter-btn dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                            FILTROS
                        </button>
                        <ul class="dropdown-menu custom-dropdown" aria-labelledby="dropdownMenuButton">
                            <li><a class="dropdown-item" href="#">Deportes</a></li>
                            <li><a class="dropdown-item" href="#">Paisajístico</a></li>
                            <li><a class="dropdown-item" href="#">Gastronómico</a></li>
                        </ul>
                    </div>
                    <button class="btn custom-search-btn" type="submit">BUSCAR</button>
                </form>
            </div>
        </div>
    </nav>
    
    
    
    <!-- Header con margen para no ser tapado por la navbar -->
    <header class="bg-light text-center py-5 mt-5">
        <div id="demoFont">BIENVENIDO A TURISMO</div>
        <p id="demoParrafoFont">Explora nuestras opciones y descubre nuevos destinos.</p>
    </header>


    <!-- Estructura principal -->
    <div class="estructura-principal">
        <!-- Mapa (fijo a la izquierda) -->
        <div class="zona-mapa-izquierda">
            <div id="map"></div>
            <script src="./javascript/maps.js"></script> <!-- API de google maps, mejorar para futuro -->
            <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBDaeWicvigtP9xPv919E-RNoxfvC-Hqik&callback=iniciarmapa"></script>
        </div>

        <!-- Cards de lugares turísticos (a la derecha) -->
        <div class="bloque-lugares">
            <!-- Primera tarjeta -->
            <div class="tarjeta-turistica">
                <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQEMyVXIyOLxdWLiC-oyvKW99nSX4hOum02_w&s" alt="Imagen de destino">
                <div class="contenido-tarjeta">
                    <h5 class="titulo-lugar">Coliseo Romano</h5>
                    <p class="descripcion-lugar">* Etiquetas *</p>
                </div>
            </div>

            <!-- Segunda tarjeta -->
            <div class="tarjeta-turistica">
                <img src="https://www.hola.com/imagenes/viajes/2014072572733/top-25-destinos-turisticos-mundo/0-281-707/a_Machu-Picch-a.jpg" alt="Imagen de destino">
                <div class="contenido-tarjeta">
                    <h5 class="titulo-lugar">Villa Fiorito</h5>
                    <p class="descripcion-lugar">* Etiquetas *</p>
                </div>
            </div>

            <!-- Segunda tarjeta -->
            <div class="tarjeta-turistica">
                <img src="https://www.hola.com/imagenes/viajes/2014072572733/top-25-destinos-turisticos-mundo/0-281-707/a_Machu-Picch-a.jpg" alt="Imagen de destino">
                <div class="contenido-tarjeta">
                    <h5 class="titulo-lugar">Municipio Ezeiza</h5>
                    <p class="descripcion-lugar">* Etiquetas *</p>
                </div>
            </div>


            <!-- Segunda tarjeta -->
            <div class="tarjeta-turistica">
                <img src="https://www.hola.com/imagenes/viajes/2014072572733/top-25-destinos-turisticos-mundo/0-281-707/a_Machu-Picch-a.jpg" alt="Imagen de destino">
                <div class="contenido-tarjeta">
                    <h5 class="titulo-lugar">Isidro Casanova</h5>
                    <p class="descripcion-lugar">* Etiquetas *</p>
                </div>
            </div>


            <!-- Segunda tarjeta -->
            <div class="tarjeta-turistica">
                <img src="https://www.hola.com/imagenes/viajes/2014072572733/top-25-destinos-turisticos-mundo/0-281-707/a_Machu-Picch-a.jpg" alt="Imagen de destino">
                <div class="contenido-tarjeta">
                    <h5 class="titulo-lugar">Sitio Ejemplo</h5>
                    <p class="descripcion-lugar">* Etiquetas *</p>
                </div>
            </div>

            <!-- Más tarjetas pueden ir aquí -->
        </div>
    </div>


        <!---------------------------->
        <!-- Modal Registro usuario -->
        <div class="modal fade" id="myModalRegistro" data-bs-backdrop="static">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <!-- Modal Header -->
                    <div class="modal-header bg-success text-white">
                        <h4 class="modal-title">Registro de Usuario</h4>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <!-- Modal Body -->
                    <form action="../Controlador/CON_RegistroUsuario.php" method="post">
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="userName" class="form-label">Nombre de Usuario</label>
                                <input type="text" class="form-control" id="userName" name="userName" required placeholder="Ingrese su username">
                            </div>
                            <div class="mb-3">
                                <label for="correo" class="form-label">Correo Electrónico</label>
                                <input type="email" class="form-control" id="correo" required placeholder="Ingrese su email" name="correo">
                            </div>
                            <div class="mb-3">
                                <label for="contraseña" class="form-label">Contraseña</label>
                                <input type="password" class="form-control" id="registerPSW" required placeholder="Ingrese su contraseña" name="registerPSW">
                            </div>
                            <div class="mb-3">
                                <label for="confirmarContraseña" class="form-label">Confirmar Contraseña</label>
                                <input type="password" class="form-control" id="confirmarContraseña" required placeholder="Confirme su contraseña" name="confirmarContraseña">
                            </div>
                            <div class="mb-3">
                                <label for="fecha_Registro">Fecha de Nacimiento:</label>
                                <input type="date" required class="form-control" id="fecha_Registro" name="fecha_Registro">
                                <div class="invalid-feedback">Fecha de nacimiento inválida.</div>
                            </div>
                        </div>
                        <!-- Modal Footer -->
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                            <button type="submit" class="btn btn-success">Registrarse</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        

        <!-- ---------------------------------->
        <!-- Inicio de sesion -->
        <div class="modal fade" id="myModalInicio" data-bs-backdrop="static">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <!-- Modal Header -->
                    <div class="modal-header bg-primary text-white">
                        <h4 class="modal-title">Iniciar Sesión</h4>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <!-- Modal Body -->
                    <form action="../Controlador/CON_IniciarSesion.php" method="post">
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="userName" class="form-label">Nombre de Usuario</label>
                                <input type="text" class="form-control" id="userName" name="userName" autocomplete="email_registro" required placeholder="Ingrese su username">
                            </div>
                            <div class="mb-3">
                                <label for="correo" class="form-label">Correo Electrónico</label>
                                <input type="email" class="form-control" id="correo" required placeholder="Ingrese su email" autocomplete="correo" name="correo">
                            </div>
                            <div class="mb-3">
                                <label for="contraseña" class="form-label">Contraseña</label>
                                <input type="password" class="form-control" id="contraseña" required placeholder="Ingrese su contraseña" name="contraseña">
                            </div>
                        </div>
                        <!-- Modal Footer -->
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                            <button type="submit" class="btn btn-primary">Enviar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
</body>
</html>