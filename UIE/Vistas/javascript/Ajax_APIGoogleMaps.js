// Crear una ventana de información para compartir entre los marcadores

let globalMap;
let currentInfoWindow = null; // Variable global para rastrear la ventana abierta
// Ruta relativa al ícono en el directorio local
let customIcon ;
function iniciarmapa() {
    // Obtener la URL actual y crear la URL cortada para la petición
    let urlActual = window.location.href;
    let palabraClave = "UIE/";
    let indice = urlActual.indexOf(palabraClave);
    let urlCortada = "";

    // Verificar si se encuentra la palabra clave en la URL
    if (indice !== -1) {
        urlCortada = urlActual.substring(0, indice + palabraClave.length);
    }

    globalMap = new google.maps.Map(document.getElementById("map"), {
        zoom: 16,
        center: { lat: 0, lng: 0 },
        styles: [
            {
                featureType: "poi",
                elementType: "all",
                stylers: [
                    { visibility: "off" } // Ocultar puntos de interés
                ]
            },
            {
                featureType: "poi.park",
                elementType: "geometry",
                stylers: [{ visibility: "on" }, { color: "#green" }]
            },
            {
                featureType: "poi.park",
                elementType: "labels",
                stylers: [{ visibility: "off" }]
            },
            {
                "featureType": "road",
                "elementType": "geometry",
            },
            {
                "featureType": "road.local",
                "elementType": "labels",
                "stylers": [
                    { "visibility": "off" }
                ]
            },
            {
                "featureType": "transit.station.bus",
                "stylers": [{ "visibility": "off" }] // Ocultar paradas de colectivo
            },
            {
                "featureType": "transit.station.rail",
                "stylers": [{ "visibility": "on" }] // Mostrar estaciones de tren
            },
            
        ]
    });

    customIcon = {
        url: "./media/IconoSitio5.png", // Ruta relativa al ícono
        scaledSize: new google.maps.Size(50, 50), // Tamaño del ícono
        origin: new google.maps.Point(0, 0), // Origen de la imagen
        anchor: new google.maps.Point(25, 50) // Punto de anclaje

    };

    const marcadoresActuales = [];

    const bounds = new google.maps.LatLngBounds();

    // Obtener los datos de las coordenadas y descripciones desde el servidor
    fetch(urlCortada + "Controlador/CON_ObtenerCOOR.php")
        .then(response => response.json())
        .then(data => {

            // Verificar si se encontraron resultados
            if (data.error) {
                console.error(data.error);
                return;
            }

            // Crear los marcadores en el mapa
            data.forEach((sitio, i) => {
                const position = { lat: parseFloat(sitio.latitud), lng: parseFloat(sitio.longitud) };

                marcadoresActuales.push( { lat: parseFloat(sitio.latitud), lng: parseFloat(sitio.longitud) });

                const title = sitio.descripcion || `Sitio ${i + 1}`; // Usar la descripción si está disponible

                const marker = new google.maps.Marker({
                    position,
                    map: globalMap,
                    title: title,
                    label: {
                        text: (location.hash == "#favoritos" ?'♥' :`★`),
                        /* text: (location.hash == "#favoritos" ?'♥' :`${i+1}`), */
                        color: "white",
                        fontSize: "15px"
                    },
                    optimized: false,
                    icon: customIcon,
                    label: null 

                });

                bounds.extend(marker.position);

                // Agregar un listener de clic para cada marcador y configurar la ventana de información
                agregarListenerMarcador(marker, sitio.id_sitio);
            });

            globalMap.fitBounds(bounds, {
                top: 50,    // Padding superior
                bottom: 150, // Padding inferior
                left: 50,   // Padding izquierdo
                right: 50   // Padding derecho
            });
        })
        .catch(error => {
            console.error("Error al obtener los datos:", error);
        });
}

function agregarListenerMarcador(marker, idSitio) {

    console.log("Cargando mapa para el sitio con ID:", idSitio);

    marker.addListener("click", () => {
        console.log(`ID del sitio: ${idSitio}`); // Verificar el ID del sitio

        // Mover el mapa a la posición del marcador al hacer clic
        /* globalMap.setCenter(marker.getPosition()); */
        globalMap.setZoom(15); // Ajustar el zoom si es necesario

        // Obtener las coordenadas del servidor usando una petición POST
        fetch('../Controlador/CON_ObtenerCOOR.php', {
            method: "POST",
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: new URLSearchParams({ id: idSitio }) // Enviar el ID del sitio
        })
        .then(response => response.json())
        .then(data => {
            console.log("Datos recibidos del servidor:", data); // Verificar la respuesta

            // Verificar si hay un error en la respuesta
            if (data.error) {
                console.error(data.error);
                return; // Salir si hay un error
            }

            // Extraer latitud y longitud, asegurando que sean números válidos
            const latitud = parseFloat(data[0].latitud); // Ajusta esta línea
            const longitud = parseFloat(data[0].longitud); // Ajusta esta línea

            // Verificar que las coordenadas sean números válidos
            if (isNaN(latitud) || isNaN(longitud)) {
                console.error("Coordenadas inválidas:", latitud, longitud);
                return; // Salir si las coordenadas no son válidas
            }

            // Llamar al PHP para obtener la imagen
            fetch('../Controlador/CON_ObtenerImagenSitio.php', {
                method: "POST",
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: new URLSearchParams({ id: idSitio }) // Enviar el ID del sitio
            })
            .then(response => response.json())
            .then(imagenData => {

                const infoWindow = new google.maps.InfoWindow();
            
                const divContainerDetalles = document.createElement("div");
                divContainerDetalles.className="d-flex flex-column";
            
                divContainerDetalles.innerHTML += `
                <div class="info-window-container rounded-3 shadow-lg bg-light p-3">
                    <img class="img-fluid rounded-3 mb-3" src="data:image/jpeg;base64,${imagenData.imagen}" alt="Imagen del sitio">
                    <h4 class="fw-bold text-primary mb-3">${data[0].nombre}</h4>
                    <hr>
                    <p class="mb-2 "><strong>Localidad:</strong> ${data[0].nombre_localidad}</p>
                    <p class="mb-2 "><strong>Horario:</strong> ${data[0].horarios}</p>
                    <p class="mb-2 "><strong>Arancelado:</strong> ${data[0].arancelado == 1 ? 'Sí' : 'No'}</p>
                    <a href="https://www.google.com/maps/dir/?api=1&destination=${latitud},${longitud}" 
                        target="_blank" 
                        class="btn btn-outline-primary btn-sm w-100 mb-2">
                        Cómo llegar
                    </a>
                    <button onclick="cargarComentario(this.dataset.sitioId); limpiarInputOpinion(this.dataset.sitioId);" 
                        data-sitio-id="${idSitio}" 
                        class="btn btn-primary shadow-none w-100" data-bs-toggle="modal" data-bs-target="#modal${idSitio}">
                        Ver más
                    </button>
                </div>`;
            
                // Cerrar la ventana de información abierta actualmente, si existe
                if (currentInfoWindow) {
                    currentInfoWindow.close();
                }

                infoWindow.close();
                infoWindow.setContent(divContainerDetalles);
                infoWindow.open(marker.getMap(), marker);
                currentInfoWindow = infoWindow;

            })
            .catch(error => {
                console.error("Error al obtener la imagen:", error);
            });
        });

    });

}

function cargarMapaDesdeTarjeta(elemento) {
        
    try {
        // Obtener el ID del sitio desde el atributo data-sitio-id
        const idSitio = elemento.dataset.sitioId; 
        console.log("Cargando mapa para el sitio con ID:", idSitio);
        
        // Obtener las coordenadas del servidor usando una petición POST
        fetch('../Controlador/CON_ObtenerCOOR.php', {
            method: "POST",
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: new URLSearchParams({ id: idSitio }) // Enviar el ID del sitio
        })
        .then(response => response.json())
        .then(data => {
            console.log("Datos recibidos del servidor:", data); // Verificar la respuesta

            // Verificar si hay un error en la respuesta
            if (data.error) {
                console.error(data.error);
                return; // Salir si hay un error
            }

            // Extraer latitud y longitud, asegurando que sean números válidos
            const latitud = parseFloat(data[0].latitud); // Ajusta esta línea
            const longitud = parseFloat(data[0].longitud); // Ajusta esta línea

            // Verificar que las coordenadas sean números válidos
            if (isNaN(latitud) || isNaN(longitud)) {
                console.error("Coordenadas inválidas:", latitud, longitud);
                return; // Salir si las coordenadas no son válidas
            }

            const position = { lat: latitud, lng: longitud };

            // Agregar un marcador en la posición del sitio
            const marker = new google.maps.Marker({
                position: position,
                map: globalMap,
                label: {
                    text: (location.hash == "#favoritos" ?'♥' :'★'),
                    color: "white",
                    fontSize: "15px"
                },
                title: 'Sitio ' + idSitio,
                icon: customIcon,
                label: null
            });

            // Mover el mapa a la posición del marcador
            globalMap.setCenter(marker.getPosition());
            globalMap.setZoom(15); // Ajustar el zoom si es necesario

            fetch('../Controlador/CON_ObtenerImagenSitio.php', {
                method: "POST",
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: new URLSearchParams({ id: idSitio }) // Enviar el ID del sitio
            })
            .then(response => response.json())
            .then(imagenData => {
                const infoWindow = new google.maps.InfoWindow();
        
                const divContainer = document.createElement("div");
                divContainer.className="d-flex flex-column";
        
                divContainer.innerHTML += `
                <div class="info-window-container rounded-3 shadow-lg bg-light p-3">
                    <img class="img-fluid rounded-3 mb-3" src="data:image/jpeg;base64,${imagenData.imagen}" alt="Imagen del sitio">
                    <h4 class="fw-bold text-primary mb-3">${data[0].nombre}</h4>
                    <hr>
                    <p class="mb-2 "><strong>Localidad:</strong> ${data[0].nombre_localidad}</p>
                    <p class="mb-2 "><strong>Horario:</strong> ${data[0].horarios}</p>
                    <p class="mb-2 "><strong>Arancelado:</strong> ${data[0].arancelado == 1 ? 'Sí' : 'No'}</p>
                    <a href="https://www.google.com/maps/dir/?api=1&destination=${latitud},${longitud}" 
                        target="_blank" 
                        class="btn btn-outline-primary btn-sm w-100 mb-2">
                        Cómo llegar
                    </a>
                    <button onclick="cargarComentario(this.dataset.sitioId); limpiarInputOpinion(this.dataset.sitioId);" 
                        data-sitio-id="${idSitio}" 
                        class="btn btn-primary shadow-none w-100" data-bs-toggle="modal" data-bs-target="#modal${idSitio}">
                        Ver más
                    </button>
                </div>
                `;
                
                // Cerrar la ventana de información abierta actualmente, si existe
                if (currentInfoWindow) {
                    currentInfoWindow.close();
                }

                infoWindow.close();
                infoWindow.setContent(divContainer);
                infoWindow.open(marker.getMap(), marker);
                currentInfoWindow = infoWindow;

                marker.addListener("click", function (){
                    // Mover el mapa a la posición del marcador al hacer clic
                    globalMap.setCenter(marker.getPosition());
                    globalMap.setZoom(15); // Ajustar el zoom si es necesario
    
                    const infoWindow = new google.maps.InfoWindow();
            
                    const divContainerDetalles = document.createElement("div");
                    divContainerDetalles.className="d-flex flex-column";
            
                    divContainerDetalles.innerHTML += `
                    <div class="info-window-container rounded-3 shadow-lg bg-light p-3">
                    <img class="img-fluid rounded-3 mb-3" src="data:image/jpeg;base64,${imagenData.imagen}" alt="Imagen del sitio">
                    <h4 class="fw-bold text-primary mb-3">${data[0].nombre}</h4>
                    <hr>
                    <p class="mb-2 "><strong>Localidad:</strong> ${data[0].nombre_localidad}</p>
                    <p class="mb-2 "><strong>Horario:</strong> ${data[0].horarios}</p>
                    <p class="mb-2 "><strong>Arancelado:</strong> ${data[0].arancelado == 1 ? 'Sí' : 'No'}</p>
                    <a href="https://www.google.com/maps/dir/?api=1&destination=${latitud},${longitud}" 
                        target="_blank" 
                        class="btn btn-outline-primary btn-sm w-100 mb-2">
                        Cómo llegar
                    </a>
                    <button onclick="cargarComentario(this.dataset.sitioId); limpiarInputOpinion(this.dataset.sitioId);" 
                        data-sitio-id="${idSitio}" 
                        class="btn btn-primary shadow-none w-100" data-bs-toggle="modal" data-bs-target="#modal${idSitio}">
                        Ver más
                    </button>
                </div>
                    `;

                    // Cerrar la ventana de información abierta actualmente, si existe
                    if (currentInfoWindow) {
                        currentInfoWindow.close();
                    }

                    infoWindow.close();
                    infoWindow.setContent(divContainerDetalles);
                    infoWindow.open(marker.getMap(), marker);
                    currentInfoWindow = infoWindow;
                });
        
                
            })
            .catch(error => {
                console.error("Error al obtener la imagen:", error);
            });

        })
        .catch(error => {
            console.error("Error al obtener los datos del sitio:", error);
        });

    } catch (error) {
        console.error(error);
    }

}

// Llamar a la función al cargar la ventana
window.onload = iniciarmapa;
