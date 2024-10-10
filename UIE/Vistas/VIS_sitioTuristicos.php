<?php require_once("../Controlador/CON_IniciarSesion.php");?>

<script defer src="../Vistas/javascript/Favoritos.js"></script>
<script defer src="../Vistas/javascript/Ajax_MostrarComentarios.js"></script>
<link rel="stylesheet" href="../Vistas/estilos/comentarios.css">

<?php foreach ($sitios as $sitio):?>
    
<div class="tarjeta-turistica card" 
    data-bs-toggle="modal" 
    data-sitio-id="<?= $sitio['id_sitio'] ?>" 
    data-nombre-sitio="<?= $sitio['nombre'] ?>"  
    data-categoria="<?= $sitio['titulo'] ?>"
    data-bs-target="#modal<?= $sitio['id_sitio'] ?>" 
    onclick="cargarMapaDesdeTarjeta(this); cargarComentario(this.dataset.sitioId);">

<img src="<?= 'data:image/jpeg;base64,' . base64_encode($sitio['bin_imagen']) ?>" alt="Imagen de destino" class="card-img-top">
    <div class="contenido-tarjeta">
        <h5 class="titulo-lugar"><?= $sitio['nombre'] ?></h5>
        <p class="etiquetas-lugar"><?= $sitio['titulo'] ?></p>
        <p class="descripcion-lugar"><?= $sitio['descripcion'] ?></p>
        <div class="valoracion">
            <span class="estrella">&#9733;</span> <!-- Estrella llena -->
            <span class="estrella">&#9733;</span> <!-- Estrella llena -->
            <span class="estrella">&#9733;</span> <!-- Estrella llena -->
            <span class="estrella">&#9734;</span> <!-- Estrella vacía -->
            <span class="estrella">&#9734;</span> <!-- Estrella vacía -->
        </div>
    </div>
</div>

<!-- Modal para tarjeta -->
<div class="modal fade" id="modal<?= $sitio['id_sitio'] ?>" tabindex="-1" aria-labelledby="exampleModalLabel<?= $sitio['id_sitio'] ?>" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header border border-0">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body d-flex flex-column">
                <img src="<?= 'data:image/jpeg;base64,' . base64_encode($sitio['bin_imagen']) ?>" alt="Imagen del lugar" class="img-fluid">
                <div class="mt-3 d-flex align-content-start flex-wrap justify-content-between">
                    <div>
                        <h3 class="ms-2 modal-title" id="exampleModalLabel<?= $sitio['id_sitio'] ?>"><?= $sitio['nombre'] ?></h3>
                        <div class="valoracion mx-2">
                            <span class="estrella">&#9733;</span>
                            <span class="estrella">&#9733;</span>
                            <span class="estrella">&#9733;</span>
                            <span class="estrella">&#9734;</span>
                            <span class="estrella">&#9734;</span>
                            <span class="text-secondary">3,0</span>
                        </div>
                    </div>

                    <?php if (isset($_SESSION['usuario']) && $_SESSION['usuario']) { ?>

                        <form method="POST" class="d-flex flex-column fav-form" data-postid="<?= $sitio['id_sitio'] ?>">
                                
                            <input type="hidden" name="id_sitio" value="<?= $sitio['id_sitio'] ?>">

                            <?php

                            if(SitioTuristico::VerificarSitioFavorito($sitio['id_sitio'], $_SESSION['id'])){ 
                                
                                echo '<button type="submit" data-fav-btn'.$sitio["id_sitio"].' class="btn btn-outline-danger rounded favorito-activo">
                                Eliminar de favoritos <i class="bi bi-heart-fill"></i>
                                </button>'; 
                                
                            }else{
                                
                                echo '<button type="submit" data-fav-btn'.$sitio["id_sitio"].' class="btn btn-outline-danger rounded">
                                Guardar en favoritos <i class="bi bi-heart-fill"></i>
                                </button>'; 
                                
                            }

                            ?>
                            
                        </form>

                    <?php } ?>

                </div>
                <div>
                    <p class="categoria-lugar"><?= $sitio['titulo'] ?></p>
                </div>
                <p class="ms-2"><?= $sitio['descripcion'] ?></p>
                <p class="ms-2">Direccion</p>
            </div>

            <div id="seccion-comentarios-<?= $sitio['id_sitio'] ?>" class="w-100">
                <div class="w-100 p-3 mx-auto border-top">

                    <h3 class="text-center m-2">Opiniones</h3>

                    <?php if (isset($_SESSION['usuario']) && $_SESSION['usuario']) { ?>
                        <div class="row">
                            <div class="col-md-8 mx-auto">
                                <!-- Formulario para agregar comentarios -->
                                <form method="post" class="comentarios-form">
                                    <div class="form-group">
                                        <textarea class="form-control" name="descripcion" maxlength="255" rows="4" cols="50" placeholder="Publica tu opinión" data-inputpublicacion<?= $sitio['id_sitio'] ?> required></textarea>
                                    </div>
                                    <input type="hidden" name="id_sitio" value="<?= $sitio['id_sitio'] ?>"> <!-- Campo oculto para el id_sitio -->
                                    <div class="my-3 d-flex flex-row justify-content-between"> <!-- Alinea el botón a la derecha -->
                                        <p class="ms-3" data-contadorchar<?= $sitio['id_sitio'] ?>>Límite de caracteres: 0/255</p>
                                        <button type="submit" class="btn btn-primary">Publicar</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        
                    <?php } ?>

                    <div class="comentarios-container border-top">
                        <ul id="lista-comentarios-<?= $sitio['id_sitio'] ?>" class="list-unstyled"></ul>
                    </div>
                </div>
            </div>
        
        </div>
    </div>
</div>
<?php endforeach; ?>
