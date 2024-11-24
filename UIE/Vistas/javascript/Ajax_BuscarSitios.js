document.querySelector('.custom-search-btn').addEventListener('click', function() {
    // Limpiar el valor del input
    document.getElementById('buscador').value = '';
    const categoriaTexto = document.getElementById('dropdownCategorias');
    const etiquetaTexto = document.getElementById('dropdownEtiquetas');
    const localidadTexto = document.getElementById('dropdownLocalidad');

    // Ejecutar la lógica de filtrado para mostrar todas las tarjetas
    var tarjetas = document.querySelectorAll('.tarjeta-turistica');
    tarjetas.forEach(function(tarjeta) {
        tarjeta.style.display = 'block'; // Mostrar todas las tarjetas
        limpiarResaltado(tarjeta); // Limpiar el resaltado en las tarjetas
    });

    // Restablecer el texto de los botones del dropdown a su valor inicial
    // Solo modificar los botones de los dropdowns de filtros
    categoriaTexto.textContent = "Categorías";
    etiquetaTexto.textContent = "Etiquetas";
    localidadTexto.textContent = "Localidad";

    // Remover clase 'btn-warning' solo en los filtros
    categoriaTexto.classList.remove('btn-warning');
    etiquetaTexto.classList.remove('btn-warning');
    localidadTexto.classList.remove('btn-warning');
});

// Filtrar por categoría, etiqueta o localidad desde el dropdown
document.querySelectorAll('.dropdown-item').forEach(item => {
    // Asegurarse de que solo se manejan los dropdowns de filtros, no los de sesión u otros
    if (item.closest('.dropdown').id === 'dropdownCategorias' ||
        item.closest('.dropdown').id === 'dropdownEtiquetas' ||
        item.closest('.dropdown').id === 'dropdownLocalidad') {

        item.addEventListener("click", function(event) {
            event.preventDefault();
            const filtro = item.getAttribute("data-filtro").toLowerCase().normalize('NFD').replace(/[\u0300-\u036f]/g, ""); // Normalizar filtro
            const dropdownParent = item.closest('.dropdown');
            const dropdownButton = dropdownParent.querySelector("button");

            // Variables para los filtros específicos
            const categoriaTexto = document.getElementById('dropdownCategorias');
            const etiquetaTexto = document.getElementById('dropdownEtiquetas');
            const localidadTexto = document.getElementById('dropdownLocalidad');

            // Cambiar el texto del botón del dropdown a la opción seleccionada
            dropdownButton.textContent = item.textContent;

            // Obtener todas las tarjetas
            const tarjetas = document.querySelectorAll('.tarjeta-turistica');

            tarjetas.forEach(tarjeta => {
                const categoriaSitio = tarjeta.dataset.categoria ? tarjeta.dataset.categoria.toLowerCase().normalize('NFD').replace(/[\u0300-\u036f]/g, "") : '';
                const etiquetaSitio = tarjeta.dataset.etiqueta ? tarjeta.dataset.etiqueta.toLowerCase().normalize('NFD').replace(/[\u0300-\u036f]/g, "") : '';
                const localidadSitio = tarjeta.dataset.localidad ? tarjeta.dataset.localidad.toLowerCase().normalize('NFD').replace(/[\u0300-\u036f]/g, "") : '';

                // Verifica el filtro correspondiente y muestra/oculta las tarjetas según el filtro
                if (item.classList.contains("filtro-categoria") && categoriaSitio.includes(filtro)) {
                    tarjeta.style.display = "block";
                    categoriaTexto.classList.add("btn-warning");
                } else if (item.classList.contains("filtro-etiqueta") && etiquetaSitio.includes(filtro)) {
                    tarjeta.style.display = "block";
                    etiquetaTexto.classList.add("btn-warning");
                } else if (item.classList.contains("filtro-localidad") && localidadSitio.includes(filtro)) {
                    tarjeta.style.display = "block";
                    localidadTexto.classList.add("btn-warning");
                } else {
                    tarjeta.style.display = "none"; // Oculta la tarjeta si no coincide
                }
            });
        });
    }
});

// Función para manejar la búsqueda en tiempo real
document.getElementById('buscador').addEventListener('input', function() {
    var searchTerm = this.value.toLowerCase().normalize('NFD').replace(/[\u0300-\u036f]/g, ""); // Eliminar tildes

    var tarjetas = document.querySelectorAll('.tarjeta-turistica');
    tarjetas.forEach(function(tarjeta) {
        var nombreSitio = tarjeta.getAttribute('data-nombre-sitio').toLowerCase().normalize('NFD').replace(/[\u0300-\u036f]/g, "");
        var descripcionSitio = tarjeta.querySelector('.descripcion-lugar').textContent.toLowerCase().normalize('NFD').replace(/[\u0300-\u036f]/g, "");

        if (nombreSitio.includes(searchTerm) || descripcionSitio.includes(searchTerm)) {
            tarjeta.style.display = 'block';
            resaltarTexto(tarjeta, searchTerm);
        } else {
            tarjeta.style.display = 'none';
            limpiarResaltado(tarjeta);
        }
    });
});

// Función para resaltar el texto en la descripción
function resaltarTexto(tarjeta, searchTerm) {
    var descripcionElemento = tarjeta.querySelector('.descripcion-lugar');
    var descripcionTexto = descripcionElemento.textContent;

    var regex = new RegExp('(' + searchTerm + ')', 'gi');
    var nuevoHtml = descripcionTexto.replace(regex, function(match) {
        return '<span class="highlight">' + match + '</span>';
    });

    descripcionElemento.innerHTML = nuevoHtml;
}

// Función para limpiar el resaltado
function limpiarResaltado(tarjeta) {
    var descripcionElemento = tarjeta.querySelector('.descripcion-lugar');
    descripcionElemento.innerHTML = descripcionElemento.textContent; // Limpiar cualquier texto resaltado
}
