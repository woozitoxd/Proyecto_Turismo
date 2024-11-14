function showModal(message, isSuccess) {
    const modalContent = document.getElementById('modalContent');
    const modalMessage = document.getElementById('modalMessage');
    const modalIcon = document.getElementById('modalIcon');

    // Limpiar clases previas
    modalContent.classList.remove('success', 'error');
    modalIcon.classList.remove('success-icon', 'error-icon');

    if (isSuccess) {
        modalContent.classList.add('success');
        modalIcon.classList.add('success-icon');
    } else {
        modalContent.classList.add('error');
        modalIcon.classList.add('error-icon');
    }

    modalMessage.textContent = message;

    // Configurar el modal resultModal en el frente
    const resultModal = new bootstrap.Modal(document.getElementById('resultModal'));
    // Cerrar temporalmente modalVistaPreviaSitio
    const previewModal = bootstrap.Modal.getInstance(document.getElementById('modalVistaPreviaSitio'));
    if (previewModal) {
        previewModal.hide(); // Cierra el modal de vista previa
    }

    // Mostrar resultModal
    resultModal.show();
    previewModal.show();
    // Restaurar vista previa cuando resultModal se cierra
    document.getElementById('resultModal').addEventListener('hidden.bs.modal', () => {
        if (previewModal) {
            previewModal.show(); // Reabre el modal de vista previa al cerrar resultModal
        }
    });
}

function showModal(message, isSuccess) {
    const modalContent = document.getElementById('modalContent');
    const modalMessage = document.getElementById('modalMessage');
    const modalIcon = document.getElementById('modalIcon');

    // Limpiar clases previas
    modalContent.classList.remove('success', 'error');
    modalIcon.classList.remove('success-icon', 'error-icon');

    if (isSuccess) {
        modalContent.classList.add('success');
        modalIcon.classList.add('success-icon');
    } else {
        modalContent.classList.add('error');
        modalIcon.classList.add('error-icon');
    }

    modalMessage.textContent = message;

    // Muestra el modal con la opacidad del fondo
    const resultModal = new bootstrap.Modal(document.getElementById('resultModal'));
    //resultModal.show();

    
    // Cambia la opacidad del modal anterior y añade fondo oscuro
    const modalanterior = document.getElementById('modalVistaPreviaSitio'); // Aplica opacidad al modal anterior
    modalanterior.style.opacity = '0.44';

    // Mostrar el modal con la opacidad del fondo
    resultModal.show();

    // Restablece la opacidad cuando se cierra el modal principal
    document.getElementById('resultModal').addEventListener('hidden.bs.modal', () => {
        modalanterior.style.opacity = '1';
    });
}





let selectedImages = []; // Array para almacenar las imágenes seleccionadas
let binaryImages = []; // Array para almacenar las imágenes en formato binario

function previewImages(event) {
    const imagePreviewContainer = document.getElementById("imagePreviewContainer");
    const thumbnailContainer = document.getElementById("thumbnailContainer");
    const files = event.target.files;

    // Limpiar el contenido previo
    imagePreviewContainer.innerHTML = "";
    thumbnailContainer.innerHTML = "";
    selectedImages = Array.from(files); // Guardar las imágenes en el array

    if (selectedImages.length > 0) {
        // Crear el HTML del carrusel
        let carouselHTML = `
        <div id="imageCarousel" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-inner">`;

        // Generar dinámicamente cada imagen como un "slide" del carrusel
        selectedImages.forEach((file, index) => {
            const imageURL = URL.createObjectURL(file);
            carouselHTML += `
            <div class="carousel-item ${index === 0 ? 'active' : ''}">
                <img src="${imageURL}" class="d-block w-100" alt="Imagen ${index + 1}">
            </div>`;

            // Crear una miniatura con botón de eliminación para cada imagen
            const thumbnailHTML = `
            <div class="thumbnail-container position-relative me-2 mb-2">
                <img src="${imageURL}" class="thumbnail-img border" alt="Miniatura ${index + 1}" onclick="navigateCarousel(${index})" style="width: 60px; height: 60px; object-fit: cover; cursor: pointer;">
                <button type="button" class="btn btn-danger btn-sm position-absolute top-0 end-0" onclick="deleteImage(${index})" style="padding: 2px 6px;">
                    &times;
                </button>
            </div>`;
            thumbnailContainer.innerHTML += thumbnailHTML;
        });

        // Agregar los controles del carrusel
        carouselHTML += `
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#imageCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#imageCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>`;

        // Insertar el carrusel en el contenedor
        imagePreviewContainer.innerHTML = carouselHTML;

        // Convertir imágenes a binario
        convertImagesToBinary();
    }
}


// Función para navegar al índice del carrusel correspondiente
function navigateCarousel(index) {
    const carousel = new bootstrap.Carousel(document.getElementById('imageCarousel'));
    carousel.to(index); // Navegar a la imagen correspondiente en el carrusel
}

// Función para eliminar una imagen de la vista previa y del carrusel
function deleteImage(index) {
    selectedImages.splice(index, 1); // Eliminar la imagen del array
    previewImages({ target: { files: selectedImages } }); // Actualizar la vista previa con las imágenes restantes
}



function actualizarCategoria() {
    const select = document.getElementById('SelectCategoria');
    const categoriaSeleccionada = document.getElementById('CategoriaSeleccionada');
    const valorSeleccionado = select.options[select.selectedIndex].text;
    // Actualiza el texto del párrafo con la categoría seleccionada
    categoriaSeleccionada.textContent = valorSeleccionado;
}


function validarCoordenadas() {     //funcion que se usará al comento de validar el form
    const latitud = document.getElementById('Latitud').value;
    const longitud = document.getElementById('Longitud').value;

    if (latitud < -90 || latitud > 90) {
        alert('La latitud debe estar entre -90 y 90.');
        return false;
    }

    if (longitud < -180 || longitud > 180) {
        alert('La longitud debe estar entre -180 y 180.');
        return false;
    }
    return true;
}

async function LocalidadesSelect(urlVariable){

    //console.log('estoy en la funcion ' + ContraseñaActual + NuevaContraseña + ConfirmaciónNuevaContraseña + IDUsuario);

    let url = urlVariable + '/../Controlador/CON_ObtenerLocalidades.php';
    fetch(url, {
        method: 'POST' // Especifica el método HTTP
    })
    .then(response => response.json()) // Parsear la respuesta como JSON
    .then(result => {
        // Manejar la respuesta del servidor
        if (result) {
            //console.log(result);
            const selectLocalidad = document.getElementById("SelectLocalidad");

            // Asegúrate de que result sea un array o conviértelo en uno si es un objeto.
            result.forEach(item => {
                // Crear una nueva opción para el select
                const option = document.createElement("option");
                option.value = item.id_localidad; // Asigna el valor como id_categoria
                option.textContent = item.nombre; // Asigna el texto de la opción como titulo
                selectLocalidad.appendChild(option); // Agrega la opción al select
            });
        } else {
            console.log('error en el fetch al traer localidad');
        }
    })
    .catch(error => {
        console.log('Error en la solicitud de traer categorias', error);
    });
}


async function CategoriasSelect(urlVariable){

    //console.log('estoy en la funcion ' + ContraseñaActual + NuevaContraseña + ConfirmaciónNuevaContraseña + IDUsuario);

    let url = urlVariable + '/../Controlador/CON_ObtenerCategorias.php';
    fetch(url, {
        method: 'POST' // Especifica el método HTTP
    })
    .then(response => response.json()) // Parsear la respuesta como JSON
    .then(result => {
        // Manejar la respuesta del servidor
        if (result) {
            //console.log(result);
            const selectCategoria = document.getElementById("SelectCategoria");

            // Asegúrate de que result sea un array o conviértelo en uno si es un objeto.
            result.forEach(item => {
                // Crear una nueva opción para el select
                const option = document.createElement("option");
                option.value = item.id_categoria; // Asigna el valor como id_categoria
                option.textContent = item.titulo; // Asigna el texto de la opción como titulo
                selectCategoria.appendChild(option); // Agrega la opción al select
            });
        } else {
            console.log('error en el fetch al traer categorias');
            /*if(result.error==false){
                showModal(`Contraseña incorrecta`, false);
            }
            else{
            showModal(`${result.error} `, false);
            //showModal(`Error: ${result.error}`, false);
            }*/
        }
    })
    .catch(error => {
        console.log('Error en la solicitud de traer categorias', error);
        //showModal(`Error: Disculpe las molestias ocasiondas, error en la solicitud al servidor.`, false);
    });
}

async function EtiquetasSelect(urlVariable){

    //console.log('estoy en la funcion ' + ContraseñaActual + NuevaContraseña + ConfirmaciónNuevaContraseña + IDUsuario);

    let url = urlVariable + '/../Controlador/CON_ObtenerEtiquetas.php';
    fetch(url, {
        method: 'POST' // Especifica el método HTTP
    })
    .then(response => response.json()) // Parsear la respuesta como JSON
    .then(result => {
        // Manejar la respuesta del servidor
        if (result) {
            //console.log(result);
            const selectEtiquetas = document.getElementById("SelectEtiquetas");

            result.forEach(item => {

                // Crear una nueva opción para el select
                const option = document.createElement("option");
                option.value = item.id_etiqueta; // Asigna el valor como id_categoria
                option.textContent = item.titulo; // Asigna el texto de la opción como titulo
                selectEtiquetas.appendChild(option); // Agrega la opción al select
            });

                    new MultiSelectTag('SelectEtiquetas', {
                        rounded: true,       // default true
                        shadow: false,       // default false
                        placeholder: 'Search...', // default Search...
                        tagColor: {
                            textColor: '#327b2c',
                            borderColor: '#92e681',
                            bgColor: '#eaffe6',
                        },
                        onChange: function(values) {
                            //console.log("Etiquetas seleccionadas:", values); // Esto muestra las etiquetas seleccionadas al cambiar
                        }
                    });
            
        } else {
            console.log('error en el fetch obtener etiquetas');

        }
    })
    .catch(error => {
        console.log('Error en la solicitud de traer etiquetas', error);
    });
}



// Función para obtener valores seleccionados manualmente con su texto
function obtenerEtiquetasSeleccionadas() {
    const selectEtiquetas = document.getElementById("SelectEtiquetas");
    const etiquetasSeleccionadas = Array.from(selectEtiquetas.options)
        .filter(option => option.selected)
        .map(option => ({
            value: option.value,
            text: option.text
        }));

    return etiquetasSeleccionadas;
}

function PublicarSitio(NombreSitioTuristico, OptionCategoriaValue, OptionCategoriaTitulo, Etiquetas, DescripcionSitioTuristico, latitud, longuitud, OptionLocalidadValue, OptionLocalidadTitulo, Arancelamiento) {
    // Llamar a convertImagesToBinary y pasarle una callback que enviará los datos cuando esté lista

    
    convertImagesToBinary(function(binaryImages) {
        const formData = new FormData();

        // Agregar otros datos del sitio al FormData
        formData.append('NombreSitioTuristico', NombreSitioTuristico);
        formData.append('OptionCategoriaValue', OptionCategoriaValue);
        formData.append('OptionCategoriaTitulo', OptionCategoriaTitulo);
        formData.append('DescripcionSitioTuristico', DescripcionSitioTuristico);
        formData.append('latitud', latitud);
        formData.append('longuitud', longuitud);
        formData.append('OptionLocalidadValue', OptionLocalidadValue);
        formData.append('OptionLocalidadTitulo', OptionLocalidadTitulo);
        formData.append('Arancelamiento', Arancelamiento);
        //formData.append('IDUsuario', IDUsuario);
//        console.log("el IDUSuario es ", IDUsuario);
        
        
        // Agregar etiquetas como un array de strings
        //console.log('sadas');
        Etiquetas.forEach((etiqueta, index) => {
            formData.append(`Etiquetas[${index}]`, JSON.stringify(etiqueta)); // Enviar las etiquetas correctamente indexadas
        });
        
        

        // Agregar cada imagen en binario al FormData
        binaryImages.forEach((image, index) => {
            const blob = new Blob([image.data], { type: 'application/octet-stream' });
            formData.append(`images[${index}]`, blob, image.name);
        });

        fetch(urlVariable + '/../Controlador/CON_PublicarSitio.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.text()) // Usa .text() temporalmente para ver el contenido exacto
        .then(text => {
            try {
                const result = JSON.parse(text); // Intenta convertir manualmente a JSON
                if (result.success) {
                    console.log('Sitio publicado exitosamente');
                    showModal('Sitio publicado exitosamente!',true);
                } else {
                    console.log('Error al publicar el sitio:', result.error);
                    showModal('Error al publicar el sitio:' + result.error,false);
                }
            } catch (error) {
                console.log('Catch Respuesta no es JSON válido:', text); // Muestra el texto de respuesta
                showModal('Error al publicar el sitio:' + text,false);
            }
        })

        .catch(error => {
            console.log('Error en la solicitud de Publicar el sitio:', error);
        });
        
    });
}

// Función para convertir cada imagen a datos binarios y luego llamar a la callback
function convertImagesToBinary(callback) {
    binaryImages = [];
    console.log(selectedImages);
    
    selectedImages.forEach((file, index) => {
        const reader = new FileReader();
        reader.onload = function(event) {
            const binaryData = event.target.result;
            binaryImages.push({ name: file.name, data: binaryData });

            // Cuando todas las imágenes estén convertidas, ejecuta la callback
            if (binaryImages.length === selectedImages.length) {
                callback(binaryImages); // Llama a la callback con todas las imágenes en binario
            }
        };
        reader.readAsArrayBuffer(file);
    });
}

function llenarModalVistaPreviaSitio(NombreSitioTuristico, OptionCategoriaTitulo, Etiquetas, DescripcionSitioTuristico, OptionLocalidadTitulo, Arancelamiento){

    const DIVCarrouselImagenesModal = document.getElementById('DIVCarrouselImagenesModal');
    const NombreSitioModal = document.getElementById('IDNombreSitioModal');
    const DescripcionSitioModal = document.getElementById('IDDescripcionSitioModal');
    const LocalidadSitioModal = document.getElementById('IDLocalidadSitioModal');
    const ArancelamientoSitioModal = document.getElementById('IDArancelamientoSitioModal');
    const DivCategoriasYEtiquetasModal = document.getElementById('DivCategoriasYEtiquetasModal');

    NombreSitioModal.textContent = "";
    DescripcionSitioModal.textContent = ""; 
    LocalidadSitioModal.textContent = "";
    ArancelamientoSitioModal.textContent = "";
    DivCategoriasYEtiquetasModal.innerHTML = "";

    NombreSitioModal.textContent = NombreSitioTuristico;
    
    const categoria = document.createElement("p");
    categoria.classList.add("categoria-lugar", "d-inline-block");
    categoria.textContent = OptionCategoriaTitulo;
    DivCategoriasYEtiquetasModal.appendChild(categoria);

    Etiquetas.forEach(index => {
        const etiqueta = document.createElement("p");
        etiqueta.classList.add("etiqueta-lugar", "d-inline-block");
        etiqueta.textContent = index.titulo;
        DivCategoriasYEtiquetasModal.appendChild(etiqueta);
    });

    DescripcionSitioModal.textContent = DescripcionSitioTuristico;
    LocalidadSitioModal.textContent = "Localidad: " + OptionLocalidadTitulo;

    if(Arancelamiento===true){
        ArancelamientoSitioModal.textContent = "Es arancelado: SI";
    }
    if(Arancelamiento===false){
        ArancelamientoSitioModal.textContent = "Es arancelado: NO";
    }
DIVCarrouselImagenesModal.innerHTML = ""; // Limpiar contenido previo
    if (selectedImages.length > 0) {
        let carouselHTML = `
            <div id="imageCarouselModal" class="carousel slide mb-2" data-bs-ride="carousel">
                <div class="carousel-inner">`;

                selectedImages.forEach((image, index) => {
            const imageURL = URL.createObjectURL(image);
            carouselHTML += `
                <div class="carousel-item ${index === 0 ? 'active' : ''}">
                    <img src="${imageURL}" class="d-block w-100" alt="Imagen ${index + 1}">
                </div>`;
        });

        carouselHTML += `
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#imageCarouselModal" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#imageCarouselModal" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
                </button>
            </div>`;

        DIVCarrouselImagenesModal.innerHTML = carouselHTML;
    }

    const modalVistaPreviaSitio = new bootstrap.Modal(document.getElementById('modalVistaPreviaSitio'));
    modalVistaPreviaSitio.show();
}

function ModalVistaPreviaSitio() {
    const form = document.getElementById("form-SugerirSitio");
    if (form) {
        // Crear un objeto FormData con el formulario
        let bandera = 0;
        const formInfo = new FormData(form);

        // Obtener los valores de cada campo por su nombre
        const NombreSitioTuristico = formInfo.get("NombreSitioTuristico"); 

        const selectCategoria = document.getElementById('SelectCategoria');
        const OptionCategoriaValue = selectCategoria.options[selectCategoria.selectedIndex].value; 
        const OptionCategoriaTitulo = selectCategoria.options[selectCategoria.selectedIndex].text;
        
        const etiquetasSeleccionadas = obtenerEtiquetasSeleccionadas();
        let Etiquetas = [];
        let iteradorEtiquetas=0;
        etiquetasSeleccionadas.forEach(etiqueta => {
            Etiquetas[iteradorEtiquetas] = {
                titulo: etiqueta.text,
                id_etiqueta: etiqueta.value
            }
            //console.log("ID de Etiqueta:", etiqueta.text);
            //console.log("Título de Etiqueta:", etiqueta.text);
            iteradorEtiquetas +=1;
        });

        const DescripcionSitioTuristico = formInfo.get("Descripcion"); 
        const latitud = document.getElementById('latitud').textContent;
        const longuitud = document.getElementById('longuitud').textContent;

        const Arancelamiento = document.getElementById('flexSwitchCheckDefault').checked;

        const selectLocalidad = document.getElementById('SelectLocalidad');
        const OptionLocalidadValue = selectLocalidad.options[selectLocalidad.selectedIndex].value; 
        const OptionLocalidadTitulo = selectLocalidad.options[selectLocalidad.selectedIndex].text;

        //obtenemos y limpiamos mensajes de error
        const NombreSitioTuristicoError = document.getElementById("NombreSitioTuristicoError");
        const SelectCategoriaError = document.getElementById("SelectCategoriaError");
        const SelectEtiquetasError = document.getElementById("SelectEtiquetasError");
        const DescripcionError = document.getElementById("DescripcionError");
        const ImagenSitioError = document.getElementById("ImagenSitioError");
        const SelectLocalidadError = document.getElementById("SelectLocalidadError");
        const mapError = document.getElementById("mapError");


        NombreSitioTuristicoError.textContent = "";
        SelectCategoriaError.textContent = "";
        SelectEtiquetasError.textContent = "";
        DescripcionError.textContent = "";
        ImagenSitioError.textContent = "";
        SelectLocalidadError.textContent = "";
        mapError.textContent = "";



        if (NombreSitioTuristico == "") {
            NombreSitioTuristicoError.textContent = "Ingrese un nombre!";
            bandera += 1;
        }
        if (OptionCategoriaValue == -1) {
            SelectCategoriaError.textContent = "Seleccione una categoria!";
            bandera += 1;
        }
        if (Etiquetas.length == 0) {
            SelectEtiquetasError.textContent = "Seleccione al menos una etiqueta!";
            bandera += 1;
        }
        if (DescripcionSitioTuristico == "") {
            DescripcionError.textContent = "Ingrese una descipcion para el sitio turistico!";
            bandera += 1;
        }
        if (selectedImages.length == 0) {
            ImagenSitioError.textContent = "Ingrese al menos una imagen!";            
            bandera += 1;
        }
        if (OptionLocalidadValue == -1) {
            SelectLocalidadError.textContent = "Seleccione una localidad!";
            bandera += 1;
        }
        
        if (latitud =="" || longuitud == "") {
            mapError.textContent = "Seleccione la ubicacion del sitio!";
            bandera += 1;
        }


        
/*         // Imprimir los valores
        console.log("Nombre del Sitio:", NombreSitioTuristico);
        console.log("ID categoría del Sitio:", OptionCategoriaValue);
        console.log("Título categoría del Sitio:", OptionCategoriaTitulo);
        console.log('Objeto de etiquetas del Sitio: ', Etiquetas);
        console.log('Descripcion del Sitio: ', DescripcionSitioTuristico);
        console.log('La latitud del Sitio es: ', latitud);
        console.log('La longuitud del Sitio es: ', longuitud);
        console.log('Es arancelado el Sitio? ', Arancelamiento);
        console.log("ID categoría del Sitio:", OptionLocalidadValue);
        console.log("Título categoría del Sitio:", OptionLocalidadTitulo); */

        if (bandera == 0) {
            // Mostrar el modal solo si las validaciones son correctas
            llenarModalVistaPreviaSitio(NombreSitioTuristico, OptionCategoriaTitulo, Etiquetas, DescripcionSitioTuristico, OptionLocalidadTitulo, Arancelamiento);
        }
        else{
            window.scrollTo({ top: 0, behavior: 'smooth' });

        }

    

    } else {
        console.log("No se encontró el formulario");
    }
}

function btnPublicarSitio(){
    //showModal('sad',false);
    const form = document.getElementById("form-SugerirSitio");
    if (form) {
        console.log('entro al form');
        // Crear un objeto FormData con el formulario
        const formInfo = new FormData(form);

        // Obtener los valores de cada campo por su nombre
        const NombreSitioTuristico = formInfo.get("NombreSitioTuristico"); 

        const selectCategoria = document.getElementById('SelectCategoria');
        const OptionCategoriaValue = selectCategoria.options[selectCategoria.selectedIndex].value; 
        const OptionCategoriaTitulo = selectCategoria.options[selectCategoria.selectedIndex].text;
        
        const etiquetasSeleccionadas = obtenerEtiquetasSeleccionadas();
        let Etiquetas = [];
        let iteradorEtiquetas=0;
        etiquetasSeleccionadas.forEach(etiqueta => {
            Etiquetas[iteradorEtiquetas] = {
                titulo: etiqueta.text,
                id_etiqueta: etiqueta.value
            }
            //console.log("ID de Etiqueta:", etiqueta.text);
            //console.log("Título de Etiqueta:", etiqueta.text);
            iteradorEtiquetas +=1;
        });

        const DescripcionSitioTuristico = formInfo.get("Descripcion"); 
        const latitud = document.getElementById('latitud').textContent;
        const longuitud = document.getElementById('longuitud').textContent;

        const Arancelamiento = document.getElementById('flexSwitchCheckDefault').checked;

        const selectLocalidad = document.getElementById('SelectLocalidad');
        const OptionLocalidadValue = selectLocalidad.options[selectLocalidad.selectedIndex].value; 
        const OptionLocalidadTitulo = selectLocalidad.options[selectLocalidad.selectedIndex].text;

        //llenarModalVistaPreviaSitio(NombreSitioTuristico, OptionCategoriaTitulo, Etiquetas, DescripcionSitioTuristico, OptionLocalidadTitulo, Arancelamiento);

        //validaciones de js
        //console.log("Título categoría del Sitio:", OptionCategoriaTitulo);
        //console.log('Es arancelado el Sitio? ', Arancelamiento);
        
/*         // Imprimir los valores
        console.log("Nombre del Sitio:", NombreSitioTuristico);
        console.log("ID categoría del Sitio:", OptionCategoriaValue);
        console.log("Título categoría del Sitio:", OptionCategoriaTitulo);
        console.log('Objeto de etiquetas del Sitio: ', Etiquetas);
        console.log('Descripcion del Sitio: ', DescripcionSitioTuristico);
        console.log('La latitud del Sitio es: ', latitud);
        console.log('La longuitud del Sitio es: ', longuitud);
        console.log('Es arancelado el Sitio? ', Arancelamiento);
        console.log("ID categoría del Sitio:", OptionLocalidadValue);
        console.log("Título categoría del Sitio:", OptionLocalidadTitulo); */

        //MODAL DE VISTA PREVIA

        PublicarSitio(NombreSitioTuristico, OptionCategoriaValue, OptionCategoriaTitulo, Etiquetas, DescripcionSitioTuristico, latitud, longuitud,OptionLocalidadValue, OptionLocalidadTitulo, Arancelamiento);
    

    } else {
        console.log("No se encontró el formulario");
    }
}
let urlVariable = document.body.getAttribute('data-url-base');
//let IDUsuario = document.body.getAttribute('data-IDUsuario');
document.addEventListener("DOMContentLoaded", async function () {
    //console.log(urlVariable);

    /* await CategoriasSelect(urlVariable);
    await EtiquetasSelect(urlVariable);
    new MultiSelectTag('SelectEtiquetas') */

    let promesasDOM1 = [];
    promesasDOM1.push(CategoriasSelect(urlVariable)); //si usamos un away en el for, se rompe, por eso hacemos esto
    await Promise.all(promesasDOM1);

    let promesasDOM2 = [];
    promesasDOM2.push(EtiquetasSelect(urlVariable)); //si usamos un away en el for, se rompe, por eso hacemos esto
    await Promise.all(promesasDOM2);

    let promesasDOM3 = [];
    promesasDOM3.push(LocalidadesSelect(urlVariable)); //si usamos un away en el for, se rompe, por eso hacemos esto
    await Promise.all(promesasDOM3);
    
//agarrar el submit del form
// Comprobar si el formulario se encuentra correctamente

});
