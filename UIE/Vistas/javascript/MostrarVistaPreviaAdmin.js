function traerDatosModalPrevia(button) {
    const id = button.getAttribute('data-bs-id');
    const nombre = button.getAttribute('data-nombre');
    const descripcion = button.getAttribute('data-descripcion');
    const localidad = button.getAttribute('data-localidad');
    const arancelado = button.getAttribute('data-arancelado');
    const horarios = button.getAttribute('data-horarios');
    const imagen = button.getAttribute('data-imagen');
    const etiqueta = button.getAttribute('data-etiqueta');
    const categoria = button.getAttribute('data-categoria');

    document.getElementById('IDNombreSitioModal').textContent = nombre;
    document.getElementById('IDDescripcionSitioModal').textContent = descripcion;
    document.getElementById('IDLocalidadSitioModal').textContent = `Localidad: ${localidad}`;
    document.getElementById('IDArancelamientoSitioModal').textContent = `Es arancelado: ${arancelado}`;
    document.getElementById('IDHorariosSitioModal').textContent = `Horarios: ${horarios}`;
    document.getElementById('DivCategoriasYEtiquetasModal').textContent = etiqueta;
    document.getElementById('CategoriaActual').textContent =`Categoria: ${categoria}`;;

    const imagenDiv = document.getElementById('DIVCarrouselImagenesModal');
    if (imagen) {
        imagenDiv.innerHTML = `<img src="${imagen}" alt="${nombre}" class="img-fluid">`;
    } else {
        imagenDiv.innerHTML = '<p>Sin imagen disponible</p>';
    }
    const botonAprobar = document.getElementById('botonAprobarSitio');
    const botonRechazar = document.getElementById('botonRechazarSitio');

    botonAprobar.setAttribute('data-bs-id', id); 
    botonRechazar.setAttribute('data-bs-id', id);
}

document.querySelectorAll('[data-bs-toggle="modal"][data-bs-target="#modalVistaPreviaSitio"]').forEach(button => {
    button.addEventListener('click', function(event) {
        traerDatosModalPrevia(button);
    });
});