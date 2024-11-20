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
    const enlacesMenu = document.querySelectorAll('.link-seccion');

    enlacesMenu.forEach(item => {
        item.classList.remove('nav-link-activo');
    });
}

function adaptarNavHeader(href){
    const itemsNavHeader = document.querySelectorAll(".link-seccion");

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
        ul.style.maxHeight= "200px"; //estilos para poder scrollear dentro del drop
        ul.style.overflowY= "auto";
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

                ContenedorSitios.innerHTML = `<div class="w-100 h-75 align-content-center text-center"><h3>Aún no tienes sitios favoritos</h3></div>`;

                const map = new google.maps.Map(document.getElementById("map"), {
                    zoom: 10,
                    center: { lat: -34.64877586247709, lng: -58.444786860971085 }
                });

                return null; // No hay contenido
            }

            // Convierto a JSON
            const data = await res.json();

            
            if (Array.isArray(data) && data.length > 0) {
                
                /* console.log('Resultado de sitios favoritos:', data); */

                // Inicializar el mapa
                const map = new google.maps.Map(document.getElementById("map"), {
                    zoom: 6,
                    center: { lat: 0, lng: 0 },
                });

                const marcadoresActuales = [];

                const bounds = new google.maps.LatLngBounds();

                const PromesasFetch = data.map(async (e) => {

                    const position = { lat: parseFloat(e.latitud), lng: parseFloat(e.longitud) };

                    marcadoresActuales.push( { lat: parseFloat(e.latitud), lng: parseFloat(e.longitud) });

                    const marker = new google.maps.Marker({
                        position,
                        map,
                        title: e.descripcion,
                        label: {
                            text: "♥",
                            color: "white",
                            fontSize: "15px"
                        },
                        optimized: false,
                    });

                    bounds.extend(marker.position);

                    // Agregar un listener de clic para cada marcador y configurar la ventana de información
                    agregarListenerMarcador(marker, e.id_sitio, e.descripcion, e.nombre, map);

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
                            data-sitio-id="${e.id_sitio}" 
                            data-nombre-sitio="${e.nombre}"  
                            data-categoria="${e.titulo_categoria}"
                            onclick="cargarMapaDesdeTarjeta(this);">
        
                                <img src="data:image/jpeg;base64,${e.bin_imagen}" alt="Imagen de destino" class="card-img-top">
                                <div class="contenido-tarjeta${e.id_sitio}">
                                    <h5 class="titulo-lugar">${e.nombre}</h5>
                                    <p class="categoria-lugar">${e.titulo_categoria}</p>
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
                            <span class="text-secondary ms-2">(${data.cant_valoraciones}${data.cant_valoraciones == 1 ?' reseña)' :' reseñas)'}</span>
                        `;

                        document.querySelector(`.contenido-tarjeta${e.id_sitio}`).appendChild(ContenedorValoracion);

                        //INFO DE MODAL

                        await fetch(urlCortada + 'Controlador/CON_ObtenerImagenesSitio.php', {
                            method: 'POST',  // Tipo de solicitud
                            headers: {
                                'Content-Type': 'application/x-www-form-urlencoded'  // Para enviar datos como formulario
                            },
                            body: `id=${e.id_sitio}`  // Enviar el id como parámetro
                        })
                        .then(async response => await response.json())
                        .then(async allImages => {

                            /* console.log(allImages); */
                            ContenedorSitios.innerHTML += `
                                <div class="modal fade" id="modal${e.id_sitio}" tabindex="-1" aria-labelledby="exampleModalLabel${e.id_sitio}" aria-hidden="true">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header border border-0">
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body d-flex flex-column">
                                                <div class="carouselModal">
                                                    <div class="carousel-images carousel${e.id_sitio}">
                                                        
                                                    </div>
                                                    <button class="buttonCarrouselModal prev"><i class="bi bi-arrow-left-circle"></i></button>
                                                    <button class="buttonCarrouselModal next"><i class="bi bi-arrow-right-circle"></i></button>
                                                </div>
                                                <div class="mt-3 d-flex align-content-start flex-wrap justify-content-between">
                                                    <div>
                                                        <h3 class="ms-2 modal-title" id="exampleModalLabel${e.id_sitio}">${e.nombre}</h3>
    
                                                        <div class="valoracion d-flex flex-row mx-2 align-items-center valoracion-promedio${e.id_sitio}"></div>
    
                                                    </div>
    
                                                    <form method="POST" class="d-flex flex-column fav-form" data-postid="${e.id_sitio}">
                                                                
                                                        <input type="hidden" name="id_sitio" value="${e.id_sitio}">
    
                                                        <button type="submit" data-fav-btn${e.id_sitio} class="btn btn-outline-danger rounded favorito-activo">
                                                                Eliminar de favoritos <i class="bi bi-heart-fill"></i>
                                                        </button>
                                                            
                                                    </form>
    
                                                </div>
                                                <div>
                                                    <p class="categoria-lugar">${e.titulo_categoria}</p>
                                                </div>
                                                <div class="p-3 mt-0">
                                                    <p class="ms-2 textoModal">${e.descripcion}</p>
                                                </div>
                                                <div class="contaniner-fluid row">
                                                    <div class="col-lg-6">
                                                        <p class="ms-2 textoModal">Localidad: ${e.localidad}</p>
                                                    </div>
                                                    <div class="col-lg-6">
                                                        <p class="ms-2 textoModal" id="IDArancelamientoSitioModal">Es arancelado: ${e.tarifa == 1 ?'Si' :'No'} </p>
                                                    </div>
                                                    <div class="position-relative mt-3 mb-3 p-2">
                                                        <div class="position-absolute top-0 start-50 translate-middle">
                                                            <p class="ms-2 textoModal" id="IDHorariosSitioModal">Horarios: ${e.horarios}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
    
                                            <div id="seccion-comentarios-${e.id_sitio}" class="w-100">
                                                <div class="w-100 p-3 mx-auto border-top">
    
                                                    <h3 class="text-center m-2">Opiniones</h3>
    
                                                    <div class="row">
                                                            <div class="col-md-8 mx-auto">
                                                                <span class="text-danger" id="comment-error-msg${e.id_sitio}"></span>
                                                                <!-- Formulario para agregar comentarios -->
                                                                <form method="post" class="comentarios-form">
                                                                    <div class="form-group">
    
                                                                        <textarea class="form-control border border-info-subtle" name="descripcion" maxlength="255" rows="4" cols="50" placeholder="¿Qué opinas sobre este sitio?" data-inputpublicacion${e.id_sitio} required></textarea>
                                                                    
                                                                    </div>
                                                                    <input type="hidden" name="id_sitio" value="${e.id_sitio}"> <!-- Campo oculto para el id_sitio -->
                                                                    <div class="my-1 d-flex flex-row justify-content-between align-items-center">
                                                                        <div class="valoracion" data-value="0">
                                                                            <span class="estrella estrella-sitio${e.id_sitio}" data-value="1">&#9733;</span>
                                                                            <span class="estrella estrella-sitio${e.id_sitio}" data-value="2">&#9733;</span>
                                                                            <span class="estrella estrella-sitio${e.id_sitio}" data-value="3">&#9733;</span>
                                                                            <span class="estrella estrella-sitio${e.id_sitio}" data-value="4">&#9733;</span>
                                                                            <span class="estrella estrella-sitio${e.id_sitio}" data-value="5">&#9733;</span>
                                                                        </div>
                                                                        <input type="hidden" name="valoracion" class="valoracion-sitio${e.id_sitio}" value="0">
                                                                        <p class="ms-3 m-0" data-contadorchar${e.id_sitio}>Límite de caracteres: 0/255</p>
                                                                    </div>
    
                                                                    <div class="d-flex justify-content-center">
                                                                        <button type="submit" class="btn btn-primary mb-3">Publicar mi opinión</button>
                                                                    </div>
    
                                                                </form>
                                                            </div>
                                                    </div>
    
                                                    <div class="comentarios-container border-top">
                                                        <ul id="lista-comentarios-${e.id_sitio}" class="list-unstyled"></ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                `;
    
                            document.querySelector(`.valoracion-promedio${e.id_sitio}`).appendChild(ContenedorValoracion);
                            const carousel = document.querySelector(`.carousel${e.id_sitio}`);
                            
                            allImages.forEach( (e, index) => {
                                carousel.innerHTML += `<img src="data:image/jpeg;base64,${e.bin_imagen}" class="img-fluid" alt="Imagen del sitio ${index+1}">`;
                            });

                        });
                        
                    })
                    .catch(error => {
                        console.error('Error:', error);
                    });
                });

                await Promise.all(PromesasFetch);

                map.fitBounds(bounds, {
                    top: 50,    // Padding superior
                    bottom: 150, // Padding inferior
                    left: 50,   // Padding izquierdo
                    right: 50   // Padding derecho
                });

                setearFiltrosSitiosFavoritos();
                
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

                ContenedorSitios.innerHTML = `<div class="w-100 h-75 align-content-center text-center"><h3>Aún no tienes sitios publicados</h3></div>`;
                const map = new google.maps.Map(document.getElementById("map"), {
                    zoom: 10,
                    center: { lat: -34.64877586247709, lng: -58.444786860971085 }
                });

                return null; // No hay contenido
            }

            // Convierto a JSON
            const data = await res.json();

            if (Array.isArray(data) && data.length > 0) {

                console.log('Resultado de sitios propios:', data);

                // Inicializar el mapa
                const map = new google.maps.Map(document.getElementById("map"), {
                    zoom: 6,
                    center: { lat: 0, lng: 0 },
                });

                const marcadoresActuales = [];

                const bounds = new google.maps.LatLngBounds();

                const PromesasFetch = data.map( async(e) => {

                    const position = { lat: parseFloat(e.latitud), lng: parseFloat(e.longitud) };

                    marcadoresActuales.push( { lat: parseFloat(e.latitud), lng: parseFloat(e.longitud) });

                    const marker = new google.maps.Marker({
                        position,
                        map,
                        title: e.descripcion,
                        label: {
                            text: `★`,
                            color: "white",
                            fontSize: "15px"
                        },
                        optimized: false,
                    });

                    bounds.extend(marker.position);

                    // Agregar un listener de clic para cada marcador y configurar la ventana de información
                    agregarListenerMarcador(marker, e.id_sitio, e.descripcion, e.nombre, map);

                    let valoracionTotal = 0;

                    await fetch(urlCortada2 + 'Controlador/CON_ObtenerValoracionSitio.php', {
                        method: 'POST',  // Tipo de solicitud
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded'  // Para enviar datos como formulario
                        },
                        body: `id_sitio_valorado=${e.id_sitio}`  // Enviar el id como parámetro
                    })
                    .then(async response => await response.json())
                    .then(async respuestaValoracion => {

                        //console.log('Respuesta del servidor:', respuestaValoracion);

                        valoracionTotal = respuestaValoracion.valoracion;

                        let estadoAprobacion = '';
                        let dataEstado = '';
                        let bg = '';

                        if (e.estado == 1) {
                            estadoAprobacion = 'Aprobado';
                            dataEstado = 'aprobados';
                            bg = 'bg-primary';
                        }else if(e.estado == 0){
                            estadoAprobacion = 'En revisión';
                            dataEstado = 'pendientes';
                            bg = 'bg-secondary';
                        }else if(e.estado == 2){
                            estadoAprobacion = 'Rechazado';
                            dataEstado = 'rechazados';
                            bg = 'bg-danger';
                        }else{
                            estadoAprobacion = 'Estado indefinido';
                            dataEstado = 'pendientes';
                            bg = 'bg-secondary';
                        }

                        ContenedorSitios.innerHTML += `
                            <div class="tarjeta-turistica card" 
                            data-sitio-id="${e.id_sitio}"
                            data-estado="${dataEstado}"
                            onclick="cargarMapaDesdeTarjeta(this);">
        
                                <img src="data:image/jpeg;base64,${e.bin_imagen}" alt="Imagen de destino" class="card-img-top">

                                <div class="contenido-tarjeta${e.id_sitio}">
                                    <h5 class="titulo-lugar">${e.nombre}</h5>
                                    <p class="categoria-lugar">${e.titulo_categoria}</p>
                                    <p class="categoria-lugar rounded-pill text-white m-0 ${bg}">${estadoAprobacion}</p>
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
                            <span class="text-secondary ms-2">(${respuestaValoracion.cant_valoraciones}${respuestaValoracion.cant_valoraciones == 1 ?' reseña)' :' reseñas)'}</span>
                        `;

                        document.querySelector(`.contenido-tarjeta${e.id_sitio}`).appendChild(ContenedorValoracion);

                        //INFO DE MODAL

                        await fetch(urlCortada2 + 'Controlador/CON_ObtenerImagenesSitio.php', {
                            method: 'POST',  // Tipo de solicitud
                            headers: {
                                'Content-Type': 'application/x-www-form-urlencoded'  // Para enviar datos como formulario
                            },
                            body: `id=${e.id_sitio}`  // Enviar el id como parámetro
                        })
                        .then(async response => await response.json())
                        .then(async allImages => {

                            /* console.log(allImages); */

                            ContenedorSitios.innerHTML += `
                                <div class="modal fade" id="modal${e.id_sitio}" tabindex="-1" aria-labelledby="exampleModalLabel${e.id_sitio}" aria-hidden="true">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header border border-0">
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body d-flex flex-column">
    
                                                <div class="carouselModal">
                                                    <div class="carousel-images carousel${e.id_sitio}">
                                                        
                                                    </div>
                                                    <button class="buttonCarrouselModal prev"><i class="bi bi-arrow-left-circle"></i></button>
                                                    <button class="buttonCarrouselModal next"><i class="bi bi-arrow-right-circle"></i></button>
                                                </div>
    
                                                <div class="mt-3 d-flex align-content-start flex-wrap justify-content-between">
                                                    <div>
                                                        <h3 class="ms-2 modal-title" id="exampleModalLabel${e.id_sitio}">${e.nombre}</h3>
    
                                                        <div class="valoracion d-flex flex-row mx-2 align-items-center valoracion-promedio${e.id_sitio}"></div>
    
                                                    </div>
    
                                                    <p class="categoria-lugar rounded-pill text-white m-0 fs-5 ${bg}" style="line-height: 3rem;">${estadoAprobacion}</p>
    
                                                </div>
                                                <div>
                                                    <p class="categoria-lugar">${e.titulo_categoria}</p>
                                                </div>
                                                <div class="p-3 mt-0">
                                                    <p class="ms-2 textoModal">${e.descripcion}</p>
                                                </div>
                                                <div class="contaniner-fluid row">
                                                    <div class="col-lg-6">
                                                        <p class="ms-2 textoModal">Localidad: ${e.localidad}</p>
                                                    </div>
                                                    <div class="col-lg-6">
                                                        <p class="ms-2 textoModal" id="IDArancelamientoSitioModal">Es arancelado: </p>
                                                    </div>
                                                    <div class="position-relative mt-3 mb-3 p-2">
                                                        <div class="position-absolute top-0 start-50 translate-middle">
                                                            <p class="ms-2 textoModal" id="IDHorariosSitioModal">Horarios: </p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
    
                                            <div id="seccion-comentarios-${e.id_sitio}" class="w-100">
                                                <div class="w-100 p-3 mx-auto border-top">
    
                                                    <h3 class="text-center m-2">Opiniones</h3>
    
                                                    <div class="row">
                                                            <div class="col-md-8 mx-auto">
                                                                <span class="text-danger" id="comment-error-msg${e.id_sitio}"></span>
                                                                <!-- Formulario para agregar comentarios -->
                                                                <form method="post" class="comentarios-form">
                                                                    <div class="form-group">
    
                                                                        <textarea class="form-control border border-info-subtle" name="descripcion" maxlength="255" rows="4" cols="50" placeholder="¿Qué opinas sobre este sitio?" data-inputpublicacion${e.id_sitio} required></textarea>
                                                                    
                                                                    </div>
                                                                    <input type="hidden" name="id_sitio" value="${e.id_sitio}"> <!-- Campo oculto para el id_sitio -->
                                                                    <div class="my-1 d-flex flex-row justify-content-between align-items-center">
                                                                        <div class="valoracion" data-value="0">
                                                                            <span class="estrella estrella-sitio${e.id_sitio}" data-value="1">&#9733;</span>
                                                                            <span class="estrella estrella-sitio${e.id_sitio}" data-value="2">&#9733;</span>
                                                                            <span class="estrella estrella-sitio${e.id_sitio}" data-value="3">&#9733;</span>
                                                                            <span class="estrella estrella-sitio${e.id_sitio}" data-value="4">&#9733;</span>
                                                                            <span class="estrella estrella-sitio${e.id_sitio}" data-value="5">&#9733;</span>
                                                                        </div>
                                                                        <input type="hidden" name="valoracion" class="valoracion-sitio${e.id_sitio}" value="0">
                                                                        <p class="ms-3 m-0" data-contadorchar${e.id_sitio}>Límite de caracteres: 0/255</p>
                                                                    </div>
    
                                                                    <div class="d-flex justify-content-center">
                                                                        <button type="submit" class="btn btn-primary mb-3">Publicar mi opinión</button>
                                                                    </div>
    
                                                                </form>
                                                            </div>
                                                    </div>
    
                                                    <div class="comentarios-container border-top">
                                                        <ul id="lista-comentarios-${e.id_sitio}" class="list-unstyled"></ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            `;
    
                            document.querySelector(`.valoracion-promedio${e.id_sitio}`).appendChild(ContenedorValoracion);

                            const carousel = document.querySelector(`.carousel${e.id_sitio}`);
                            
                            allImages.forEach( (e, index) => {
                                carousel.innerHTML += `<img src="data:image/jpeg;base64,${e.bin_imagen}" class="img-fluid" alt="Imagen del sitio ${index+1}">`;
                            });
                        });


                    })
                    .catch(error => {
                        console.error('Error:', error);
                    });
                });

                await Promise.all(PromesasFetch);

                map.fitBounds(bounds, {
                    top: 50,    // Padding superior
                    bottom: 150, // Padding inferior
                    left: 50,   // Padding izquierdo
                    right: 50   // Padding derecho
                });

                setearFiltrosMisSitios();

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