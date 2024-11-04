function aprobarSitio(id_sitio) {
    const confirmacion = confirm('¿Estás segura de que deseas aprobar este sitio?');

    // Si el usuario confirma, proceder con la solicitud
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
            } else {
                alert('Error al aprobar el sitio');
            }
        })
        .catch(error => console.error('Error:', error));
    } else {
        // Si el usuario cancela, no se hace nada
        console.log('Aprobación cancelada por el usuario.');
    }
}
