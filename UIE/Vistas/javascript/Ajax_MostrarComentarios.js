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
                    alert("Comentario eliminado correctamente.");
                } else {
                    alert("Error al eliminar el comentario.");
                }
            })
            .catch(error => {
                console.error("Error al eliminar el comentario:", error);
            });
        }
    }
}



function habilitarEdicion(idComentario) {
    const comentarioText = document.getElementById(`comentario-text-${idComentario}`);
    const comentarioActual = comentarioText.textContent;

    // Cambiar el texto a un campo de entrada
    comentarioText.innerHTML = `
        <textarea class="form-control" id="textarea-${idComentario}">${comentarioActual}</textarea>
        <button type="button" class="btn btn-sm btn-primary mt-2" onclick="guardarComentario(${idComentario})">Guardar</button>
        <button type="button" class="btn btn-sm btn-secondary mt-2" onclick="cancelarEdicion(${idComentario}, '${comentarioActual}')">Cancelar</button>
    `;
}




function guardarComentario(idComentario) {
    const textoArea = document.getElementById(`textarea-${idComentario}`);
    const nuevoComentario = textoArea.value;

    if (nuevoComentario.trim() === '') {
        alert('El comentario no puede estar vacío.');
        return;
    }

    let urlActual = window.location.href;
    let palabraClave = "UIE/";
    let indice = urlActual.indexOf(palabraClave);
console.log("ID del comentario: ", idComentario);
console.log("Nuevo comentario: ", nuevoComentario);

    if (indice !== -1) {
        let urlCortada = urlActual.substring(0, indice + palabraClave.length);
        fetch(urlCortada + "Controlador/CON_ActualizarComentario.php", {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: `id_comentario=${idComentario}&nuevo_comentario=${encodeURIComponent(nuevoComentario)}`
        })
        .then(response => response.json())
        
        .then(data => {
            if (data.success) {
                // Actualizar el comentario en la interfaz
                const comentarioText = document.getElementById(`comentario-text-${idComentario}`);
                comentarioText.textContent = nuevoComentario;
                alert('Comentario actualizado correctamente.');
            } else {
                alert('Error al actualizar el comentario.');
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