function iniciarmapa(coord) {
    const map = new google.maps.Map(document.getElementById('map'), {
        center: coord,
        zoom: 17,
        mapId: '82945df34136974b',
    });

    // Escuchar el evento de clic en el mapa
    map.addListener('click', (event) => {
        const clickedLocation = {
            lat: event.latLng.lat(),
            lng: event.latLng.lng()
        };
        mostrarCoordenadas(clickedLocation); // Muestra las coordenadas en la página
    });

    initAutocomplete(map); // Pasa el objeto `map` aquí
}

function initAutocomplete(map) {
    const input = document.getElementById("place_input");
    const autocomplete = new google.maps.places.Autocomplete(input, {
        componentRestrictions: { country: "AR" } // Restringe a Argentina ("AR" es el código del país)
    });

    // Vincula el autocompletado al mapa
    autocomplete.bindTo('bounds', map);

    // Evento que se dispara cuando se selecciona un lugar
    autocomplete.addListener('place_changed', () => {
        const place = autocomplete.getPlace();
        if (place.geometry) {
            map.setCenter(place.geometry.location);
            map.setZoom(17);
            const marker = new google.maps.marker.AdvancedMarkerElement({
                map,
                position: place.geometry.location,
            });
        } else {
            console.log("No hay detalles disponibles para el lugar seleccionado.");
        }
    });
}


function mostrarCoordenadas(location) {
    const coordenadasDiv = document.getElementById('coordenadas');
    coordenadasDiv.textContent = `Coordenadas: Latitud ${location.lat}, Longitud ${location.lng}`;
}

// Función para manejar el clic en una tarjeta
function cargarMapaDesdeTarjeta(tarjeta) {
    var sitioID = tarjeta.getAttribute('data-sitio-id');  // Obtener el ID del sitio turístico 
    cargarMapa(sitioID);  // Llamar a la función AJAX que carga las coordenadas
}

// Función AJAX para obtener las coordenadas
function cargarMapa(sitioID) {
    let urlActual = window.location.href;
    let palabraClave = "UIE/";

    // Encuentra el índice de la palabra "UIE/" en la URL
    let indice = urlActual.indexOf(palabraClave);

    // Si la palabra "UIE/" se encuentra en la URL
    if (indice !== -1) {
        // Guarda la URL desde el inicio hasta la palabra "UIE/"
        let urlCortada = urlActual.substring(0, indice + palabraClave.length);

        $.ajax({
            url: urlCortada + 'Modelo/CON_ObtenerCoordenadas.php',  // Archivo PHP que devuelve las coordenadas
            type: 'POST', 
            data: { id: sitioID },  // Envía el ID del sitio turístico
            success: handleResponse,  // Maneja la respuesta en una función separada
            error: function(xhr, status, error) {
                console.error(`Error al obtener las coordenadas: ${xhr.status} - ${error}`);
            }
        });
    }
}

// Manejo de la respuesta del servidor
function handleResponse(response) {
    console.log("Respuesta del servidor:", response);  // Para ver qué se recibe del servidor
    try {
        const data = JSON.parse(response);  // Intenta parsear la respuesta JSON
        
        // Verifica si hay un error en la respuesta
        if (data.error) {
            console.error(`Error desde el servidor: ${data.error}`);
            return;  // Salir si hay un error
        }

        // Verifica que latitud y longitud existan antes de acceder
        const { latitud, longitud } = data;
        if (latitud !== undefined && longitud !== undefined) {
            const coord = { lat: parseFloat(latitud), lng: parseFloat(longitud) };
            console.log("Coordenadas recibidas:", coord);  // Mensaje de éxito
            iniciarmapa(coord);  // Inicializa el mapa con las nuevas coordenadas
        } else {
            console.error("Latitud o longitud no están definidas en la respuesta.");
        }
    } catch (e) {
        console.error("Error al procesar la respuesta JSON:", e);
    }
}

// Listener para cargar el mapa al cargar la página
document.addEventListener("DOMContentLoaded", function() {
    var initialCoord = { lat: 37.4239163, lng: -122.0947209 };  // Coordenadas iniciales
    iniciarmapa(initialCoord);  // Inicializa el mapa al cargar la página
});
