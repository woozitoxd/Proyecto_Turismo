            // Definir la URL base según la estructura de tu proyecto
            document.addEventListener("DOMContentLoaded", function() {

            let urlActual = window.location.href;
            let palabraClave = "UIE/";
            let indice = urlActual.indexOf(palabraClave);
            let urlCortada = indice !== -1 ? urlActual.substring(0, indice + palabraClave.length) : urlActual; // Asegura que se establezca correctamente

            document.getElementById('denuncia-form').addEventListener('submit', function(event) {
                event.preventDefault(); // Prevenir el envío del formulario por defecto

                let formData = new FormData(this);
                let urlFormulario = urlCortada + "Controlador/CON_ProcesarDenuncia.php"; // URL del controlador

                fetch(urlFormulario, {
                    method: 'POST', // Asegúrate de que sea POST
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    const mensajeDiv = document.getElementById('mensaje');
                    mensajeDiv.style.display = 'block'; // Mostrar el div del mensaje

                    if (data.success) {
                        mensajeDiv.className = 'alert alert-success';
                        mensajeDiv.textContent = data.message; // Muestra el mensaje de éxito
                        document.getElementById('denuncia-form').reset(); // Limpia el formulario
                    } else {
                        mensajeDiv.className = 'alert alert-danger';
                        mensajeDiv.textContent = data.error; // Muestra el mensaje de error
                    }
                })
                .catch(err => {
                    const mensajeDiv = document.getElementById('mensaje');
                    mensajeDiv.style.display = 'block';
                    mensajeDiv.className = 'alert alert-danger';
                    mensajeDiv.textContent = 'Ocurrió un error al enviar la denuncia. Debes estar logeado para realizar esta acción.';
                    console.error("Error al enviar la denuncia:", err);
                });            
            });
        });
