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
    <link rel="stylesheet" href="./estilos/header.css">
    <link rel="stylesheet" href="./estilos/navbar.css">
    <link rel="stylesheet" href="./estilos/maps.css">
    <link rel="stylesheet" href="./estilos/cards.css">
    <script defer src="./javascript/Ajax_APIGoogleMaps.js"></script>
    <!-- jQuery (opcional, solo si lo necesitas) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Bootstrap JS más reciente -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Bootstrap CSS más reciente -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
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
    
    
    <main> <!-- etiqueta main que contiene basicamente todo el cuerpo de la pagina, sepparandolo del nav y del footer -->
        <header class="bg-light text-center py-5 mt-5">
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
                <!-- Los dato son hardocdeados de la base de datos, el "data-sitio-id = 1, se usa para traer las coordenadas, y onlick llama a la funcion que recarga el mapa con las coord-->
                <div class="tarjeta-turistica" data-bs-toggle="modal" data-sitio-id="1" data-bs-target="#modal1" onclick="cargarMapaDesdeTarjeta(this)">
                    <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQEMyVXIyOLxdWLiC-oyvKW99nSX4hOum02_w&s" alt="Imagen de destino">
                    <div class="contenido-tarjeta">
                        <h5 class="titulo-lugar">Coliseo Romano</h5>
                        <p class="etiquetas-lugar">Etiquetas</p>
                        <p class="descripcion-lugar">* Etiquetas *</p>
                        <div class="valoracion">
                            <span class="estrella">&#9733;</span> <!-- Estrella llena -->
                            <span class="estrella">&#9733;</span> <!-- Estrella llena -->
                            <span class="estrella">&#9733;</span> <!-- Estrella llena -->
                            <span class="estrella">&#9734;</span> <!-- Estrella vacía -->
                            <span class="estrella">&#9734;</span> <!-- Estrella vacía -->
                        </div>
                    </div>
                </div>
    
                <!-- Segunda tarjeta -->
                <div class="tarjeta-turistica">
                    <img src="https://www.hola.com/imagenes/viajes/2014072572733/top-25-destinos-turisticos-mundo/0-281-707/a_Machu-Picch-a.jpg" alt="Imagen de destino">
                    <div class="contenido-tarjeta">
                        <h5 class="titulo-lugar">Villa Fiorito</h5>
                        <p class="etiquetas-lugar">Etiquetas</p>
                        <p class="descripcion-lugar">* Etiquetas *</p>
                        <div class="valoracion">
                            <span class="estrella">&#9733;</span> <!-- Estrella llena -->
                            <span class="estrella">&#9733;</span> <!-- Estrella llena -->
                            <span class="estrella">&#9733;</span> <!-- Estrella llena -->
                            <span class="estrella">&#9734;</span> <!-- Estrella vacía -->
                            <span class="estrella">&#9734;</span> <!-- Estrella vacía -->
                        </div>
                    </div>
                </div>
    
                <!-- Segunda tarjeta -->
                <div class="tarjeta-turistica">
                    <img src="https://www.hola.com/imagenes/viajes/2014072572733/top-25-destinos-turisticos-mundo/0-281-707/a_Machu-Picch-a.jpg" alt="Imagen de destino">
                    <div class="contenido-tarjeta">
                        <h5 class="titulo-lugar">Municipio Ezeiza</h5>
                        <p class="etiquetas-lugar">Etiquetas</p>
                        <p class="descripcion-lugar">* Etiquetas *</p>
                        <div class="valoracion">
                            <span class="estrella">&#9733;</span> <!-- Estrella llena -->
                            <span class="estrella">&#9733;</span> <!-- Estrella llena -->
                            <span class="estrella">&#9733;</span> <!-- Estrella llena -->
                            <span class="estrella">&#9734;</span> <!-- Estrella vacía -->
                            <span class="estrella">&#9734;</span> <!-- Estrella vacía -->
                        </div>
                        
                    </div>
                </div>
    
    
                <!-- Segunda tarjeta -->
                <div class="tarjeta-turistica">
                    <img src="https://www.hola.com/imagenes/viajes/2014072572733/top-25-destinos-turisticos-mundo/0-281-707/a_Machu-Picch-a.jpg" alt="Imagen de destino">
                    <div class="contenido-tarjeta">
                        <h5 class="titulo-lugar">Isidro Casanova</h5>
                        <p class="etiquetas-lugar">Etiquetas</p>
                        <p class="descripcion-lugar">* Etiquetas *</p>
                        <div class="valoracion">
                            <span class="estrella">&#9733;</span> <!-- Estrella llena -->
                            <span class="estrella">&#9733;</span> <!-- Estrella llena -->
                            <span class="estrella">&#9733;</span> <!-- Estrella llena -->
                            <span class="estrella">&#9734;</span> <!-- Estrella vacía -->
                            <span class="estrella">&#9734;</span> <!-- Estrella vacía -->
                        </div>
                    </div>
                </div>
    
    
                <!-- Segunda tarjeta -->
                <div class="tarjeta-turistica">
                    <img src="https://www.hola.com/imagenes/viajes/2014072572733/top-25-destinos-turisticos-mundo/0-281-707/a_Machu-Picch-a.jpg" alt="Imagen de destino">
                    <div class="contenido-tarjeta">
                        <h5 class="titulo-lugar">Sitio Ejemplo</h5>
                        <p class="etiquetas-lugar">Etiquetas</p>
                        <p class="descripcion-lugar">* Etiquetas *</p>
                        <!-- Sección de valoración -->
                        <div class="valoracion">
                            <span class="estrella">&#9733;</span> <!-- Estrella llena -->
                            <span class="estrella">&#9733;</span> <!-- Estrella llena -->
                            <span class="estrella">&#9733;</span> <!-- Estrella llena -->
                            <span class="estrella">&#9734;</span> <!-- Estrella vacía -->
                            <span class="estrella">&#9734;</span> <!-- Estrella vacía -->
                        </div>
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


                <!--Modal para tarjeta-->
                <div class="modal fade" id="modal1" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Coliseo Romano</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQEMyVXIyOLxdWLiC-oyvKW99nSX4hOum02_w&s" alt="Imagen del Coliseo" class="card-img-top">
                            <div class="d-flex align-content-start flex-wrap"><p class="categoria-lugar">Categoria</p><p class="etiquetas-lugar">Etiquetas</p><p class="etiquetas-lugar">Etiquetas</p><p class="etiquetas-lugar">Etiquetas</p></div>
                            <p>El Coliseo es un anfiteatro de la época del Imperio romano, situado en el centro de la ciudad de Roma. Este lugar es uno de los sitios más turísticos del mundo.</p>
                        </div>
                        <div class="modal-footer">
                            <!-- Sección de valoración -->
                        <div class="valoracion">
                            <span class="estrella">&#9733;</span> <!-- Estrella llena -->
                            <span class="estrella">&#9733;</span> <!-- Estrella llena -->
                            <span class="estrella">&#9733;</span> <!-- Estrella llena -->
                            <span class="estrella">&#9734;</span> <!-- Estrella vacía -->
                            <span class="estrella">&#9734;</span> <!-- Estrella vacía -->
                        </div>
                        </div>
                        </div>
                    </div>
                </div>
    </main>

    <footer>
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <img src="https://img.freepik.com/vector-gratis/fondo-plano-viaje-vintage_23-2148189177.jpg?size=338&ext=jpg&ga=GA1.1.2008272138.1727308800&semt=ais_hybrid" alt="logoTurismo" class="img-fluid" style="max-width: 100px;"> 
                </div>
                <div class="col-md-6 text-md-end mt-4 mt-md-0">
                    <a href="#" target="_blank" class="text-white me-3"><i class="fab fa-twitter" style="font-size: 3.5rem;"></i></a>  
                    <a href="#" target="_blank" class="text-white me-3"><i class="fab fa-instagram" style="font-size: 3.5rem;"></i></a>
                    <a href="#" target="_blank" class="text-white"><i class="fab fa-facebook" style="font-size: 3.5rem;"></i></a>
                </div>
            </div>
        </div>
    </footer>
</body>
</html>