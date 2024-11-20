const botonEditar = document.getElementById('botonEditarSitio');
if (botonEditar) {
    botonEditar.addEventListener('click', () => {
        const formularioE = document.getElementById('form-EditarSitio');

        // Obtener el valor del atributo data-bs-id
        const idSitio = formularioE.getAttribute('data-bs-id-editar');
        if (idSitio) {
            btnEditarSitio();
        } else {
            console.error('ID del sitio no encontrado en el atributo data-bs-id');
        }
    });
}

function btnEditarSitio() {
    const formularioE = document.getElementById('form-EditarSitio');

    // Obtener el valor del atributo data-bs-id
    const idSitio = formularioE.getAttribute('data-bs-id-editar');

    // Obtener el valor del nombre desde el campo de texto
    const nombre = document.getElementById('NombreSitioTuristico').value;
    const descripcion = document.getElementById('Descripcion').value;
    const selectCategoria = document.getElementById('SelectCategoria');
    const categoriaSeleccionada = selectCategoria.value; // Esto obtiene el ID de la categoría seleccionada

    console.log('ID del sitio:', idSitio);
    console.log('Nuevo nombre:', nombre);
    console.log('Nueva descripcion:', descripcion);
    console.log('Nueva categoria:', categoriaSeleccionada);

    // Preparar los datos para el envío (solo el nombre)
    const formData = {
        id_sitio: idSitio,
        nombre_sitio: nombre,
        descripcion: descripcion,
        categoria: categoriaSeleccionada
    };

    // Enviar los datos de edición (solo el nombre)
    fetch('../Controlador/CON_EditarSitio.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(formData)
    })
    .then(response => {
        // Imprimir la respuesta en texto crudo para ver qué está devolviendo el servidor
        return response.text().then(text => {
            console.log('Respuesta cruda del servidor:', text);  // Esto te permitirá ver si hay HTML o algún error
            try {
                return JSON.parse(text);  // Intentamos convertirlo en JSON
            } catch (error) {
                console.error('Error al analizar el JSON:', error);
                throw new Error('La respuesta del servidor no es un JSON válido');
            }
        });
    })
    .then(data => {
        console.log('Respuesta:', data); 
        if (data.success) {
            alert('Sitio editado correctamente');
            const selectCategoria = document.getElementById('SelectCategoria');
            const categoriaSeleccionada = selectCategoria.value;
            const textoCategoriaSeleccionada = selectCategoria.options[selectCategoria.selectedIndex].text;
            const categoriaLabel = document.getElementById('categoriaElegida');

            // Solo actualizar si se ha seleccionado una categoría
            if (categoriaSeleccionada !== "-1") {
                categoriaLabel.textContent = 'Categoría Actual: ' + textoCategoriaSeleccionada;
            } 

        } else {
            console.error('Error al editar el sitio:', data);
            alert('Error al editar el sitio');
        }
    })
    .catch(error => {
        console.error('Error:', error);  // Aquí se captura el error si no es un JSON válido
        alert('Hubo un problema con la solicitud');
    });
    
}    



