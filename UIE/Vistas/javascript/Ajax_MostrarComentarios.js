function cargarComentario(idSitio) {
    console.log('ID del sitio recibido:', idSitio);

    const xhr = new XMLHttpRequest();
    let urlActual = window.location.href;
    let palabraClave = "UIE/";
    let indice = urlActual.indexOf(palabraClave);

    if (indice !== -1) {
        let urlCortada = urlActual.substring(0, indice + palabraClave.length);
        xhr.open('POST', urlCortada + 'Controlador/CON_ObtenerComentarios.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

        xhr.onload = function() {
            if (xhr.status === 200) {
                const comentarios = JSON.parse(xhr.responseText);
                const listaComentarios = document.getElementById(`lista-comentarios-${idSitio}`);
                listaComentarios.innerHTML = ''; 

                if (comentarios.error) {
                    listaComentarios.innerHTML = `<li class="text-danger">${comentarios.error}</li>`;
                } else {
                    comentarios.forEach(comentario => {
                        const li = document.createElement('li');
                        li.classList.add('list-group-item');
                        li.innerHTML = `
                        <div class="d-flex flex-column border-bottom mt-2 pb-2" id="comentario-${comentario.idComentario}">
                            <div class="d-flex flex-row justify-content-between">
                                <div class="d-flex flex-row align-items-end">
                                    <span class="info-comment-${comentario.idComentario}"><b>${comentario.nombre}</b></span>
                                    <p class="ms-3 mb-0 text-secondary">${comentario.fechaPublicacion}</p>
                                </div>
                                <button type="button" class="btn btn-sm btn-outline-danger" name="report" onclick="openPopup(${comentario.idComentario})">
                                    <i class="fas fa-flag"></i> Reportar
                                </button>
                            </div>
                            <p class="mt-2" id="comentario-text-${comentario.idComentario}">${comentario.Comentario}</p>
                            
                            ${comentario.idUsuario === idUsuarioLogueado ? `
                                <button type="button" class="btn btn-sm btn-outline-warning mt-2" onclick="habilitarEdicion(${comentario.idComentario})">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-danger mt-2" onclick="eliminarComentario(${comentario.idComentario}, ${idSitio})">
                                    <i class="fas fa-trash"></i>
                                </button>
                            ` : ''}
                        </div>
                        `;

                        const ContenedorValoracion = document.createElement("div");

                        ContenedorValoracion.className = "mx-2 d-flex flex-row";

                        for (let index = 1; index <= 5; index++) {

                            const estrella = document.createElement("span");

                            estrella.innerHTML = "&#9733;";

                            if (index <= comentario.valoracion) {
                                estrella.className = "estrella hover fs-5"
                            }else{
                                estrella.className = "estrella fs-5";
                            }

                            ContenedorValoracion.appendChild(estrella);

                        }
                        
                        listaComentarios.appendChild(li);

                        document.querySelector(".info-comment-"+comentario.idComentario).insertAdjacentElement("afterend", ContenedorValoracion);
                    
                    });
                }
            } else {
                console.error('Error en la solicitud AJAX:', xhr.statusText);
            }
        };

        xhr.onerror = function() {
            console.error('Error en la conexión.');
        };

        xhr.send(`id=${idSitio}`);
    }

    const textAreaComentario = document.querySelector(`[data-inputpublicacion${idSitio}]`);
    const cantidadCharRestantes = document.querySelector(`[data-contadorchar${idSitio}]`);

    textAreaComentario.addEventListener("input", function () {
        cantidadCharRestantes.textContent = 'Límite de caracteres: ' + textAreaComentario.value.length + '/255';
    });
}


function eliminarComentario(idComentario, idSitio) {
    if (confirm("¿Estás seguro de que deseas eliminar este comentario?")) {
        let urlActual = window.location.href;
        let palabraClave = "UIE/";
        let indice = urlActual.indexOf(palabraClave);

        if (indice !== -1) {
            let urlCortada = urlActual.substring(0, indice + palabraClave.length);
            fetch(urlCortada + "Controlador/CON_BorrarComentarioPropio.php", {
                method: "POST",
                headers: { "Content-Type": "application/x-www-form-urlencoded" },
                body: `id_comentario=${idComentario}&id_sitio=${idSitio}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.getElementById(`comentario-${idComentario}`).remove(); // Remover el comentario del DOM
                    mostrarMensajeTemporal("Comentario eliminado correctamente.");

                    //alert("Comentario eliminado correctamente.");
                } else {
                    mostrarMensajeTemporal("Error al eliminar el comentario: " + data.error);

                    //alert("Error al eliminar el comentario: " + data.error); // Muestra el error del servidor
                }
            })
            .catch(error => {
                mostrarMensajeTemporal("Error en la solicitud.");

                console.error("Error al eliminar el comentario:", error);
            });
        }
    }
}


function mostrarMensajeTemporal(mensaje) {
    const mensajeElement = document.createElement("div");
    mensajeElement.textContent = mensaje;
    mensajeElement.className = "alert alert-success"; // O el estilo que prefieras
    document.body.appendChild(mensajeElement);
    setTimeout(() => {
        mensajeElement.remove();
    }, 3000); // El mensaje desaparece después de 3 segundos
}

function habilitarEdicion(idComentario) {
    const comentarioText = document.getElementById(`comentario-text-${idComentario}`);
    const comentarioActual = comentarioText.textContent;

    // Recuperar la valoración actual
    const valoracionActual = comentarioText.getAttribute('data-valoracion') || 0;

    // Cambiar el texto a un campo de entrada y añadir la interfaz de estrellas
    comentarioText.innerHTML = `
        <div class="d-flex flex-row align-items-center mb-2">
            <div class="valoracion" data-value="${valoracionActual}" id="valoracion-edicion-${idComentario}">
                ${[1, 2, 3, 4, 5].map(i => `
                    <span class="estrella estrella-sitio ${i <= valoracionActual ? 'selected' : ''}" data-value="${i}" onclick="seleccionarEstrella(${idComentario}, ${i})" onmouseover="this.classList.add('hover')" onmouseout="this.classList.remove('hover')">&#9733;</span>
                `).join('')}
            </div>
        </div>
        <textarea class="form-control" id="textarea-${idComentario}">${comentarioActual}</textarea>
        <button type="button" class="btn btn-sm btn-primary mt-2" onclick="guardarComentario(${idComentario})">Guardar</button>
        <button type="button" class="btn btn-sm btn-secondary mt-2" onclick="cancelarEdicion(${idComentario}, '${comentarioActual}', ${valoracionActual})">Cancelar</button>
    `;
}

// Función para seleccionar la estrella
function seleccionarEstrella(idComentario, valor) {
    const valoracionDiv = document.getElementById(`valoracion-edicion-${idComentario}`);
    valoracionDiv.setAttribute('data-value', valor);

    // Cambiar visualmente las estrellas según la valoración seleccionada
    Array.from(valoracionDiv.children).forEach(estrella => {
        estrella.classList.toggle('selected', estrella.getAttribute('data-value') <= valor);
    });
}

function guardarComentario(idComentario) {
    const textoArea = document.getElementById(`textarea-${idComentario}`);
    const nuevoComentario = textoArea.value;

    if (nuevoComentario.trim() === '') {
        alert('El comentario no puede estar vacío.');
        return;
    }

    // Obtener la valoración seleccionada
    const valoracionDiv = document.getElementById(`valoracion-edicion-${idComentario}`);
    const valoracionActual = valoracionDiv.getAttribute('data-value') || 0; // Asegúrate de que data-value se actualice según la selección del usuario

    let urlActual = window.location.href;
    let palabraClave = "UIE/";
    let indice = urlActual.indexOf(palabraClave);

    if (indice !== -1) {
        let urlCortada = urlActual.substring(0, indice + palabraClave.length);
        fetch(urlCortada + "Controlador/CON_ActualizarComentario.php", {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: `id_comentario=${idComentario}&nuevo_comentario=${encodeURIComponent(nuevoComentario)}&nueva_valoracion=${valoracionActual}`
        })
        .then(response => response.json()) // Cambiar a .json() para procesar la respuesta como JSON
        .then(data => {
            if (data.success) {
                // Actualizar el comentario en la interfaz
                const comentarioText = document.getElementById(`comentario-text-${idComentario}`);
                comentarioText.textContent = nuevoComentario;


                // Actualizar las estrellas existentes
                const contenedorEstrellas = document.getElementById("valoracion"); // Asegúrate de que este ID sea correcto
                const estrellas = contenedorEstrellas.querySelectorAll(`.estrella-sitio${idComentario}`); // Seleccionar todas las estrellas dentro del contenedor

                estrellas.forEach((estrella, index) => {
                    // Establecer la clase para cada estrella basada en la nueva valoración
                    if (index < valoracionActual) {
                        estrella.classList.remove('hover'); // Quitar la clase hover para las estrellas seleccionadas
                        estrella.classList.add('fs-5'); // Asegurarse de que la clase fs-5 esté presente
                    } else {
                        estrella.classList.add('hover'); // Agregar clase hover para las estrellas no seleccionadas
                        estrella.classList.remove('fs-5'); // Eliminar la clase fs-5 si no está seleccionada
                    }
                });
                

                alert('Comentario actualizado correctamente.');
            } else {
                alert(data.message || 'Error al actualizar el comentario.');
            }
        })
        .catch(error => {
            console.error("Error al actualizar el comentario:", error);
        });
    }
}

function cancelarEdicion(idComentario, comentarioAnterior) {
    const comentarioText = document.getElementById(`comentario-text-${idComentario}`);
    comentarioText.textContent = comentarioAnterior; // Revertir al comentario anterior
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

                if (data.success == true) {

                    const CajaComentariosDeSitio = document.getElementById(`lista-comentarios-${data.id_sitio}`);
    
                    const li = document.createElement('li');
                    li.classList.add('list-group-item');
                    li.innerHTML = `
                    <div class="d-flex flex-column border-bottom mt-2 pb-2" id="comentario-${data.id_comentario}">
                        <div class="d-flex flex-row justify-content-between">
                            <div class="d-flex flex-row">
                                <span class="info-comment-${data.id_comentario}"><b>${data.nombre}</b></span>
                                <p class="ms-3 mb-0 text-secondary">${data.fechaPublicacion}</p>
                            </div>
                            <button type="button" class="btn btn-sm btn-outline-danger" name="report" onclick="openPopup(${data.id_comentario})">
                                <i class="fas fa-flag"></i> Reportar
                            </button>
                        </div>
                        <p class="comentario-text" id="comentario-text-${data.id_comentario}">${data.comentario}</p>
                        
                        ${data.id_usuario === idUsuarioLogueado ? `
                            <button type="button" class="btn btn-sm btn-outline-warning mt-2" onclick="habilitarEdicion(${data.id_comentario})">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-danger mt-2" onclick="eliminarComentario(${data.id_comentario}, ${data.id_sitio})">
                                <i class="fas fa-trash"></i>
                            </button>
                        ` : ''}
                    </div>
                    `;
    
                    const ContenedorValoracion = document.createElement("div");
                    ContenedorValoracion.className = "mx-2";
    
                    for (let index = 1; index <= 5; index++) {
    
                        const estrella = document.createElement("span");
    
                        estrella.innerHTML = "&#9733;";
    
                        if (index <= data.valoracion) {
                            estrella.className = "estrella hover fs-5"
                        }else{
                            estrella.className = "estrella fs-5";
                        }
                        ContenedorValoracion.appendChild(estrella);
                    }

                    CajaComentariosDeSitio.prepend(li);
                    
                    document.querySelector(".info-comment-" + data.id_comentario).insertAdjacentElement("afterend", ContenedorValoracion);
                    document.querySelector(`[data-inputpublicacion${data.id_sitio}]`).value = '';
                    document.querySelector(`[data-contadorchar${data.id_sitio}]`).textContent = 'Límite de caracteres: 0/255';
                    document.getElementById("comment-error-msg" + data.id_sitio).textContent = '';

                }else{
                    document.getElementById("comment-error-msg" + data.id_sitio).textContent = data.message;
                }
            });
        } else {
            console.log("La palabra 'UIE/' no se encontró en la URL.");
        }
    }
});


function openPopup(idComentario) {
    window.open(`../Vistas/VIS_DenunciaComentario.php?idComentario=${idComentario}`, 'Denunciar Comentario', 'width=800,height=600,scrollbars=yes');
}

//Listener para las estrellas de un sitio clickeado por el usuario
document.querySelectorAll(".tarjeta-turistica").forEach( (e) => {

    e.addEventListener("click", function (){

        const Estrellas = document.querySelectorAll('.estrella-sitio' + e.dataset.sitioId);
        const ratingValue = document.querySelector('.valoracion-sitio' + e.dataset.sitioId);
        let currentRating = 0;

        Estrellas.forEach(estrella => {

            estrella.addEventListener('mouseover', function() {
                resetEstrellas(Estrellas);
                iluminarEstrellas(this.dataset.value, Estrellas);
            });

            estrella.addEventListener('mouseout', function() {
                resetEstrellas(Estrellas);
                if (currentRating) iluminarEstrellas(currentRating, Estrellas);
            });

            estrella.addEventListener('click', function() {
                currentRating = this.dataset.value;
                ratingValue.value = currentRating;
                iluminarEstrellas(currentRating, Estrellas);
            });
        });
    });

    
});

function iluminarEstrellas(rating, Estrellas) {
    for (let i = 0; i < rating; i++) {
        Estrellas[i].classList.add('hover');
    }
}

function resetEstrellas(Estrellas) {
    Estrellas.forEach(estrella => {
        estrella.classList.remove('hover');
    });
}