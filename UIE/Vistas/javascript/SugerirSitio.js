//pequeño codigo para mostrar la vista previa de una nueva foto de perfil
function previewImages(event) {
    console.log("Función previewImages llamada");

    const imagePreviewContainer = document.getElementById('imagePreviewContainer');
    imagePreviewContainer.innerHTML = ''; // Limpiar previas vistas previas

    const files = event.target.files;
    
    if (files) {
        Array.from(files).forEach(file => {
            const reader = new FileReader();
            reader.onload = function(e) {
                const imgElement = document.createElement('img');
                imgElement.src = e.target.result;
                imgElement.style.width = '100px'; // Ajusta el tamaño según sea necesario
                imgElement.style.height = '100px';
                imgElement.style.objectFit = 'cover';
                imagePreviewContainer.appendChild(imgElement);
            };
            reader.readAsDataURL(file);
        });
    }
}



function actualizarCategoria() {
    const select = document.getElementById('SelectCategoria');
    const categoriaSeleccionada = document.getElementById('CategoriaSeleccionada');
    const valorSeleccionado = select.options[select.selectedIndex].text;
    // Actualiza el texto del párrafo con la categoría seleccionada
    categoriaSeleccionada.textContent = valorSeleccionado;
}

var contadorSelect = 0; //variable

function AgregarSelect() {
    const contenedor = document.getElementById("IDContenedorSelectEtiquetas");

    // Crear un contenedor único para cada select y su botón
    const fragmentoHTML = `
        <div id="selectContainer${contadorSelect}" class="mb-2">
            <select id="SelectEtiqueta${contadorSelect}" class="form-select mt-2 d-inline" onchange="AgregarEtiqueta(${contadorSelect})">
                <option value="">Selecciona Etiquetas!</option>
                <option value="Etiqueta1${contadorSelect}" name="Etiqueta1${contadorSelect}">Etiqueta 1</option>
                <option value="Etiqueta2${contadorSelect}" name="Etiqueta2${contadorSelect}">Etiqueta 2</option>
            </select>
            <button class="btn btn-danger mt-2 d-inline" onclick="EliminarSelect(${contadorSelect})">Eliminar</button>
            <p class="d-inline EstilosEstiquetas" id="pEtiqueta${contadorSelect}"> EtiquetaSeleccionada</p>
        </div>`;
    
    // Insertar el HTML generado en el contenedor
    contenedor.innerHTML += fragmentoHTML;  
    contadorSelect += 1;  // Incrementar el contador para crear ids únicos
}

function EliminarSelect(id) {
    // Seleccionamos el contenedor del select que queremos eliminar
    const selectContainer = document.getElementById(`selectContainer${id}`);
    // Si el contenedor existe, lo eliminamos
    if (selectContainer) {
        selectContainer.remove();
    }
}

function AgregarEtiqueta(id){
    // Obtenemos el select correspondiente
    const select = document.getElementById(`SelectEtiqueta${id}`);
    
    // Obtenemos el párrafo asociado al select
    const EtiquetaSeleccionada = document.getElementById(`pEtiqueta${id}`);

    // Si el select existe, obtenemos la opción seleccionada y la asignamos al párrafo
    if (select) {
        const valorSeleccionado = select.options[select.selectedIndex].text;
        EtiquetaSeleccionada.textContent = valorSeleccionado;
    }
}

document.addEventListener('DOMContentLoaded', (event) => {
    // Llamar a AgregarSelect una vez cuando la página cargue
    AgregarSelect();
});
