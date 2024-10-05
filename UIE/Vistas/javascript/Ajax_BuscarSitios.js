document.addEventListener("DOMContentLoaded", function() {
    const inputBuscador = document.getElementById("buscador");
    const tarjetas = document.querySelectorAll(".tarjeta-turistica");
    const dropdownItems = document.querySelectorAll(".dropdown-item.filtro");

    // Filtrar por texto de búsqueda
    inputBuscador.addEventListener("input", function() {
        const query = inputBuscador.value.toLowerCase();

        tarjetas.forEach(tarjeta => {
            // Validar si el nombre del sitio o la categoría están definidos
            const nombreSitio = tarjeta.dataset.nombreSitio ? tarjeta.dataset.nombreSitio.toLowerCase() : '';
            const categoriaSitio = tarjeta.dataset.categoria ? tarjeta.dataset.categoria.toLowerCase() : '';

            // Si alguna de las dos cadenas coincide, mostrar la tarjeta
            if (nombreSitio.includes(query) || categoriaSitio.includes(query)) {
                tarjeta.style.display = "block"; // Mostrar tarjeta si coincide
            } else {
                tarjeta.style.display = "none"; // Ocultar tarjeta si no coincide
            }
        });
    });

    // Filtrar por categoría desde el dropdown
    dropdownItems.forEach(item => {
        item.addEventListener("click", function(event) {
            event.preventDefault();
            const filtro = item.getAttribute("data-filtro").toLowerCase();

            tarjetas.forEach(tarjeta => {
                // Validar si la categoría del sitio está definida
                const categoriaSitio = tarjeta.dataset.categoria ? tarjeta.dataset.categoria.toLowerCase() : '';

                if (categoriaSitio.includes(filtro)) {
                    tarjeta.style.display = "block"; // Mostrar tarjeta si coincide con el filtro
                } else {
                    tarjeta.style.display = "none"; // Ocultar tarjeta si no coincide
                }
            });
        });
    });
});
