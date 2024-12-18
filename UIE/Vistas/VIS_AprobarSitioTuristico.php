<?php
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

<div class="modal fade" id="sitiosModal" tabindex="-1" aria-labelledby="sitiosModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="sitiosModalLabel">Destinos Turísticos Más Solicitados</h5>
                <button type="button" id="botonCerrarSitiosModal" class="btn-close" aria-label="Close"
                    data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th scope="col">Título</th>
                            <th scope="col">Descripción</th>
                            <th scope="col">Imagen</th>
                            <th scope="col">Etiqueta</th>
                            <th scope="col">Localidad</th>
                            <th scope="col">Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="sitiosTableBody">
                        <?php foreach ($sitiosAgrupados as $sitio): ?>
                            <?php
                            $datosSitio = $sitio['datos'];
                            $imagenes = $sitio['imagenes'];
                            ?>
                            <tr id="sitio-row-<?= $datosSitio['id_sitio'] ?>">
                                <td><?= htmlspecialchars($datosSitio['nombre']) ?></td>
                                <td><?= htmlspecialchars($datosSitio['descripcion'] ?? 'Sin descripción') ?></td>
                                <td>
                                <img src="<?= 'data:image/jpeg;base64,' . base64_encode(string: $imagenes[0]) ?>" alt="Imagen de destino"
                                style="width:60px;">
                                </td>
                                <td> <?php
                                // Mostrar las etiquetas del sitio agrupadas
                                if (!empty($datosSitio['etiquetas'])):
                                    foreach ($datosSitio['etiquetas'] as $etiqueta):
                                        ?>
                                            <span class="etiqueta-lugar"><?= htmlspecialchars($etiqueta) ?></span>
                                            <?php
                                    endforeach;
                                else:
                                    // Si no tiene etiquetas, mostrar "Sin etiquetas"
                                    echo '<span class="etiqueta-lugar">Sin etiquetas</span>';
                                endif;
                                ?>

                                </td>
                                <td><?= htmlspecialchars($datosSitio['localidad'] ?? 'Desconocida') ?></td>
                                <td>
                                    <button class="btn btn-secondary btn-sm" data-bs-toggle="modal"
                                        data-bs-target="#modalVistaPreviaSitio" data-bs-id="<?= $datosSitio['id_sitio'] ?>"
                                        data-categoria="<?= htmlspecialchars($datosSitio['titulo'] ?? 'Sin categoria') ?>"
                                        data-nombre="<?= htmlspecialchars($datosSitio['nombre']) ?>"
                                        data-descripcion="<?= htmlspecialchars($datosSitio['descripcion'] ?? 'Sin descripción') ?>"
                                        data-localidad="<?= htmlspecialchars($datosSitio['localidad'] ?? 'Desconocida') ?>"
                                        data-arancelado="<?= $datosSitio['tarifa'] ? 'Sí' : 'No' ?>"
                                        data-horarios="<?= htmlspecialchars($datosSitio['horarios'] ?? 'No especificados') ?>"
                                        data-imagen="<?= isset($imagenes[0]) && !empty($imagenes[0]) ? 'data:image/jpeg;base64,' . base64_encode($datosSitio['bin_imagen']) : '' ?>"
                                        data-etiqueta="<?= htmlspecialchars(implode(', ', $datosSitio['etiquetas'] ?? ['Sin etiqueta'])) ?>">
                                        <i class="bi bi-eye"></i>
                                    </button>


                                    <button class="btn btn-warning btn-sm mt-3" data-bs-dismiss="modal"
                                        data-bs-toggle="modal" data-bs-target="#modalEditarSitioTuristico"
                                        data-bs-id="<?= $datosSitio['id_sitio'] ?>"
                                        data-categoria="<?= htmlspecialchars($datosSitio['titulo'] ?? 'Sin categoria') ?>"
                                        data-nombre="<?= htmlspecialchars($datosSitio['nombre']) ?>"
                                        data-descripcion="<?= htmlspecialchars($datosSitio['descripcion'] ?? 'Sin descripción') ?>"
                                        data-localidad="<?= htmlspecialchars($datosSitio['localidad'] ?? 'Desconocida') ?>"
                                        data-arancelado="<?= $datosSitio['tarifa'] ? 'Sí' : 'No' ?>"
                                        data-horarios="<?= htmlspecialchars($datosSitio['horarios'] ?? 'No especificados') ?>"
                                        data-imagen="<?= isset($imagenes[0]) && !empty($imagenes[0]) ? 'data:image/jpeg;base64,' . base64_encode($datosSitio['bin_imagen']) : '' ?>"
                                        data-etiqueta="<?= htmlspecialchars(implode(', ', $datosSitio['etiquetas'] ?? ['Sin etiqueta'])) ?>">
                                        <i class="bi bi-pencil"></i>
                                    </button>

                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalVistaPreviaSitio" tabindex="-1" aria-labelledby="modalVistaPreviaSitioLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-custom-size">
        <div class="modal-content">
            <div class="modal-header">
                <button data-bs-dismiss="modal" data-bs-target="#sitiosModal" data-bs-toggle="modal" class="btn  btn-sm"
                    data-bs-dismiss="modal">

                    <i class="bi bi-arrow-left"></i>
                </button>
                <h3 class="modal-title fs-5" id="modalVistaPreviaSitioLabel">Vista Previa de Sitio</h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="card">
                    <div class="align-self-center" id="DIVCarrouselImagenesModal"></div>

                    <div class="card-body">
                        <h3 class="modal-title" id="IDNombreSitioModal">Nombre Sitio</h3>
                        <p id="CategoriaActual"></p>
                        <div id="DivCategoriasYEtiquetasModal"></div>
                    </div>

                    <ul class="list-group list-group-flush">
                        <div class="p-3 mt-0">
                            <p class="descriptionModal" id="IDDescripcionSitioModal"> Descripcion </p>
                        </div>
                        <hr>
                        <div class="col-lg-6">
                            <p class="ms-2 descriptionModal" id="IDLocalidadSitioModal">Localidad: </p>
                        </div>
                        <div class="col-lg-6">
                            <p class="ms-2 descriptionModal" id="IDArancelamientoSitioModal">Es arancelado: </p>
                        </div>
                        <div class="position-relative mt-3 mb-3 p-2">
                            <div class="position-absolute top-0 start-50 translate-middle">
                                <p class="ms-2 descriptionModal" id="IDHorariosSitioModal">Horarios: </p>
                            </div>
                        </div>
                    </ul>
                    <div class="card-body d-flex justify-content-end gap-2">
                        <input type="button" id="botonAprobarSitio" data-bs-id="" class="btn btn-success btn-sm"
                            value="Aprobar" data-bs-dismiss="modal">
                        <input type="button" id="botonRechazarSitio" data-bs-id="" class="btn btn-danger btn-sm"
                            value="Rechazar" data-bs-dismiss="modal">

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
require 'VIS_EditarSitioTuristico.php';
?>