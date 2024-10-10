// Función para cargar los comentarios mediante AJAX
function cargarComentario(idSitio) {
    // Mostrar el ID que recibe la función
    console.log('ID del sitio recibido:', idSitio);

    // Crear una nueva instancia de XMLHttpRequest
    const xhr = new XMLHttpRequest();

    // Configurar la solicitud
    xhr.open('POST', 'http://localhost/Proyecto_DesarrolloSoftware/Proyecto_Turismo/UIE/Controlador/CON_ObtenerComentarios.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

    // Configurar la función que se ejecutará cuando se reciba la respuesta
    xhr.onload = function() {
        if (xhr.status === 200) {
            // Parsear la respuesta JSON
            const comentarios = JSON.parse(xhr.responseText);

            // Obtener el elemento donde se volcarán los comentarios
            const listaComentarios = document.getElementById(`lista-comentarios-${idSitio}`);
            listaComentarios.innerHTML = ''; // Limpiar cualquier contenido previo

            // Verificar si hay un error en la respuesta
            if (comentarios.error) {
                listaComentarios.innerHTML = `<li class="text-danger">${comentarios.error}</li>`;
            } else {
                // Recorrer los comentarios y agregarlos al HTML
                comentarios.forEach(comentario => {
                    const li = document.createElement('li');
                    li.classList.add('list-group-item');
                    li.innerHTML = `
                        <strong>${comentario.nombre}</strong> (${comentario.fechaPublicacion}):<br>
                        ${comentario.Comentario} <br>
                    `;

                    // Crear el botón de denuncia
                    let denunciaButton = document.createElement("button");
                    denunciaButton.type = "button";
                    denunciaButton.className = "btn btn-sm btn-outline-danger";
                    denunciaButton.name = "report";

                    // Icono de Font Awesome
                    let icon = document.createElement("i");
                    icon.className = "fas fa-flag"; // Icono de bandera
                    denunciaButton.appendChild(icon); // Agregar el icono al botón

                    // Agregar texto al botón
                    denunciaButton.appendChild(document.createTextNode(" Reportar")); // Texto del botón
                    // Añadir evento para redirigir a la página de denuncia
                    denunciaButton.addEventListener("click", function() {
                        const idComentario = comentario.idComentario; // O el ID del comentario que desees pasar
                        window.open(`../Vistas/VIS_DenunciaComentario.php?idComentario=${idComentario}`, '_blank'); // Cambia la URL según tu estructura
                    });

                    // Agregar el botón al comentario
                    li.appendChild(denunciaButton);

                    // Agregar el comentario a la lista
                    listaComentarios.appendChild(li);
                });
            }
        } else {
            console.error('Error en la solicitud AJAX:', xhr.statusText);
        }
    };

    // Manejar errores de red
    xhr.onerror = function() {
        console.error('Error en la conexión.');
    };

    // Enviar la solicitud con el ID del sitio turístico
    xhr.send(`id=${idSitio}`);
}

// Asignar el evento al formulario
document.getElementById('comentarios-form').addEventListener('submit', enviarComentario);
