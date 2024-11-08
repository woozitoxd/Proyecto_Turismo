<div class="modal fade" id="sitiosModal" tabindex="-1" aria-labelledby="sitiosModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="sitiosModalLabel">Destinos Turísticos Más Solicitados</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
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
                        <?php foreach ($sitios as $sitio): ?>
                            <tr id="sitio-row-<?= $sitio['id_sitio'] ?>">
                                <td><?= htmlspecialchars($sitio['nombre']) ?></td>
                                <td><?= htmlspecialchars($sitio['descripcion'] ?? 'Sin descripción') ?></td>
                                <td>
                                    <?php if (!empty($sitio['bin_imagen'])): ?>
                                        <img src="data:image/jpeg;base64,<?= base64_encode($sitio['bin_imagen']) ?>"
                                            alt="<?= htmlspecialchars($sitio['titulo']) ?>" width="50">
                                    <?php else: ?>
                                        Sin imagen
                                    <?php endif; ?>
                                </td>
                                <td><?= htmlspecialchars($sitio['etiqueta'] ?? 'Sin etiqueta') ?></td>
                                <td><?= htmlspecialchars($sitio['localidad'] ?? 'Desconocida') ?></td>
                                <td>
                                    <button onclick="aprobarSitio(<?= $sitio['id_sitio'] ?>)"
                                        class="btn btn-success btn-sm">
                                        <i class="bi bi-check-lg"></i>
                                    </button>
                                    <button onclick="rechazarSitio(<?= $sitio['id_sitio'] ?>)"
                                        class="btn btn-danger btn-sm">
                                        <i class="bi bi-x-lg"></i>
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