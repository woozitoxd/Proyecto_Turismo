// Función para cargar los comentarios mediante AJAX
function cargarComentario(idSitio) {

    // Mostrar el ID que recibe la función
    console.log('ID del sitio recibido:', idSitio);

    // Crear una nueva instancia de XMLHttpRequest
    const xhr = new XMLHttpRequest();

    let urlActual = window.location.href;
    let palabraClave = "UIE/";

    // Encuentra el índice de la palabra "UIE/" en la URL
    let indice = urlActual.indexOf(palabraClave);

    // Si la palabra "UIE/" se encuentra en la URL
    if (indice !== -1) {

        // Guarda la URL desde el inicio hasta la palabra "UIE/"
        let urlCortada = urlActual.substring(0, indice + palabraClave.length);

        // Configurar la solicitud
        xhr.open('POST', urlCortada + 'Controlador/CON_ObtenerComentarios.php', true);
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
                            <div class="d-flex flex-column border-bottom mt-2 pb-2">
                                <div class="d-flex flex-row justify-content-between">
                                        <div class="d-flex flex-row">
                                            <span><b>${comentario.nombre}</b></span>
                                            <p class="ms-3 mb-0 text-secondary">${comentario.fechaPublicacion}</p>
                                        </div>
                                        <button type="button" class="btn btn-sm btn-outline-danger" name="report" onclick="abrirVentanaReporte(${comentario.idComentario})">
                                            <i class="fas fa-flag"></i> Reportar
                                        </button>
                                </div>
                                <p>${comentario.Comentario}</p>
                            </div>
                        `;

                        /* // Crear el botón de denuncia
                        let denunciaButton = document.createElement("button");
                        let div = document.createElement("div");
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
                        li.appendChild(denunciaButton); */

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

    const textAreaComentario = document.querySelector(`[data-inputpublicacion${idSitio}]`);
    const cantidadCharRestantes = document.querySelector(`[data-contadorchar${idSitio}]`);

    textAreaComentario.addEventListener("input", function (){

        // Obtenemos el número de caracteres actual y lo establecemos en el contador

        cantidadCharRestantes.textContent = 'Límite de caracteres: ' + textAreaComentario.value.length + '/255';

    });
}

// Asignar el evento al formulario
document.addEventListener("submit", function (e){

    if(e.target.classList.contains("comentarios-form")){
        e.preventDefault();

        let urlActual = window.location.href;
        let palabraClave = "UIE/";

        // Encuentra el índice de la palabra "UIE/" en la URL
        let indice = urlActual.indexOf(palabraClave);

        if (indice !== -1) {

            // Guarda la URL desde el inicio hasta la palabra "UIE/"
            let urlCortada = urlActual.substring(0, indice + palabraClave.length);

            fetch(urlCortada + "Controlador/CON_EnviarComentario.php", {
        
                method: "POST",
                body: new FormData(e.target)
            })
            .then(res => res.json())
            .then(data => {
    
                console.log(data);
    
                const CajaComentariosDeSitio = document.getElementById(`lista-comentarios-${data.id_sitio}`);

                const li = document.createElement('li');
                li.classList.add('list-group-item');
                li.innerHTML = `
                    <div class="d-flex flex-column border-bottom mt-2 pb-2">
                        <div class="d-flex flex-row justify-content-between">
                            <div class="d-flex flex-row">
                                <span><b>${data.nombre}</b></span>
                                <p class="ms-3 mb-0 text-secondary">${data.fechaPublicacion}</p>
                            </div>
                            <button type="button" class="btn btn-sm btn-outline-danger" name="report" onclick="abrirVentanaReporte(${data.id_comentario})">
                                <i class="fas fa-flag"></i> Reportar
                            </button>
                        </div>
                        <p>${data.comentario}</p>
                    </div>
                `;

                CajaComentariosDeSitio.prepend(li);

                document.querySelector(`[data-inputpublicacion${data.id_sitio}]`).value = '';
                document.querySelector(`[data-contadorchar${data.id_sitio}]`).textContent = 'Límite de caracteres: 0/255';
            });

        } else {
            console.log("La palabra 'UIE/' no se encontró en la URL.");
        }
    }
});

function abrirVentanaReporte(idComentario) {
    window.open(`../Vistas/VIS_DenunciaComentario.php?idComentario=${idComentario}`, '_blank');
}