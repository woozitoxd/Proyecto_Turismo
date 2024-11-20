function traerDatosModalEditar(button) {
    // Obtener atributos del botón
    const id = button.getAttribute('data-bs-id');
    const nombre = button.getAttribute('data-nombre');
    const descripcion = button.getAttribute('data-descripcion');
    const localidad = button.getAttribute('data-localidad');
    const arancelado = button.getAttribute('data-arancelado') === 'Sí';
    const horarios = button.getAttribute('data-horarios');
    const etiqueta = button.getAttribute('data-etiqueta');
    const categoria = button.getAttribute('data-categoria');
    console.log(categoria);
    //const imagen = button.getAttribute('data-imagen');

    // Llenar los campos del formulario
    document.getElementById('NombreSitioTuristico').value = nombre || '';
    document.getElementById('Descripcion').value = descripcion || '';
    document.getElementById('SelectLocalidad').value = localidad || -1;
    document.getElementById('HorarioElegido').textContent='Horario actual: ' + horarios || 'Horario no elegido';
    const categoriaLabel = document.getElementById('categoriaElegida');
    categoriaLabel.textContent ='Categoría Actual: ' + categoria || 'Categoría no elegida';
    

    // Checkbox para arancelado
    document.getElementById('flexSwitchCheckDefault').checked = arancelado;
    const form = document.getElementById('form-EditarSitio');
    form.setAttribute('data-bs-id-editar', id);

    // Llenar horarios (apertura y cierre)
    const [horarioApertura, horarioCierre] = horarios.split(' - ') || [];
    document.getElementById('SelectHorarioApertura').value = horarioApertura || '';
    document.getElementById('SelectHorarioCierre').value = horarioCierre || '';

    // Actualizar etiquetas (en caso de ser múltiples)
    // Actualizar etiquetas (en caso de ser múltiples)
const etiquetasArray = etiqueta.split(',').map(e => e.trim());
const selectEtiquetas = document.getElementById('EtiquetasElegidas');
selectEtiquetas.innerHTML = ''; // Limpiar el select

etiquetasArray.forEach(etiqueta => {
    const option = document.createElement('option');
    option.value = etiqueta;
    option.textContent = etiqueta;
    option.selected = true; // Marcar como seleccionado
    selectEtiquetas.appendChild(option);
});


    // Actualizar vista previa de imagen
    // const imagenDiv = document.getElementById('imagePreviewContainer');
    // if (imagen) {
    //     imagenDiv.innerHTML = `<img src="${imagen}" alt="${nombre}" class="img-fluid img-thumbnail">`;
    // } else {
    //     imagenDiv.innerHTML = '<p>Sin imagen disponible</p>';
    // }
}

// Asociar evento a los botones
document.querySelectorAll('[data-bs-toggle="modal"][data-bs-target="#modalEditarSitioTuristico"]').forEach(button => {
    button.addEventListener('click', function() {
        traerDatosModalEditar(button);
    });
});
