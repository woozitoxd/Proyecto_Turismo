<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./estilos/header.css">
    <link rel="stylesheet" href="./estilos/navbar.css">
    <link rel="stylesheet" href="./estilos/maps.css">
    <link rel="stylesheet" href="./estilos/cards.css">

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Bootstrap CSS más reciente -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">

    <!-- Bootstrap JS más reciente -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script src="../Vistas/javascript/Ajax_BuscarSitios.js"></script>
    <script defer src="../Vistas/javascript/Ajax_APIGoogleMaps.js"></script>
    <script defer src="../Vistas/javascript/ContenidoSeccion.js"></script>
    <script defer src="../Vistas/javascript/validaciones.js"></script>

    <title>Inicio - Turismo</title>
</head>

<body>

    <?php
                require_once '../Controlador/CON_SitioTuristico.php';
                $controlador = new SitioTuristicoContoller();
                $controlador->TraerCategorias();
    ?>
        
    <main> <!-- etiqueta main que contiene basicamente todo el cuerpo de la pagina, sepparandolo del nav y del footer -->
        <header class="bg-light text-center pt-5 mt-2 d-flex flex-row justify-content-end">
            <div id="section-title" class="w-50 text-primary fst-italic fs-3 fw-medium">
                Descubre nuevos sitios turísticos
            </div>
        </header>
    
        <!-- Estructura principal -->
        <div class="estructura-principal">
            <!-- Mapa (fijo a la izquierda) -->
            <div class="zona-mapa-izquierda">
                <div id="map"></div>
                <!--<script src="./javascript/maps.js"></script> API de google maps, mejorar para futuro -->
                <!--<script defer src="https://maps.googleapis.com/maps/api/js?key=&callback=iniciarmapa&v=weekly"></script>-->
                <script defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDGurFwiV-ORAOoQDOpQGFVNWSJopP2Vyg&callback=iniciarmapa&v=weekly"></script>

                </div>
            <!-- Cards de lugares turísticos (a la derecha) -->
            <div class="bloque-lugares align-content-start" id="contenedor-tarjetas">
            <?php
                require_once '../Controlador/CON_SitioTuristico.php';
                $controlador = new SitioTuristicoContoller();
                $controlador->MostrarSitiosTuristicos();
            ?>
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
                        <form action="../Controlador/CON_RegistroUsuario.php" method="post" id="formulario-registro">
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label for="userName" class="form-label">Nombre de Usuario</label>
                                    <input type="text" class="form-control" id="userName" name="userName" placeholder="Ingrese su username">
                                    <div class="invalid-feedback" id="errorUserName"></div>
                                </div>
                                <div class="mb-3">
                                    <label for="correo" class="form-label">Correo Electrónico</label>
                                    <input type="text" class="form-control" id="correo" placeholder="Ingrese su email" name="correo">
                                    <div class="invalid-feedback" id="errorCorreoRegistro"></div>
                                </div>
                                <div class="mb-3">
                                    <label for="registerPSW" class="form-label">Contraseña</label>
                                    <input type="password" class="form-control" id="registerPSW" placeholder="Ingrese su contraseña" name="registerPSW">
                                    <div class="invalid-feedback" id="errorRegisterPSW"></div>
                                </div>
                                <div class="mb-3">
                                    <label for="confirmarContraseña" class="form-label">Confirmar Contraseña</label>
                                    <input type="password" class="form-control" id="confirmarContraseña" placeholder="Confirme su contraseña" name="confirmarContraseña">
                                    <div class="invalid-feedback" id="errorConfirmarPSW"></div>
                                </div>
                                <div class="mb-3">
                                    <label for="fecha_Registro" class="form-label">Fecha de Nacimiento:</label>
                                    <input type="date" class="form-control" id="fecha_Registro" name="fecha_Registro">
                                    <div class="invalid-feedback" id="errorFechaNacimiento">Fecha de nacimiento inválida.</div>
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

            <!-- Modal de Éxito -->
            <!-- Modal de Éxito -->
            <div class="modal fade" id="modalExito" tabindex="-1" aria-labelledby="modalExitoLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header bg-success text-white"> <!-- Cambia 'bg-success' por el color que desees -->
                            <h5 class="modal-title" id="modalExitoLabel">Registro Exitoso</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            Usuario registrado con éxito!
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        </div>
                    </div>
                </div>
            </div>

            <!------------------------------------>
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
                        <form action="../Controlador/CON_IniciarSesion.php" method="post" id="formulario-login">
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label for="correo_login" class="form-label">Correo Electrónico</label>
                                    <input type="text" class="form-control" id="correo_login" placeholder="Ingrese su email"
                                        autocomplete="correo" name="correo_login">
                                    <div class="invalid-feedback" id="errorCorreo"></div><!-- div para el mensaje de error del correo -->
                                </div>
                                <div class="mb-3">
                                    <label for="contraseña_login" class="form-label">Contraseña</label>
                                    <input type="password" class="form-control" id="contraseña_login"
                                        placeholder="Ingrese su contraseña" name="contraseña_login">
                                    <div class="invalid-feedback" id="errorClave"></div> <!-- div para el mensaje de error del correo -->
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
    </main>

    <footer class="py-5" style="background: linear-gradient(to bottom, rgba(40, 115, 214, 0), rgba(44, 98, 216, 0.7));">
        <div class="container">
            <div class="row justify-content-between">
                <div class="col-md-2">
                    <strong class="text-primary">TURI</strong><span class="text-danger">SMO</span>
                    <p>© Proyecto Turismo 2024</p>
                </div>

                <div class="col-md-4">
                <h5 class="font-weight-bold mb-2">Contactanos</h5>
                    <p class="mb-4">SomosTuristas@gmail.com.ar</p>
                    <p class="mb-4">SomosTuristas@hotmail.com.ar</p>
                </div>

                <div class="col-md-2">
                
                    <ul class="list-unstyled">
                        <li class="mb-2">
                            <a href="#" class="text-decoration-none"><i class="fab fa-linkedin"></i>Linkedin</a>
                        </li>
                        <li class="mb-2">
                            <a href="#" class="text-decoration-none"><i class="fab fa-facebook"></i>Facebook</a>
                        </li>
                        <li class="mb-2">
                            <a href="#" class="text-decoration-none"><i class="fab fa-instagram"></i>Instagram</a>
                        </li>
                        <li class="mb-2">
                            <a href="#" class="text-decoration-none"><i class="fab fa-youtube"></i>Youtube</a>
                        </li>
                        <li class="mb-2">
                            <a href="#" class="text-decoration-none"><i class="fab fa-twitter"></i>Twitter</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </footer>
</body>
</html>