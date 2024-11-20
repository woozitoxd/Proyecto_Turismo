
document.getElementById('verDetallesBtn').addEventListener('click', function() {
    cargarUsuarios(); // Cargar denuncias al hacer clic en el botón
});

function cargarUsuarios() {
    let urlActual = window.location.href;
    let palabraClave = "UIE/";
    let indice = urlActual.indexOf(palabraClave);
    let urlCortada = "";

    // Verificar si se encuentra la palabra clave en la URL
    if (indice !== -1) {
        urlCortada = urlActual.substring(0, indice + palabraClave.length);
    }

    fetch(urlCortada + "Controlador/CON_MostrarUsuarios.php")
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json(); // Intenta parsear JSON
        })
        .then(data => {
            if (!data.success) {
                throw new Error(data.error || "Error desconocido en el servidor");
            }
            const usuariosTabla = document.getElementById('usuariosTabla');
            usuariosTabla.innerHTML = ''; // Limpia la tabla
            data.usuarios.forEach(usuario => {
                let estadoClase = usuario.estado == 0 ? "estado-bloqueado" : "estado-activo";
                let botonTexto = usuario.estado == 0 ? "Desbloquear Usuario" : "Bloquear Usuario";
                let botonClase = usuario.estado == 0 ? "btn-primary" : "btn-danger";
            
                usuario.estado = usuario.estado == 0 ? "Bloqueado" : "Activo";
            
                usuariosTabla.innerHTML += `
                    <tr>
                        <td>${usuario.nombre}</td>
                        <td>${usuario.rol}</td>
                        <td>${usuario.email}</td>
                        <td class="${estadoClase}">${usuario.estado}</td>
                        <td>
                            <button class="btn ${botonClase} btn-sm" onclick="cambiarEstadoCuentaUsuario(${usuario.id}, '${usuario.estado}')">${botonTexto}</button>
                            <button class="btn btn-warning btn-sm" onclick="mostrarCambiarRolModal(${usuario.id}, ${usuario.id_rol})">Cambiar Rol</button>
                        </td>
                    </tr>
                `;
            });
            
            
        })
        .catch(error => console.error('Error:', error.message));
};

function cambiarEstadoCuentaUsuario(IdUsuario, estadoActual) {
    // Obtener la URL base
    let urlActual = window.location.href;
    let palabraClave = "UIE/";
    let indice = urlActual.indexOf(palabraClave);
    let urlCortada = "";

    if (indice !== -1) {
        urlCortada = urlActual.substring(0, indice + palabraClave.length);
    }

    // Determinar la acción a enviar según el estado actual
    const accion = estadoActual === "Activo" ? "bloquear" : "desbloquear";

    fetch(urlCortada + "Controlador/CON_CambiarEstadoUser.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/json"
        },
        body: JSON.stringify({ IDUsuario: IdUsuario, accion: accion }) // Envía la acción y el ID del usuario
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const mensaje = accion === "bloquear" ? "Usuario bloqueado correctamente." : "Usuario desbloqueado correctamente.";
            alert(mensaje);
            cargarUsuarios(); // Actualiza 
        } else {
            throw new Error(data.error || "Error desconocido en el servidor");
        }
    })
    .catch(error => {
        console.error("Error al cambiar el estado de la cuenta:", error);
        alert("Ocurrió un error al procesar la solicitud.");
    });
}


function mostrarCambiarRolModal(idUsuario, rolActual) {
    const modalElement = document.getElementById('cambiarRolModal');
    const modal = bootstrap.Modal.getOrCreateInstance(modalElement);

    // Configura los datos del modal
    document.getElementById('idUsuario').value = idUsuario;
    document.getElementById('nuevoRol').value = rolActual;

    // Asegúrate de cerrar otros modales si es necesario
    if (modalElement.classList.contains('show')) {
        modal.hide();
    }

    modal.show();
    }

function guardarNuevoRol() {
    const idUsuario = document.getElementById('idUsuario').value;
    const nuevoRol = document.getElementById('nuevoRol').value;

    // Validación básica
    if (!idUsuario || !nuevoRol) {
        alert('Debe seleccionar un usuario y un rol válido.');
        return;
    }

    // Obtener la URL base
    let urlActual = window.location.href;
    let palabraClave = "UIE/";
    let indice = urlActual.indexOf(palabraClave);
    let urlCortada = "";

    if (indice !== -1) {
        urlCortada = urlActual.substring(0, indice + palabraClave.length);
    }

    // Realizar la solicitud al controlador
    fetch(urlCortada + "Controlador/CON_ModificarRolUser.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/json"
        },
        body: JSON.stringify({ idUsuario, idRol: nuevoRol })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Rol cambiado correctamente.');
            cargarUsuarios(); // Actualiza la tabla
            //bootstrap.Modal.getInstance(document.getElementById('cambiarRolModal')).hide();
        } else {
            alert('Error al cambiar el rol: ' + data.error);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Ocurrió un error al procesar la solicitud.');
    });
}
