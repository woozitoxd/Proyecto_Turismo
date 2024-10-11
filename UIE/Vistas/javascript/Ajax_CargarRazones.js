


            document.addEventListener("DOMContentLoaded", function() {
                // Cargar razones (similar a tu código existente)
                let urlActual = window.location.href;
                let palabraClave = "UIE/";
                let indice = urlActual.indexOf(palabraClave);
            
                // Función para cargar razones
                if (indice !== -1) {
                    let urlCortada = urlActual.substring(0, indice + palabraClave.length);
            
                    fetch(urlCortada + "Controlador/CON_ObtenerRazones.php")
                        .then(res => res.json())
                        .then(data => {
                            const selectRazon = document.getElementById("razon");
                            selectRazon.innerHTML = '<option value="">Selecciona una razón</option>';
            
                            if (data.error) {
                                console.error(data.error);
                                return;
                            }
            
                            data.forEach(razon => {
                                const option = document.createElement("option");
                                option.value = razon.id_razon;
                                option.textContent = razon.descripcion;
                                selectRazon.appendChild(option);
                            });
                        })
                        .catch(err => console.error("Error al cargar las razones:", err));
                    } else {
                        console.log("La palabra 'UIE/' no se encontró en la URL.");
                    }
            
                    
            });
            // Enviar el formulario de denuncia