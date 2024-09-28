<?php foreach ($sitios as $sitio): ?>
<div class="tarjeta-turistica" data-bs-toggle="modal" data-bs-target="#modal1">
                <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQEMyVXIyOLxdWLiC-oyvKW99nSX4hOum02_w&s" alt="Imagen de destino">
                <div class="contenido-tarjeta">
                    <h5 class="titulo-lugar"> <?= $sitio['nombre'] ?></h5>
                    <p class="etiquetas-lugar"><?= $sitio['id_categoria'] ?></p>
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
<?php endforeach; ?>