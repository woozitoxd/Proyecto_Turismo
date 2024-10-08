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

function manejarContenido(seccion){

    const TituloSeccion = document.getElementById("section-title");

    switch (seccion) {
   
        case '#favoritos':

            TituloSeccion.textContent = "Mis sitios favoritos";
            
            obtenerSitiosFavoritos();
            
            adaptarNavHeader("#favoritos");
        break;
            
        case '#MisSitios':
                
            TituloSeccion.textContent = "Mis sitios publicados";

            obtenerPublicacionesPropias();

            adaptarNavHeader("#MisSitios");
        break;

        default:
        
            location.reload(true);

            adaptarNavHeader("#");

        break;
    }
}

let URL = window.location.href;
let path = "UIE/";

// Encuentra el índice de la palabra "UIE/" en la URL
let indiceDePath = URL.indexOf(path);

function obtenerSitiosFavoritos(){

    const ContenedorSitios = document.querySelector(".bloque-lugares");

    ContenedorSitios.innerHTML = ``;

    ContenedorSitios.innerHTML += `<script defer src="../Vistas/javascript/Favoritos.js"></script>
                                <script defer src="../Vistas/javascript/Ajax_MostrarComentarios.js"></script>
                                <link rel="stylesheet" href="../Vistas/estilos/comentarios.css">`;


    //ACA MANEJO EL FETCH CON LOS SITIOS FAVORITOS QUE TRAIGA

    if (indiceDePath !== -1) {

        // Guarda la URL desde el inicio hasta la palabra "UIE/"
        let urlCortada = URL.substring(0, indiceDePath + path.length);

        fetch(urlCortada + "Controlador/CON_SitiosFavoritos.php", {
            method: "GET",
            headers: {
                "Content-Type": "application/json"
            }
        })
        .then(res => {

            // Verifico si la respuesta fue exitosa
            if (!res.ok) {
                throw new Error('Error en la solicitud: ' + response.status);
            }

            // Verifico si hay contenido en la respuesta
            if (res.headers.get('content-length') === '0') {
                return null; // No hay contenido
            }

            // Convierto a JSON
            return res.json();
        })
        .then(data => {

            if (Array.isArray(data) && data.length > 0) {

                console.log('Resultados encontrados:', data);

                data.forEach( (e) => {
                    ContenedorSitios.innerHTML += `
                        <div class="tarjeta-turistica card" 
                            data-bs-toggle="modal" 
                            data-sitio-id="${e.id_sitio}" 
                            data-bs-target="#modal${e.id_sitio}" 
                            onclick="cargarMapaDesdeTarjeta(this); cargarComentario(this.dataset.sitioId);">
    
                            <img src="data:image/jpeg;base64,${e.bin_imagen}" alt="Imagen de destino" class="card-img-top">
                            <div class="contenido-tarjeta">
                                <h5 class="titulo-lugar">${e.nombre}</h5>
                                <p class="etiquetas-lugar">${e.titulo}</p>
                                <p class="descripcion-lugar">${e.descripcion}</p>
                                <div class="valoracion">
                                    <span class="estrella">&#9733;</span> <!-- Estrella llena -->
                                    <span class="estrella">&#9733;</span> <!-- Estrella llena -->
                                    <span class="estrella">&#9733;</span> <!-- Estrella llena -->
                                    <span class="estrella">&#9734;</span> <!-- Estrella vacía -->
                                    <span class="estrella">&#9734;</span> <!-- Estrella vacía -->
                                </div>
                            </div>
                        </div>
                    `;
                });
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

function obtenerPublicacionesPropias(){

    const ContenedorSitios = document.querySelector(".bloque-lugares");

    ContenedorSitios.innerHTML = ``;

    ContenedorSitios.innerHTML += `<script defer src="../Vistas/javascript/Favoritos.js"></script>
                                <script defer src="../Vistas/javascript/Ajax_MostrarComentarios.js"></script>
                                <link rel="stylesheet" href="../Vistas/estilos/comentarios.css">`;


    //ACA MANEJO EL FETCH CON LOS SITIOS PROPIOS QUE TRAIGA
    
    if (indiceDePath !== -1) {

        // Guarda la URL desde el inicio hasta la palabra "UIE/"
        let urlCortada2 = URL.substring(0, indiceDePath + path.length);

        fetch(urlCortada2 + "Controlador/CON_SitiosPropios.php", {
            method: "GET",
            headers: {
                "Content-Type": "application/json"
            }
        })
        .then(res => {
            // Verifico si la respuesta fue exitosa
            if (!res.ok) {
                throw new Error('Error en la solicitud: ' + response.status);
            }

            // Verifico si hay contenido en la respuesta
            if (res.headers.get('content-length') === '0') {
                return null; // No hay contenido
            }

            // Convierto a JSON
            return res.json();
        })
        .then(data => {

            if (Array.isArray(data) && data.length > 0) {

                console.log('Resultados encontrados:', data);

                data.forEach( (e) => {
                    ContenedorSitios.innerHTML += `
                        <div class="tarjeta-turistica card" 
                            data-bs-toggle="modal" 
                            data-sitio-id="${e.id_sitio}" 
                            data-bs-target="#modal${e.id_sitio}" 
                            onclick="cargarMapaDesdeTarjeta(this); cargarComentario(this.dataset.sitioId);">
    
                            <img src="data:image/jpeg;base64,${e.bin_imagen}" alt="Imagen de destino" class="card-img-top">
                            <div class="contenido-tarjeta">
                                <h5 class="titulo-lugar">${e.nombre}</h5>
                                <p class="etiquetas-lugar">${e.titulo}</p>
                                <p class="descripcion-lugar">${e.descripcion}</p>
                                <div class="valoracion">
                                    <span class="estrella">&#9733;</span> <!-- Estrella llena -->
                                    <span class="estrella">&#9733;</span> <!-- Estrella llena -->
                                    <span class="estrella">&#9733;</span> <!-- Estrella llena -->
                                    <span class="estrella">&#9734;</span> <!-- Estrella vacía -->
                                    <span class="estrella">&#9734;</span> <!-- Estrella vacía -->
                                </div>
                            </div>
                        </div>
                    `;
                });
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