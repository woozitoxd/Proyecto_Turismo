function abrirModalVistaPrevia() {
    const modalVistaPrevia = document.getElementById('modalVistaPreviaSitio');
    const modalSitios = document.getElementById('sitiosModal');

    // Agregar el evento 'shown.bs.modal' al modalVistaPreviaSitio
    modalVistaPrevia.addEventListener('shown.bs.modal', function () {
        console.log('El modalVistaPreviaSitio se ha mostrado');

        // Cerrar el modal sitiosModal cuando se abra modalVistaPreviaSitio
        const sitiosModalInstance = bootstrap.Modal.getInstance(modalSitios);
        sitiosModalInstance.hide(); // Cierra el modal sitiosModal
    });
}

function traerDatosModalPrevia(button) {
    // Extraer los datos del botón
    const id = button.getAttribute('data-bs-id');
    const nombre = button.getAttribute('data-nombre');
    const descripcion = button.getAttribute('data-descripcion');
    const localidad = button.getAttribute('data-localidad');
    const arancelado = button.getAttribute('data-arancelado');
    const horarios = button.getAttribute('data-horarios');
    const imagen = button.getAttribute('data-imagen');
    const etiqueta = button.getAttribute('data-etiqueta');

    // Actualizar el contenido del modal
    document.getElementById('IDNombreSitioModal').textContent = nombre;
    document.getElementById('IDDescripcionSitioModal').textContent = descripcion;
    document.getElementById('IDLocalidadSitioModal').textContent = `Localidad: ${localidad}`;
    document.getElementById('IDArancelamientoSitioModal').textContent = `Es arancelado: ${arancelado}`;
    document.getElementById('IDHorariosSitioModal').textContent = `Horarios: ${horarios}`;
    document.getElementById('DivCategoriasYEtiquetasModal').textContent = etiqueta;

    const imagenDiv = document.getElementById('DIVCarrouselImagenesModal');
    if (imagen) {
        imagenDiv.innerHTML = `<img src="${imagen}" alt="${nombre}" class="img-fluid">`;
    } else {
        imagenDiv.innerHTML = '<p>Sin imagen disponible</p>';
    }
    const botonAprobar = document.getElementById('botonAprobarSitio');
    const botonRechazar = document.getElementById('botonRechazarSitio');

    botonAprobar.setAttribute('data-bs-id', id); // Asigna el id al botón Aprobar
    botonRechazar.setAttribute('data-bs-id', id); // Asigna el id al botón Rechazar
}

// Llamar a la función cuando se haga clic en el botón
document.querySelectorAll('[data-bs-toggle="modal"][data-bs-target="#modalVistaPreviaSitio"]').forEach(button => {
    button.addEventListener('click', function(event) {
        // Primero, traer los datos del modal
        traerDatosModalPrevia(button);

        // Luego, abrir el modal de vista previa y cerrar el otro modal
        abrirModalVistaPrevia();
    });
});


function volverModal() {
    const modalSitios = new bootstrap.Modal(document.getElementById('sitiosModal'));

    modalSitios.show();
}