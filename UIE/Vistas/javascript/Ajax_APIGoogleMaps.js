// Crear una ventana de información para compartir entre los marcadores

//const infoWindow = new google.maps.InfoWindow();

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

    // Inicializar el mapa
    const map = new google.maps.Map(document.getElementById("map"), {
        zoom: 6,
        center: { lat: 0, lng: 0 },
    });

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
                    map,
                    title: title,
                    label: {
                        text: (location.hash == "#favoritos" ?'♥' :`★`),
                        /* text: (location.hash == "#favoritos" ?'♥' :`${i+1}`), */
                        color: "white",
                        fontSize: "15px"
                    },
                    optimized: false,
                });

                bounds.extend(marker.position);

                // Agregar un listener de clic para cada marcador y configurar la ventana de información
                agregarListenerMarcador(marker, sitio.id_sitio, title, sitio.nombre, map);
            });

            map.fitBounds(bounds, {
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

function agregarListenerMarcador(marker, idSitio, descripcion, nombre, map) {

        // Obtener la URL actual y crear la URL cortada para la petición
        let urlActual = window.location.href;
        let palabraClave = "UIE/";
        let indice = urlActual.indexOf(palabraClave);
        let urlCortada = "";
    
        // Verificar si se encuentra la palabra clave en la URL
        if (indice !== -1) {
            urlCortada = urlActual.substring(0, indice + palabraClave.length);
        }
        marker.addListener("click", () => {
            console.log(`ID del sitio: ${idSitio}`); // Verificar el ID del sitio

            // Mover el mapa a la posición del marcador al hacer clic
            map.setCenter(marker.getPosition());
            map.setZoom(15); // Ajustar el zoom si es necesario

            // Llamar al PHP para obtener la imagen
            fetch(urlCortada + "Controlador/CON_ObtenerImagenSitio.php", {
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
                divContainerDetalles.className="d-flex flex-column align-items-center";
            
                divContainerDetalles.innerHTML += `
                    <img class="overflow-hidden" src="data:image/jpeg;base64,${imagenData.imagen}" alt="Imagen del sitio"/>
                    <hr>
                    <h4 class="fw-bolder mb-3">${nombre}</h4>
                    <span class="w-75 mb-3 mx-3">Descripción: ${descripcion}</span>
                    <button class="btn btn-primary shadow-none mb-3" data-bs-toggle="modal" data-bs-target="#modal${idSitio}">Ver más</button>
                    `;
            
                infoWindow.close();
                infoWindow.setContent(divContainerDetalles);
                infoWindow.open(marker.getMap(), marker);

            })
            .catch(error => {
                console.error("Error al obtener la imagen:", error);
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

            // Mover el mapa a la posición del sitio y ajustar el zoom
            const map = new google.maps.Map(document.getElementById("map"), {
                zoom: 15,
                center: position,
            });

            // Agregar un marcador en la posición del sitio
            const marker = new google.maps.Marker({
                position: position,
                map: map,
                label: {
                    text: (location.hash == "#favoritos" ?'♥' :'★'),
                    color: "white",
                    fontSize: "15px"
                },
                title: 'Sitio ' + idSitio,
            });

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
                divContainer.className="d-flex flex-column align-items-center";
        
                divContainer.innerHTML += `
                <img class="overflow-hidden" src="data:image/jpeg;base64,${imagenData.imagen}" alt="Imagen del sitio"/>
                <hr>
                <h4 class="fw-bolder mb-3">${data[0].nombre}</h4>
                <span class="w-75 mb-3 mx-3">Descripción: ${data[0].descripcion}</span>
                <button class="btn btn-primary shadow-none mb-3" data-bs-toggle="modal" data-bs-target="#modal${data[0].id_sitio}">Ver más</button>
                `;
        
                infoWindow.close();
                infoWindow.setContent(divContainer);
                infoWindow.open(marker.getMap(), marker);

                marker.addListener("click", function (){
                    // Mover el mapa a la posición del marcador al hacer clic
                    map.setCenter(marker.getPosition());
                    map.setZoom(15); // Ajustar el zoom si es necesario
    
                    const infoWindow = new google.maps.InfoWindow();
            
                    const divContainerDetalles = document.createElement("div");
                    divContainerDetalles.className="d-flex flex-column align-items-center";
            
                    divContainerDetalles.innerHTML += `
                    <img class="overflow-hidden" src="data:image/jpeg;base64,${imagenData.imagen}" alt="Imagen del sitio"/>
                    <hr>
                    <h4 class="fw-bolder mb-3">${data[0].nombre}</h4>
                    <span class="w-75 mb-3 mx-3">Descripción: ${data[0].descripcion}</span>
                    <button class="btn btn-primary shadow-none mb-3" data-bs-toggle="modal" data-bs-target="#modal${data[0].id_sitio}">Ver más</button>
                    `;
            
                    infoWindow.close();
                    infoWindow.setContent(divContainerDetalles);
                    infoWindow.open(marker.getMap(), marker);
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
