document.addEventListener("DOMContentLoaded", function () {
    // Función para actualizar la cantidad de sitios pendientes de aprobación
    function actualizarCantidadPendientes() {
        fetch('../Controlador/CON_ContarSitiosTuristicosAprobar.php')
            .then(response => {
                if (!response.ok) {
                    throw new Error("Error en la respuesta del servidor");
                }
                return response.json();
            })
            .then(data => {
                // Obtener el elemento para actualizar el texto
                const pendientesElement = document.getElementById("pendientesAprobacion");

                // Actualiza el texto con la cantidad de sitios pendientes
                pendientesElement.textContent = `Quedan ${data.cantidad} sitios pendientes de aprobación.`;

                // Cambiar el estilo según la cantidad de sitios pendientes
                if (data.cantidad === 0) {
                    // Si no hay sitios pendientes, el fondo es verde
                    pendientesElement.style.backgroundColor = "green";
                    pendientesElement.style.color = "white";
                } else {
                    // Si hay sitios pendientes, el fondo es rojo
                    pendientesElement.style.backgroundColor = "red";
                    pendientesElement.style.color = "white";
                }

                // Estilo común
                pendientesElement.style.padding = "5px 10px";
                pendientesElement.style.borderRadius = "5px";
                pendientesElement.style.fontWeight = "bold";
            })
            .catch(error => {
                console.error("Hubo un problema con la solicitud AJAX:", error);
                document.getElementById("pendientesAprobacion").textContent =
                    "Error al obtener la cantidad de sitios pendientes.";
            });
    }

    // Llama a la función para obtener la cantidad inicial
    actualizarCantidadPendientes();

    // Actualiza la cantidad cuando se cierra el modal
    document.getElementById("sitiosModal").addEventListener("hidden.bs.modal", function () {
        actualizarCantidadPendientes();
    });
});
