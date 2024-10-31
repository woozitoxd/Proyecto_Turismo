document.getElementById('verDetallesBtn').addEventListener('click', function() {
    let urlActual = window.location.href;
    let palabraClave = "UIE/";
    let indice = urlActual.indexOf(palabraClave);
    let urlCortada = indice !== -1 ? urlActual.substring(0, indice + palabraClave.length) : urlActual;

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
                usuariosTabla.innerHTML += `
                    <tr>
                        <td>${usuario.nombre}</td>
                        <td>${usuario.rol}</td>
                        <td>${usuario.email}</td>
                        <td>
                            <button class="btn btn-danger btn-sm"> Bloquear Usuario</button>
                            <button class="btn btn-warning btn-sm">Cambiar Rol</button>
                        </td>
                    </tr>
                `;
            });
            // Muestra el modal
            new bootstrap.Modal(document.getElementById('usuariosModal')).show();
        })
        .catch(error => console.error('Error:', error.message));
});