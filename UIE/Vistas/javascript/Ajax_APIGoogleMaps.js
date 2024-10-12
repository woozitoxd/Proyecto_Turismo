// The following example creates markers from coordinates obtained from the server.
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
    
    // Crear una ventana de información para compartir entre los marcadores
    const infoWindow = new google.maps.InfoWindow();

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
                marker.addListener("click", () => {
                    console.log(`ID del sitio: ${sitio.id_sitio}`); // Verificar el ID del sitio
                    // Llamar al PHP para obtener la imagen
                    fetch(urlCortada + "Controlador/CON_ObtenerImagenSitio.php", {
                        method: "POST",
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded'
                        },
                        body: new URLSearchParams({ id: sitio.id_sitio }) // Enviar el ID del sitio
                    })
                    .then(response => response.json())
                    .then(imagenData => {
                        // Crear el contenido HTML para la ventana de información
                        let contenido = '';

                        // Verificar si hay una imagen y agregarla
                        if (imagenData.imagen) {
                            contenido += `<img src="data:image/jpeg;base64,${imagenData.imagen}" alt="Imagen del sitio" style="width: 200px; height: auto;"/><hr>`; // Ajustar el ancho a 200px
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
            });
        })
        .catch(error => {
            console.error("Error al obtener los datos:", error);
        });
}

// Llamar a la función al cargar la ventana
window.onload = iniciarmapa;
