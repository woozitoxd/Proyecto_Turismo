document.addEventListener("DOMContentLoaded", function (){

    verificarSeccion();

    document.querySelectorAll('.navbar-nav a').forEach(item => {

        item.addEventListener('click', function() {

            limpiarNavHeader();

            const seccion = item.getAttribute('href');

            manejarContenido(seccion);

            if (seccion != "#") item.classList.add('nav-link-activo');

            //console.log(item);
        });
    });

});

function limpiarNavHeader() {
    const enlacesMenu = document.querySelectorAll('.nav-link');

    enlacesMenu.forEach(item => {
        item.classList.remove('nav-link-activo');
    });
}

function adaptarNavHeader(href){
    const itemsNavHeader = document.querySelectorAll(".nav-link");

    itemsNavHeader.forEach((e) => {
        //console.log("#" + e.href.split("#")[1]);
        //console.log(href);
        if ( ("#" + e.href.split("#")[1]) == href) {
            e.classList.add('nav-link-activo');
        }else{
            e.classList.remove('nav-link-activo');
        }
    });
};

function verificarSeccion(){

    // Obtenengo el fragmento de la URL después del #
    const seccion = location.hash;

    // Verificar si existe el fragmento de la URL
    if (seccion) {
        manejarContenido(seccion);
    }

};

let URL = window.location.href;
let path = "UIE/";

// Encuentra el índice de la palabra "UIE/" en la URL
let indiceDePath = URL.indexOf(path);

async function manejarContenido(seccion){

    const HeaderSeccion = document.getElementById("section-title");

    HeaderSeccion.innerHTML = ``;

    const DropdownMenu = document.createElement('div');
        DropdownMenu.classList.add('btn-group', 'dropdown', 'ms-2');

    const button = document.createElement("button");
            button.type = "button";
            button.className = "btn fs-3 fst-italic text-primary fw-medium dropdown-toggle";
            button.id = "dropdownSeccionFiltro";
            button.setAttribute("data-bs-toggle", "dropdown");
            button.setAttribute("aria-expanded", "false");

    const ul = document.createElement("ul");
        ul.className = "dropdown-menu w-100 text-center fs-5 border border-primary-subtle";
        ul.setAttribute("aria-labelledby", "dropdownSeccionFiltro");

        ul.innerHTML = ``;

    if (seccion == "#favoritos") {

        button.textContent = "Mis sitios favoritos";

        DropdownMenu.appendChild(button);

        ul.innerHTML += `<li><a class="dropdown-item filtro-seccion text-primary" data-filtro="todos" href="#">Todos</a></li>`;

        if (indiceDePath !== -1) {

            // Guarda la URL desde el inicio hasta la palabra "UIE/"
            let urlCortada = URL.substring(0, indiceDePath + path.length);
        
            await fetch(urlCortada + "Controlador/CON_ObtenerCategorias.php", {
                method: "GET",
                headers: {
                    "Content-Type": "application/json"
                }
            })
            .then(async res => {
        
                // Verifico si la respuesta fue exitosa
                if (!res.ok) {
                    throw new Error('Error en la solicitud: ' + response.status);
                }
        
                // Verifico si hay contenido en la respuesta
                if (res.headers.get('content-length') === '0') {
                    return null; // No hay contenido
                }
        
                // Convierto a JSON
                return await res.json();
            })
            .then(data => {
        
                /* console.log('Resultados encontrados:', data); */
        
                data.forEach( (e) => {
                    ul.innerHTML += `<li><a class="dropdown-item filtro-seccion text-primary overflow-hidden" data-filtro="${e.titulo}" href="#">${e.titulo}</a></li>`;
                });
        
            })
            .catch(error => {
                // Manejo los errores en caso de que la solicitud o el fetch falle
                console.error('Error:', error);
            });
        
        } else {
            console.log("La palabra 'UIE/' no se encontró en la URL.");
        }

        DropdownMenu.appendChild(ul);

        HeaderSeccion.appendChild(DropdownMenu);
            
        await obtenerSitiosFavoritos();
            
        adaptarNavHeader("#favoritos");

    }else if(seccion == "#MisSitios"){

        button.textContent = "Mis sitios publicados";

        DropdownMenu.appendChild(button);

        ul.innerHTML += `<li><a class="dropdown-item filtro-seccion text-primary" data-filtro="todos" href="#">Todos</a></li>`;
        ul.innerHTML += `<li><a class="dropdown-item filtro-seccion text-primary" data-filtro="aprobados" href="#">Aprobados</a></li>`;
        ul.innerHTML += `<li><a class="dropdown-item filtro-seccion text-primary" data-filtro="pendientes" href="#">En revisión</a></li>`;

        DropdownMenu.appendChild(ul);

        HeaderSeccion.appendChild(DropdownMenu);

        await obtenerPublicacionesPropias();

        adaptarNavHeader("#MisSitios");

    }else{

        location.reload(true);

        adaptarNavHeader("#");
    }
}

function setearFiltrosSitiosFavoritos(){
    const dropdownItems = document.querySelectorAll(".filtro-seccion");
    const tarjetas = document.querySelectorAll(".tarjeta-turistica");
    /* console.log(tarjetas); */

    // Filtrar por categoría desde el dropdown
    dropdownItems.forEach(item => {
        item.addEventListener("click", function(event) {
            event.preventDefault();

            dropdownItems.forEach( (e) => {
                e.classList.remove("active");
                e.classList.remove("text-white");
            });
                    
            event.target.classList.add("active");
            event.target.classList.add("text-white");

            const filtro = item.getAttribute("data-filtro").toLowerCase();

            tarjetas.forEach(tarjeta => {

                // Validar si la categoría del sitio está definida
                const categoriaSitio = tarjeta.dataset.categoria ? tarjeta.dataset.categoria.toLowerCase() : '';

                // Muestra u oculta la publicación según el filtro seleccionado

                if (filtro == "todos" || categoriaSitio.includes(filtro) ) {
                    tarjeta.style.display = "block"; // Mostrar tarjeta si coincide con el filtro
                } else {
                    tarjeta.style.display = "none"; // Ocultar tarjeta si no coincide
                }
            });
        });
    });
}

async function obtenerSitiosFavoritos(){

    const ContenedorSitios = document.querySelector(".bloque-lugares");

    ContenedorSitios.innerHTML = ``;

    ContenedorSitios.innerHTML += `<script defer src="../Vistas/javascript/Favoritos.js"></script>
                                <script defer src="../Vistas/javascript/Ajax_MostrarComentarios.js"></script>
                                <link rel="stylesheet" href="../Vistas/estilos/comentarios.css">`;


    //ACA MANEJO EL FETCH CON LOS SITIOS FAVORITOS QUE TRAIGA

    if (indiceDePath !== -1) {

        // Guarda la URL desde el inicio hasta la palabra "UIE/"
        let urlCortada = URL.substring(0, indiceDePath + path.length);

        await fetch(urlCortada + "Controlador/CON_SitiosFavoritos.php", {
            method: "GET",
            headers: {
                "Content-Type": "application/json"
            }
        })
        .then( async res => {

            // Verifico si la respuesta fue exitosa
            if (!res.ok) {
                throw new Error('Error en la solicitud: ' + response.status);
            }

            // Verifico si hay contenido en la respuesta
            if (res.headers.get('content-length') === '0') {
                return null; // No hay contenido
            }

            // Convierto a JSON
            const data = await res.json();

            if (Array.isArray(data) && data.length > 0) {

                console.log('Resultado de sitios favoritos:', data);

                const PromesasFetch = data.map(async (e) => {

                    let valoracionTotal = 0;

                    await fetch(urlCortada + 'Controlador/CON_ObtenerValoracionSitio.php', {
                        method: 'POST',  // Tipo de solicitud
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded'  // Para enviar datos como formulario
                        },
                        body: `id_sitio_valorado=${e.id_sitio}`  // Enviar el id como parámetro
                    })
                    .then(async response => await response.json())
                    .then(async data => {
                        //console.log('Respuesta del servidor:', data);

                        valoracionTotal = data.valoracion;

                        ContenedorSitios.innerHTML += `
                            <div class="tarjeta-turistica card" 
                            data-bs-toggle="modal" 
                            data-sitio-id="${e.id_sitio}" 
                            data-nombre-sitio="${e.nombre}"  
                            data-categoria="${e.titulo}"
                            data-bs-target="#modal${e.id_sitio}" 
                            onclick="cargarMapaDesdeTarjeta(this); cargarComentario(this.dataset.sitioId);">
        
                                <img src="data:image/jpeg;base64,${e.bin_imagen}" alt="Imagen de destino" class="card-img-top">
                                <div class="contenido-tarjeta${e.id_sitio}">
                                    <h5 class="titulo-lugar">${e.nombre}</h5>
                                    <p class="etiquetas-lugar">${e.titulo}</p>
                                    <p class="descripcion-lugar">${e.descripcion}</p>
                                </div>
                            </div>
                        `;

                        const ContenedorValoracion = document.createElement("div");
        
                        ContenedorValoracion.className = "valoracion d-flex mx-2";
        
                        for (let index = 1; index <= 5; index++) {
        
                            const estrella = document.createElement("span");
        
                            if (index  <= Math.floor(valoracionTotal)) {
                                estrella.className = "star full"
                            }else if(index === Math.ceil(valoracionTotal) && (valoracionTotal - Math.floor(valoracionTotal)) > 0){
                                estrella.className = "star half";
                            }else{
                                estrella.className = "star";
                            }
        
                            ContenedorValoracion.appendChild(estrella);
        
                        }

                        ContenedorValoracion.innerHTML += `
                            <span class="text-secondary ms-2">${Math.floor(valoracionTotal * 10) / 10}</span>
                        `;

                        document.querySelector(`.contenido-tarjeta${e.id_sitio}`).appendChild(ContenedorValoracion);
                    })
                    .catch(error => {
                        console.error('Error:', error);
                    });
                });

                await Promise.all(PromesasFetch);

                setearFiltrosSitiosFavoritos();

            } else {
                ContenedorSitios.innerHTML = `<div class="w-100 h-75 align-content-center text-center"><h3>Aún no tienes sitios favoritos</h3></div>`;
            }
        })
        .catch(error => {
            // Manejo los errores en caso de que la solicitud o el fetch falle
            console.error('Error:', error);
        });

    } else {
        console.log("La palabra 'UIE/' no se encontró en la URL.");
    }
}

function setearFiltrosMisSitios(){

    const dropdownItems = document.querySelectorAll(".filtro-seccion");
    const tarjetas = document.querySelectorAll(".tarjeta-turistica");
    /* console.log(tarjetas); */

    // Filtrar por categoría desde el dropdown
    dropdownItems.forEach(item => {
        item.addEventListener("click", function(event) {
            event.preventDefault();
            dropdownItems.forEach( (e) => {
                e.classList.remove("active");
                e.classList.remove("text-white");
            });
                    
            event.target.classList.add("active");
            event.target.classList.add("text-white");
            const filtro = item.getAttribute("data-filtro").toLowerCase();
            tarjetas.forEach(tarjeta => {
                // Validar si el estado del sitio está definida
                const estadoSitioPublicado = tarjeta.dataset.estado ? tarjeta.dataset.estado.toLowerCase() : '';
                // Muestra u oculta la publicación según el filtro seleccionado
                if (filtro == "todos" || estadoSitioPublicado.includes(filtro) ) {
                    tarjeta.style.display = "block"; // Mostrar tarjeta si coincide con el filtro
                } else {
                    tarjeta.style.display = "none"; // Ocultar tarjeta si no coincide
                }
            });
        });
    });
}

async function obtenerPublicacionesPropias(){

    const ContenedorSitios = document.querySelector(".bloque-lugares");

    ContenedorSitios.innerHTML = ``;

    ContenedorSitios.innerHTML += `<script defer src="../Vistas/javascript/Favoritos.js"></script>
                                <script defer src="../Vistas/javascript/Ajax_MostrarComentarios.js"></script>
                                <link rel="stylesheet" href="../Vistas/estilos/comentarios.css">`;


    //ACA MANEJO EL FETCH CON LOS SITIOS PROPIOS QUE TRAIGA
    
    if (indiceDePath !== -1) {

        // Guarda la URL desde el inicio hasta la palabra "UIE/"
        let urlCortada2 = URL.substring(0, indiceDePath + path.length);

        await fetch(urlCortada2 + "Controlador/CON_SitiosPropios.php", {
            method: "GET",
            headers: {
                "Content-Type": "application/json"
            }
        })
        .then(async res => {

            // Verifico si la respuesta fue exitosa
            if (!res.ok) {
                throw new Error('Error en la solicitud: ' + res.status);
            }

            // Verifico si hay contenido en la respuesta
            if (res.headers.get('content-length') === '0') {
                return null; // No hay contenido
            }

            // Convierto a JSON
            const data = await res.json();

            if (Array.isArray(data) && data.length > 0) {

                console.log('Resultado de sitios propios:', data);

                const PromesasFetch = data.map( async(e) => {

                    let valoracionTotal = 0;

                    await fetch(urlCortada2 + 'Controlador/CON_ObtenerValoracionSitio.php', {
                        method: 'POST',  // Tipo de solicitud
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded'  // Para enviar datos como formulario
                        },
                        body: `id_sitio_valorado=${e.id_sitio}`  // Enviar el id como parámetro
                    })
                    .then(async response => {

                        // Verifico si la respuesta fue exitosa
                        if (!response.ok) {
                            throw new Error('Error en la solicitud: ' + response.status);
                        }

                        // Verifico si hay contenido en la respuesta
                        if (response.headers.get('content-length') === '0') {
                            return null; // No hay contenido
                        }

                        // Convierto a JSON
                        return await response.json();
                    })
                    .then(async respuestaValoracion => {
                        //console.log('Respuesta del servidor:', respuestaValoracion);

                        valoracionTotal = respuestaValoracion.valoracion;

                        ContenedorSitios.innerHTML += `
                            <div class="tarjeta-turistica card" 
                            data-bs-toggle="modal" 
                            data-sitio-id="${e.id_sitio}"
                            data-estado="${e.estado == 0 ?'aprobados' :'pendientes'}"
                            data-bs-target="#modal${e.id_sitio}" 
                            onclick="cargarMapaDesdeTarjeta(this); cargarComentario(this.dataset.sitioId);">
        
                                <img src="data:image/jpeg;base64,${e.bin_imagen}" alt="Imagen de destino" class="card-img-top">

                                <div class="contenido-tarjeta${e.id_sitio}">
                                    <h5 class="titulo-lugar">${e.nombre}</h5>
                                    <p class="etiquetas-lugar">${e.titulo}</p>
                                    <p class="etiquetas-lugar rounded-pill text-white m-0 ${e.estado == 0 ?'bg-primary' :'bg-secondary'}">${e.estado == 0 ?'aprobado' :'En revisión'}</p>
                                    <p class="descripcion-lugar">${e.descripcion}</p>
                                    
                                </div>

                            </div>
                        `;

                        const ContenedorValoracion = document.createElement("div");
        
                        ContenedorValoracion.className = "valoracion d-flex mx-2";
        
                        for (let index = 1; index <= 5; index++) {
        
                            const estrella = document.createElement("span");
        
                            if (index  <= Math.floor(valoracionTotal)) {
                                estrella.className = "star full"
                            }else if(index === Math.ceil(valoracionTotal) && (valoracionTotal - Math.floor(valoracionTotal)) > 0){
                                estrella.className = "star half";
                            }else{
                                estrella.className = "star";
                            }
        
                            ContenedorValoracion.appendChild(estrella);
        
                        }

                        ContenedorValoracion.innerHTML += `
                            <span class="text-secondary ms-2">${Math.floor(valoracionTotal * 10) / 10}</span>
                        `;

                        document.querySelector(`.contenido-tarjeta${e.id_sitio}`).appendChild(ContenedorValoracion);

                    })
                    .catch(error => {
                        console.error('Error:', error);
                    });
                });

                await Promise.all(PromesasFetch);

                setearFiltrosMisSitios();

            } else {
                ContenedorSitios.innerHTML = `<div class="w-100 h-75 align-content-center text-center"><h3>Aún no tienes sitios publicados</h3></div>`;
            }
        })
        .catch(error => {
            // Manejo los errores en caso de que la solicitud o el fetch falle
            console.error('Error:', error);
        });

    } else {
        console.log("La palabra 'UIE/' no se encontró en la URL.");
    }
}