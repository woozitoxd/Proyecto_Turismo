let urlActual = window.location.href;
let palabraClave = "UIE/";

let indice = urlActual.indexOf(palabraClave);

// Función para cargar razones
document.addEventListener("DOMContentLoaded", function () {
    // Si la palabra "UIE/" se encuentra en la URL
    if (indice !== -1) {
        let urlCortada = urlActual.substring(0, indice + palabraClave.length);

        // Realiza la solicitud para obtener las razones
        fetch(urlCortada + "Controlador/CON_ObtenerRazones.php") // 
            .then(res => res.json())
            .then(data => {
                const selectRazon = document.getElementById("razon");
                // Limpia las opciones existentes
                selectRazon.innerHTML = '<option value="">Selecciona una razón</option>';

                // Verifica si hay un error
                if (data.error) {
                    console.error(data.error);
                    return;
                }

                // Agrega las razones al select
                data.forEach(razon => {
                    const option = document.createElement("option");
                    option.value = razon.id_razon; // se le  asigna el id de la razon en funcion de la razon existente
                    option.textContent = razon.descripcion; //texto visible en el dropdown
                    selectRazon.appendChild(option);
                });
            })
            .catch(err => console.error("Error al cargar las razones:", err)); 
    } else {
        console.log("La palabra 'UIE/' no se encontró en la URL.");
    }
});


