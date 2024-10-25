document.addEventListener("DOMContentLoaded", function() {
    const inputBuscador = document.getElementById("buscador");
    const tarjetas = document.querySelectorAll(".tarjeta-turistica");
    const dropdownItems = document.querySelectorAll(".dropdown-item.filtro");
    const dropdowns = document.querySelectorAll(".dropdown");
    const botonLimpiar = document.querySelector(".custom-search-btn");

    // Filtrar por texto de búsqueda
    inputBuscador.addEventListener("input", function() {
        const query = inputBuscador.value.toLowerCase();
        
        tarjetas.forEach(tarjeta => {
            const nombreSitio = tarjeta.dataset.nombreSitio ? tarjeta.dataset.nombreSitio.toLowerCase() : '';
            const categoriaSitio = tarjeta.dataset.categoria ? tarjeta.dataset.categoria.toLowerCase() : '';
            const descripcionSitio = tarjeta.dataset.descripcionLugar ? tarjeta.dataset.descripcionLugar.toLowerCase() : '';
            const etiquetaSitio = tarjeta.dataset.etiqueta ? tarjeta.dataset.etiqueta.toLowerCase() : '';
            const localidadSitio = tarjeta.dataset.localidad ? tarjeta.dataset.localidad.toLowerCase() : '';
            const descripcionElemento = tarjeta.querySelector(".descripcion-lugar");

            // Mostrar tarjeta si coincide
            if (nombreSitio.includes(query) || categoriaSitio.includes(query) || descripcionSitio.includes(query) || etiquetaSitio.includes(query) || localidadSitio.includes(query)) {
                tarjeta.style.display = "block";

                // Resaltar palabra buscada
                if (query !== '') {
                    const regex = new RegExp(`(${query})`, 'gi');
                    const descripcionOriginal = tarjeta.dataset.descripcionLugar;
                    const descripcionResaltada = descripcionOriginal.replace(regex, '<span class="highlight">$1</span>');
                    descripcionElemento.innerHTML = descripcionResaltada;
                } else {
                    descripcionElemento.textContent = tarjeta.dataset.descripcionLugar;
                }
            } else {
                tarjeta.style.display = "none";
            }
        });
    });

    // Filtrar por categoría, etiqueta o localidad desde el dropdown
    dropdownItems.forEach(item => {
        item.addEventListener("click", function(event) {
            event.preventDefault();
            const filtro = item.getAttribute("data-filtro").toLowerCase();
            const dropdownParent = item.closest('.dropdown');

            tarjetas.forEach(tarjeta => {
                const categoriaSitio = tarjeta.dataset.categoria ? tarjeta.dataset.categoria.toLowerCase() : '';
                const etiquetaSitio = tarjeta.dataset.etiqueta ? tarjeta.dataset.etiqueta.toLowerCase() : '';
                const localidadSitio = tarjeta.dataset.localidad ? tarjeta.dataset.localidad.toLowerCase() : '';

                // Verifica el filtro correspondiente
                if (item.classList.contains("filtro-categoria") && categoriaSitio.includes(filtro)) {
                    tarjeta.style.display = "block";
                    dropdownParent.classList.add("dropdown-active");
                } else if (item.classList.contains("filtro-etiqueta") && etiquetaSitio.includes(filtro)) {
                    tarjeta.style.display = "block";
                    dropdownParent.classList.add("dropdown-active");
                } else if (item.classList.contains("filtro-localidad") && localidadSitio.includes(filtro)) {
                    tarjeta.style.display = "block";
                    dropdownParent.classList.add("dropdown-active");
                } else {
                    tarjeta.style.display = "none"; // Oculta la tarjeta si no coincide
                }
            });
        });
    });

    // Evento click para limpiar la búsqueda
    botonLimpiar.addEventListener("click", function(event) {
        event.preventDefault(); 

        // Limpiar input y mostrar todas las tarjetas
        inputBuscador.value = "";
        tarjetas.forEach(tarjeta => {
            tarjeta.style.display = "block";
            const descripcionElemento = tarjeta.querySelector(".descripcion-lugar");
            descripcionElemento.textContent = tarjeta.dataset.descripcionLugar;
        });

        // Eliminar clase de resaltado en todos los dropdowns
        dropdowns.forEach(dropdown => {
            dropdown.classList.remove("dropdown-active");
        });
    });
});
