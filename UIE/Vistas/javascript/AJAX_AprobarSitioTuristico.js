const botonAprobar = document.getElementById('botonAprobarSitio');
if (botonAprobar) {
    botonAprobar.addEventListener('click', () => {
        const idSitio = botonAprobar.getAttribute('data-bs-id');
        if (idSitio) {
            aprobarSitio(idSitio);
        } else {
            console.error('ID del sitio no encontrado en el atributo data-bs-id');
        }
    });
}



function aprobarSitio(id_sitio) {
    const confirmacion = confirm('¿Estás segura de que deseas aprobar este sitio?');
    console.log('ID del sitio:', id_sitio);

    if (confirmacion) {
        fetch('../Controlador/CON_AprobarSitioTuristico.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ id_sitio: id_sitio })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Elimina el elemento del DOM una vez aprobado
                const sitioRow = document.getElementById(`sitio-row-${id_sitio}`);
                if (sitioRow) {
                    sitioRow.remove();
                }
                alert('Sitio aprobado correctamente');

                // Cerrar modal actual y volver al modal de sitios
                const modalVistaPrevia = bootstrap.Modal.getInstance(document.getElementById('modalVistaPreviaSitio'));
                modalVistaPrevia.hide();

                const sitiosModal = new bootstrap.Modal(document.getElementById('sitiosModal'));
                sitiosModal.show();
            } else {
                alert('Error al aprobar el sitio');
            }
        })
        .catch(error => console.error('Error:', error));
    } else {
        console.log('Aprobación cancelada por el usuario.');
    }
}