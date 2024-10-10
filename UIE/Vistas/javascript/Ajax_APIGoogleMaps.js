// Inicializa el mapa con coordenadas predeterminadas
function iniciarmapa(coord) {
    // Si las coordenadas son válidas, crear o actualizar el mapa
    if (coord) {
        var map = new google.maps.Map(document.getElementById('map'), {
            zoom: 10,
            center: coord  
        });

        // Agregar un marcador en las nuevas coordenadas
        var marker = new google.maps.Marker({
            position: coord,
            map: map
        });
    }
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


document.addEventListener("DOMContentLoaded", function() {
    var initialCoord = {lat: -34.793531378112675, lng: -58.72811124230912};
    iniciarmapa(initialCoord);  // Inicializa el mapa al cargar la página
});