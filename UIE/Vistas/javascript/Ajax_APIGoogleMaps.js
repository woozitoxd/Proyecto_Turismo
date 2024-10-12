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
        zoom: 12,
        center: { lat: -34.799627301712476, lng: -58.560688298394204 },
    });

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
                const title = sitio.descripcion || `Sitio ${i + 1}`; // Usar la descripción si está disponible

                const marker = new google.maps.Marker({
                    position,
                    map,
                    title: title,
                    label: `${i + 1}`,
                    optimized: false,
                });

                // Agregar un listener de clic para cada marcador y configurar la ventana de información
                agregarListenerMarcador(marker, sitio.id_sitio, title, map);
            });
        })
        .catch(error => {
            console.error("Error al obtener los datos:", error);
        });
}

function agregarListenerMarcador(marker, idSitio, title, map) {
    const infoWindow = new google.maps.InfoWindow();

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
            // Crear el contenido HTML para la ventana de información
            let contenido = '';

            // Verificar si hay una imagen y agregarla
            if (imagenData.imagen) {
                contenido += `<img src="data:image/jpeg;base64,${imagenData.imagen}" alt="Imagen del sitio" style="width: 200px; height: auto;"/><hr>`;
            } else {
                contenido += 'No se encontró imagen para este sitio.';
            }

            // Agregar la descripción debajo de la imagen
            contenido += `<p style="font-family: Arial, sans-serif; font-size: 14px; color: #333; margin-top: 8px;">Descripción: ${title}</p>`; // Descripción

            infoWindow.close();
            infoWindow.setContent(contenido);
            infoWindow.open(marker.getMap(), marker);
        })
        .catch(error => {
            console.error("Error al obtener la imagen:", error);
        });
    });
}

function cargarMapaDesdeTarjeta(elemento) {
        let urlActual = window.location.href; // Obtener la URL actual
        let palabraClave = "UIE/";
        let indice = urlActual.indexOf(palabraClave);
        let urlCortada = "";

        // Verificar si se encuentra la palabra clave en la URL
        if (indice !== -1) {
            urlCortada = urlActual.substring(0, indice + palabraClave.length);
        }

        // Obtener el ID del sitio desde el atributo data-sitio-id
        const idSitio = elemento.dataset.sitioId; 
        console.log("Cargando mapa para el sitio con ID:", idSitio);
        
        // Obtener las coordenadas del servidor usando una petición POST
        fetch(urlCortada + "Controlador/CON_ObtenerCOOR.php", {
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
                title: 'Sitio ' + idSitio,
            });

            // Agregar el listener para el nuevo marcador
            agregarListenerMarcador(marker, idSitio, data[0].descripcion, map); // Ajusta esta línea
        })
        .catch(error => {
            console.error("Error al obtener los datos del sitio:", error);
        });

    }

// Llamar a la función al cargar la ventana
window.onload = iniciarmapa;
