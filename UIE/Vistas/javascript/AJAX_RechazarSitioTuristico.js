document.addEventListener('DOMContentLoaded', () => {
    const botonAprobar = document.getElementById('botonRechazarSitio');

    if (botonAprobar) {
        botonAprobar.addEventListener('click', () => {
            const idSitio = botonAprobar.getAttribute('data-bs-id');
            if (idSitio) {
                rechazarSitio(idSitio);
            } else {
                console.error('ID del sitio no encontrado en el atributo data-bs-id');
            }
        });
    } else {
        console.error('Botón "Rechazar Sitio" no encontrado.');
    }
});


function rechazarSitio(id_sitio) {
    const confirmacion = confirm('¿Estás segura de que deseas rechazar este sitio?');
    console.log('ID del sitio:', id_sitio);

    // Si el usuario confirma, proceder con la solicitud
    if (confirmacion) {
        fetch('../Controlador/CON_RechazarSitioTuristico.php', {
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
                alert('Sitio rechazado correctamente');
                  // Cerrar modal actual y volver al modal de sitios
                  const modalVistaPrevia = bootstrap.Modal.getInstance(document.getElementById('modalVistaPreviaSitio'));
                  modalVistaPrevia.hide();
  
                  const sitiosModal = new bootstrap.Modal(document.getElementById('sitiosModal'));
                  sitiosModal.show();
            } else {
                alert('Error al rechazar el sitio');
            }
        })
        .catch(error => console.error('Error:', error));
    } else {
        // Si el usuario cancela, no se hace nada
        console.log('Rechazo cancelado por el usuario.');
    }
}
