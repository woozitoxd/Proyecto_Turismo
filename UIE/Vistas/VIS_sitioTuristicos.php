<?php

if (isset($_SESSION['mensaje'])) {
    echo '<div class="alert alert-success">' . $_SESSION['mensaje'] . '</div>'; // Mensaje de éxito
    unset($_SESSION['mensaje']); // Limpiar el mensaje después de mostrarlo
}
?>
<script defer src="../Vistas/javascript/Favoritos.js"></script>
<script defer src="../Vistas/javascript/Ajax_MostrarComentarios.js"></script>
<script defer src="../Vistas//javascript/AJAX_EliminarComentario.js"></script>
<link rel="stylesheet" href="../Vistas/estilos/comentarios.css">

<?php $sitiosAgrupados = [];
$sitiosAgrupados = [];
foreach ($sitios as $sitio) {
    $idSitio = $sitio['id_sitio'];
    if (!isset($sitiosAgrupados[$idSitio])) {
        $sitiosAgrupados[$idSitio] = [
            'datos' => $sitio,
            'imagenes' => [],
            'etiquetas' => [],
        ];
    }
    if (!empty($sitio['bin_imagen']) && !in_array($sitio['bin_imagen'], $sitiosAgrupados[$idSitio]['imagenes'])) {
        $sitiosAgrupados[$idSitio]['imagenes'][] = $sitio['bin_imagen'];
    }
    if (!empty($sitio['etiqueta']) && !in_array($sitio['etiqueta'], $sitiosAgrupados[$idSitio]['etiquetas'])) {
        $sitiosAgrupados[$idSitio]['etiquetas'][] = $sitio['etiqueta'];
    }
}

?>
<?php foreach ($sitiosAgrupados as $sitio): ?>
    <?php
    $datosSitio = $sitio['datos'];
    $imagenes = $sitio['imagenes'];
    $ValoracionDeSitio = SitioTuristico::ObtenerValoracionPromedioSitio($datosSitio['id_sitio'])['valoracion_promedio'];
    ?>

    <div class="tarjeta-turistica card" data-sitio-id="<?= $datosSitio['id_sitio'] ?>"
        data-nombre-sitio="<?= $datosSitio['nombre'] ?>" data-categoria="<?= $datosSitio['titulo'] ?>"
        data-etiqueta="<?= $datosSitio['etiqueta'] ?>" data-descripcion-lugar="<?= $datosSitio['descripcion'] ?>"
        data-localidad="<?= $datosSitio['localidad'] ?>"
        onclick="cargarMapaDesdeTarjeta(this); cargarComentario(this.dataset.sitioId);">

        <img src="<?= 'data:image/jpeg;base64,' . base64_encode($imagenes[0]) ?>" alt="Imagen de destino"
            class="card-img-top">
        <div class="contenido-tarjeta">
            <h5 class="titulo-lugar"><?= $datosSitio['nombre'] ?></h5>
            <p class="categoria-lugar"><?= $datosSitio['titulo'] ?></p>

            <!-- Mostrar la etiqueta solo si existe -->
            <?php foreach ($sitio['etiquetas'] as $etiqueta): ?>
                <span class="etiqueta-lugar"><?= htmlspecialchars($etiqueta) ?></span>
            <?php endforeach; ?>
            <p class="descripcion-lugar"><?= $datosSitio['descripcion'] ?></p>

            <div class="valoracion d-flex flex-row mx-2 align-items-center">

                <?php
                for ($i = 1; $i <= 5; $i++) {
                    if ($i <= floor($ValoracionDeSitio)) {
                        ?><span class="star full"></span><?php
                    } else if ($i == ceil($ValoracionDeSitio) && $ValoracionDeSitio - floor($ValoracionDeSitio) > 0) {
                        ?><span class="star half"></span><?php
                    } else {
                        ?><span class="star"></span><?php
                    }
                }
                ?>

                <span class="text-secondary ms-2"><?php echo number_format($ValoracionDeSitio, 1) ?></span>

            </div>
        </div>
    </div>

    <!-- Modal para tarjeta -->
    <div class="modal fade" id="modal<?= $datosSitio['id_sitio'] ?>" tabindex="-1"
        aria-labelledby="exampleModalLabel<?= $datosSitio['id_sitio'] ?>" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header border border-0">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body d-flex flex-column">
                    <?php foreach ($imagenes as $index => $imagen): ?>

                        <img src="<?= 'data:image/jpeg;base64,' . base64_encode($imagen) ?>" class="img-fluid"
                            alt="Imagen del sitio">

                    <?php endforeach; ?>
                    <div class="mt-3 d-flex align-content-start flex-wrap justify-content-between">
                        <div>
                            <h3 class="ms-2 modal-title" id="exampleModalLabel<?= $datosSitio['id_sitio'] ?>">
                                <?= $datosSitio['nombre'] ?>
                            </h3>

                            <div class="valoracion d-flex flex-row mx-2 align-items-center">

                                <?php
                                for ($i = 1; $i <= 5; $i++) {
                                    if ($i <= floor($ValoracionDeSitio)) {
                                        ?><span class="star full"></span><?php
                                    } else if ($i == ceil($ValoracionDeSitio) && $ValoracionDeSitio - floor($ValoracionDeSitio) > 0) {
                                        ?><span class="star half"></span><?php
                                    } else {
                                        ?><span class="star"></span><?php
                                    }
                                }
                                ?>
                                <span class="text-secondary ms-2"><?php echo number_format($ValoracionDeSitio, 1) ?></span>

                            </div>
                        </div>

                        <?php if (isset($_SESSION['usuario']) && $_SESSION['usuario']) { ?>

                            <form method="POST" class="d-flex flex-column fav-form"
                                data-postid="<?= $datosSitioo['id_sitio'] ?>">

                                <input type="hidden" name="id_sitio" value="<?= $datosSitio['id_sitio'] ?>">

                                <?php

                                if (SitioTuristico::VerificarSitioFavorito($datosSitio['id_sitio'], $_SESSION['id'])) {

                                    echo '<button type="submit" data-fav-btn' . $datosSitio["id_sitio"] . ' class="btn btn-outline-danger rounded favorito-activo">
                                Eliminar de favoritos <i class="bi bi-heart-fill"></i>
                                </button>';

                                } else {

                                    echo '<button type="submit" data-fav-btn' . $datosSitio["id_sitio"] . ' class="btn btn-outline-danger rounded">
                                Guardar en favoritos <i class="bi bi-heart-fill"></i>
                                </button>';

                                }

                                ?>

                            </form>

                        <?php } ?>

                    </div>
                    <div>
                        <p class="categoria-lugar"><?= $datosSitio['titulo'] ?></p>
                        <?php foreach ($sitio['etiquetas'] as $etiqueta): ?>
                            <span class="etiqueta-lugar"><?= htmlspecialchars($etiqueta) ?></span>
                        <?php endforeach; ?>
                    </div>
                    <p class="ms-2"><?= $datosSitio['descripcion'] ?></p>
                    <p class="ms-2">Direccion: <?= $datosSitio['localidad'] ?></p>
                </div>

                <div id="seccion-comentarios-<?= $datosSitio['id_sitio'] ?>" class="w-100">
                    <div class="w-100 p-3 mx-auto border-top">

                        <h3 class="text-center m-2">Opiniones</h3>

                        <?php if (isset($_SESSION['usuario']) && $_SESSION['usuario']) { ?>
                            <div class="row">
                                <div class="col-md-8 mx-auto">
                                    <span class="text-danger" id="comment-error-msg<?= $datosSitio['id_sitio'] ?>"></span>
                                    <!-- Formulario para agregar comentarios -->
                                    <form method="post" class="comentarios-form">
                                        <div class="form-group">



                                            <textarea class="form-control border border-info-subtle" name="descripcion"
                                                maxlength="255" rows="4" cols="50" placeholder="¿Qué opinas sobre este sitio?"
                                                data-inputpublicacion<?= $datosSitio['id_sitio'] ?> required></textarea>
                                        </div>
                                        <input type="hidden" name="id_sitio" value="<?= $datosSitio['id_sitio'] ?>">
                                        <!-- Campo oculto para el id_sitio -->
                                        <div class="my-1 d-flex flex-row justify-content-between align-items-center">
                                            <div class="valoracion" data-value="0">
                                                <span class="estrella estrella-sitio<?= $datosSitio['id_sitio'] ?>"
                                                    data-value="1">&#9733;</span>
                                                <span class="estrella estrella-sitio<?= $datosSitio['id_sitio'] ?>"
                                                    data-value="2">&#9733;</span>
                                                <span class="estrella estrella-sitio<?= $datosSitio['id_sitio'] ?>"
                                                    data-value="3">&#9733;</span>
                                                <span class="estrella estrella-sitio<?= $datosSitio['id_sitio'] ?>"
                                                    data-value="4">&#9733;</span>
                                                <span class="estrella estrella-sitio<?= $datosSitio['id_sitio'] ?>"
                                                    data-value="5">&#9733;</span>
                                            </div>
                                            <input type="hidden" name="valoracion"
                                                class="valoracion-sitio<?= $datosSitio['id_sitio'] ?>" value="0">
                                            <p class="ms-3 m-0" data-contadorchar<?= $datosSitio['id_sitio'] ?>>Límite de
                                                caracteres:
                                                0/255</p>
                                        </div>
                                        <div class="d-flex justify-content-center">
                                            <button type="submit" class="btn btn-primary mb-3">Publicar mi opinión</button>
                                        </div>

                                        <script>
                                            var idUsuarioLogueado = <?php echo isset($_SESSION['id']) ? (int) $_SESSION['id'] : 'null'; ?>;
                                            console.log('ID Usuario Logueado:', idUsuarioLogueado);
                                        </script>
                                    </form>
                                </div>
                            </div>

                        <?php } ?>

                        <div class="comentarios-container border-top">
                            <ul id="lista-comentarios-<?= $datosSitio['id_sitio'] ?>" class="list-unstyled"></ul>
                            <?php
                            // Verificar si el comentario pertenece al usuario actual
                        
                            ?>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
<?php endforeach; ?>