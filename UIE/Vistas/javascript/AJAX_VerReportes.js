
document.getElementById("verDenunciasBtn").addEventListener("click", function () {
    cargarDenuncias(); // Cargar denuncias al hacer clic en el botón
});

function cargarDenuncias() {
    // Obtener la URL base
    let urlActual = window.location.href;
    let palabraClave = "UIE/";
    let indice = urlActual.indexOf(palabraClave);
    let urlCortada = "";

    // Verificar si se encuentra la palabra clave en la URL
    if (indice !== -1) {
        urlCortada = urlActual.substring(0, indice + palabraClave.length);
    }

    // Realizar la solicitud AJAX
    fetch(urlCortada + "Controlador/CON_ObtenerDenuncias.php")
        .then(response => response.json())
        .then(data => {
            const denunciasTableBody = document.getElementById("denunciasTableBody");
            denunciasTableBody.innerHTML = ''; // Limpiar el contenido anterior
            
            if (data.error) {
                denunciasTableBody.innerHTML = `<tr><td colspan="5" class="text-danger">${data.error}</td></tr>`;
                return;
            }

            // Mostrar cada denuncia
            data.forEach(denuncia => {
                const row = document.createElement("tr");
                row.innerHTML = `
                    <td>${denuncia.denuncia_id}</td>
                    <td>${denuncia.razon}</td>
                    <td>${denuncia.comentario}</td>
                    <td>${denuncia.usuario_denunciante}</td>
                    <td>${denuncia.observacion}</td>
                    <td>
                        <button class="btn btn-danger btn-sm" onclick="eliminarComentario(${denuncia.id_comentario})">Eliminar</button>
                        <button class="btn btn-warning btn-sm" onclick="borrarDenuncia(${denuncia.denuncia_id})">Eliminar Denuncia</button>
                    </td>
                `;
                denunciasTableBody.appendChild(row);
            });


        })
        .catch(error => {
            const denunciasTableBody = document.getElementById("denunciasTableBody");
            denunciasTableBody.innerHTML = `<tr><td colspan="5" class="text-danger">Error al cargar las denuncias: ${error}</td></tr>`;
        });
}



function eliminarComentario(idComentario) {
    if (confirm("¿Estás seguro de que quieres eliminar este comentario?")) {
        // Obtener la URL base
        let urlActual = window.location.href;
        let palabraClave = "UIE/";
        let indice = urlActual.indexOf(palabraClave);
        let urlCortada = "";
        
        // Verificar si se encuentra la palabra clave en la URL
        if (indice !== -1) {
            urlCortada = urlActual.substring(0, indice + palabraClave.length);
        }
        
        // Realizar la solicitud AJAX para eliminar el comentario
        fetch(urlCortada + "Controlador/CON_EliminarComentario.php", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ id: idComentario }) // Enviar el ID del comentario
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                mostrarMensajeExito("Comentario eliminado exitosamente.");
                cargarDenuncias(); // Actualiza el contenido del modal
            } else {
                alert("Error al eliminar el comentario: " + data.error);
            }
        })
        .catch(error => {
            console.error("Error al eliminar el comentario:", error);
            alert("Error al eliminar el comentario. Revisa la consola para más detalles.");
        });
    }
    
}
function mostrarMensajeExito(mensaje) {
    const mensajeExitoDiv = document.getElementById("mensajeExito");
    mensajeExitoDiv.textContent = mensaje;
    mensajeExitoDiv.style.display = "block"; // Mostrar el mensaje
    
    // Ocultar el mensaje después de 3 segundos
    setTimeout(() => {
        mensajeExitoDiv.style.display = "none";
    }, 3000);
}
function borrarDenuncia(denuncia_id) {
    if (confirm("¿Estás seguro de que quieres eliminar esta denuncia?")) {
        // Obtener la URL base
        let urlActual = window.location.href;
        let palabraClave = "UIE/";
        let indice = urlActual.indexOf(palabraClave);
        let urlCortada = "";

        // Verificar si se encuentra la palabra clave en la URL
        if (indice !== -1) {
            urlCortada = urlActual.substring(0, indice + palabraClave.length);
        }

        // Realizar la solicitud AJAX para eliminar la denuncia
        fetch(urlCortada + "Controlador/CON_BorrarDenuncia.php", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ id: denuncia_id }) // Enviar el ID de la denuncia
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                mostrarMensajeExito("Denuncia ELiminada con éxito.");
                cargarDenuncias(); // Actualiza el contenido del modal
            } else {
                alert("Error al eliminar el reporte: " + data.error);
            }
        })
        .catch(error => {
            console.error("Error al eliminar el reporte:", error);
            alert("Error al eliminar el reporte. Revisa la consola para más detalles.");
        });
    }
}