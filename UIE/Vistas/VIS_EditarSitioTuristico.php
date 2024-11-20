<div class="modal fade" id="modalEditarSitioTuristico" tabindex="-1" aria-labelledby="modalVistaPreviaSitioLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-custom-width">
        <div class="modal-content">
            <div class="modal-header">
                <button data-bs-dismiss="modal" data-bs-target="#sitiosModal" data-bs-toggle="modal" class="btn  btn-sm"
                    data-bs-dismiss="modal">

                    <i class="bi bi-arrow-left"></i>
                </button>
                <h3 class="modal-title fs-5" id="modalVistaPreviaSitioLabel">Vista Previa de Sitio</h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal" data-bs-toggle="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Aquí va el formulario -->
                <form class="mt-5" id="form-EditarSitio" data-bs-id-editar="">
                    <div class="container-fluid mt-3 row">
                        <div class="mb-2 col-lg-6">
                            <label class="h5 form-label" for="NombreSitioTuristico">Nombre de Sitio</label>
                            <input class="form-control" type="text" name="NombreSitioTuristico"
                                placeholder="Añade el nombre del Sitio Turistico!" aria-label=".form-control"
                                id="NombreSitioTuristico" maxlength="100" required>
                            <small class="text-danger" id="NombreSitioTuristicoError"></small>
                            <div class="row">
                                <label for="SelectCategoria" class="h5 form-label mt-3">Categoria</label>
                                <label for="SelectCategoria" id="categoriaElegida"
                                            class="form-label h6 btn btn-secondary mt-3"></label>
                                <div class="col-6" class="btn btn-secondary">
                                    <select id="SelectCategoria" class="form-select mt-2"
                                        onchange="actualizarCategoria()">
                                        <option value=-1 disabled selected>Selecciona una Categoria!</option>
                                    </select>
                                    <small class="text-danger" id="SelectCategoriaError"></small>
                                </div>
                                <div class="col-6 d-flex justify-content-center">
                                    <p class="categoria-lugar" id="CategoriaSeleccionada">-</p>
                                </div>
                            </div>

                            <div class="row">
                                <label for="SelectEtiqueta" class="h5 form-label mt-3 ">Etiquetas</label>
                                <p id="EtiquetasElegidas" class="btn btn-outline-success"></p>
                                <div class="col-9">
                                <select id="SelectEtiquetas" multiple >
                                    </select>
                                </div>
                                <small class="text-danger" id="SelectEtiquetasError"></small>
                            </div>


                            <div class="mb-2 form-check form-switch mt-5 d-flex justify-content-center">
                                <div class="">
                                    <input class="form-check-input h5 mb-1" type="checkbox" role="switch"
                                        id="flexSwitchCheckDefault" name="flexSwitchCheckDefault">
                                    <label class="form-check-label h5 mb-1" for="flexSwitchCheckDefault">Es
                                        arancelado?</label>
                                </div>
                            </div>
                        </div>

                        <div class="mb-2 col-lg-6 mt-3">
                            <div class="mb-2">
                                <label for="Descripcion" class="h5 form-label">Descripción</label>
                                <textarea class="form-control textarea-resize" id="Descripcion" name="Descripcion"
                                    placeholder="Añade una descripción al Sitio Turistico" required></textarea>
                                <small class="text-danger" id="DescripcionError"></small>
                            </div>

                            <div class="mb-2">
                                <div class="d-flex align-items-center mt-3">
                                    <label for="SelectHorarioApertura" class="h5 form-label mb-0 me-3">Horarios</label>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input h6 mb-1" type="checkbox" role="switch"
                                            id="24HorasCheck" name="24HorasCheck" onchange="toggleHorarios(this)">
                                        <label class="form-check-label h6 mb-1" for="24HorasCheck">24 Horas</label>
                                    </div>
                                </div>

                                <div id="ContenedorSelectHorarios" class="row">
                                    <div class="mt-3 col-6">
                                        <label for="SelectHorarioApertura" class="form-label h6">Horario de
                                            Apertura</label>
                                        <select id="SelectHorarioApertura" name="HorarioApertura" class="form-select">
                                            <?php
                                            for ($hora = 0; $hora < 24; $hora++):
                                                for ($minuto = 0; $minuto < 60; $minuto += 15):
                                                    $timeValue = str_pad($hora, 2, '0', STR_PAD_LEFT) . ':' . str_pad($minuto, 2, '0', STR_PAD_LEFT);
                                                    ?>
                                                    <option value="<?php echo $timeValue; ?>"><?php echo $timeValue; ?></option>
                                                    <?php
                                                endfor;
                                            endfor;
                                            ?>
                                        </select>
                                        <label for="SelectHorarioApertura" id="HorarioElegido"
                                            class="form-label h6 btn btn-secondary mt-3"></label>

                                    </div>
                                    <div class="mt-3 col-6">
                                        <label for="SelectHorarioCierre" class="form-label h6">Horario de Cierre</label>
                                        <select id="SelectHorarioCierre" name="HorarioCierre" class="form-select">
                                            <?php
                                            for ($hora = 0; $hora < 24; $hora++):
                                                for ($minuto = 0; $minuto < 60; $minuto += 15):
                                                    $timeValue = str_pad($hora, 2, '0', STR_PAD_LEFT) . ':' . str_pad($minuto, 2, '0', STR_PAD_LEFT);
                                                    ?>
                                                    <option value="<?php echo $timeValue; ?>"><?php echo $timeValue; ?></option>
                                                    <?php
                                                endfor;
                                            endfor;
                                            ?>
                                        </select>
                                    </div>
                                    <small class="text-danger mt-1" id="HorariosError"></small>
                                </div>
                            </div>
                        </div>

                        <!-- <div class="mb-2 col-lg-6 mt-3">
                            <div class="mb-3">
                                <label class="h5 form-label">Imagenes de Sitio Turistico</label>
                                <input type="file" id="ImagenSitio" class="form-control" accept="image/*" multiple
                                    onchange="previewImages(event)">
                            </div>
                            <div class="mb-3">
                                <label class="h5 form-label">Imágenes seleccionadas</label>
                                <small class="text-danger" id="ImagenSitioError"></small>

                                <div id="imagePreviewContainer"></div>
                                <div id="thumbnailContainer" class="d-flex flex-wrap mt-3"></div>
                            </div>
                        </div> -->

                        <div class="mb-2 col-lg-6 row">
                            <label for="SelectLocalidad" class="h5 form-label mt-3">Localidad</label>
                            <div class="col-6">
                                <select id="SelectLocalidad" class="form-select mt-2">
                                    <option value=-1 disabled selected>Selecciona una localidad!</option>
                                </select>
                                <small class="text-danger" id="SelectLocalidadError"></small>
                            </div>

                            <hr class="mt-4">
                            <div class="position-relative mt-3 mb-3 p-2">
                            <div class="position-relative mt-3 mb-3 p-2">
                                <div class="position-absolute top-0 start-50 translate-middle">
                                    <button type="button" class="btn btn-success" id="btn-publicar" onclick="btnEditarSitio();">Guardar Cambios</button>
                                </div>
                            </div>
                            </div>
                        </div>
                </form>
            </div>
        </div>
    </div>
</div>