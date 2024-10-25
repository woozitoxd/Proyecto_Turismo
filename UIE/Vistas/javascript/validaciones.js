// Función para manejar la validación y el envío del formulario de login
async function validarFormLogin(e) {
    e.preventDefault(); // Evitamos el envío del formulario por defecto

    // Seleccionamos los elementos necesarios
    const formulario = document.getElementById('formulario-login');
    const campoCorreo = document.getElementById('correo_login');
    const campoContraseña = document.getElementById('contraseña_login');
    const errorCorreo = document.getElementById('errorCorreo');
    const errorClave = document.getElementById('errorClave');

    // Limpiar clases y mensajes de error previos
    limpiarErrores(campoCorreo, errorCorreo);
    limpiarErrores(campoContraseña, errorClave);
    let esValido = true;

    // Validar el campo de correo
    if (campoCorreo.value.trim() === '') {
        mostrarError(campoCorreo, errorCorreo, 'El campo correo no puede estar vacío.');
        esValido = false;
    } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(campoCorreo.value.trim())) {
        mostrarError(campoCorreo, errorCorreo, 'Por favor, ingrese un correo electrónico válido.');
        esValido = false;
    }

    // Validar el campo de contraseña
    if (campoContraseña.value.trim() === '') {
        mostrarError(campoContraseña, errorClave, 'La contraseña no puede estar vacía.');
        esValido = false;
    }

    // Si es válido, procedemos con la petición AJAX
    if (esValido) {
        try {
            // Construimos los datos para enviar
            const datos = new FormData(formulario);
            const response = await fetch('../Controlador/CON_IniciarSesion.php', {
                method: 'POST',
                body: datos,
            });

            const resultado = await response.json(); // Procesamos la respuesta como JSON

            if (resultado.success) {
                window.location.href = '../Vistas/index.php'; // Redirigir en caso de éxito
            } else {
                mostrarErrorGlobal(resultado.message); // Mostrar el mensaje de error en caso de fallo
            }
        } catch (error) {
            console.log('Error en la petición:', error);
            mostrarErrorGlobal('Ocurrió un problema al iniciar sesión.');
        }
    }
}

// Función para mostrar errores en campos específicos
function mostrarError(campo, mensajeElemento, mensaje) {
    campo.classList.add('is-invalid'); // Añadir la clase de error al campo
    mensajeElemento.innerHTML = mensaje; // Mostrar el mensaje de error
}
// Funciones para mostrar errores globales
function mostrarErrorGlobal(mensaje) {
    const errorGlobal = document.getElementById('errorGlobal');
    errorGlobal.classList.remove('d-none');
    errorGlobal.innerHTML = mensaje;
}

// Función para limpiar mensajes de error
function limpiarErrores(campo, mensajeElemento) {
    campo.classList.remove('is-invalid');
    mensajeElemento.innerHTML = '';
}
// Función para mostrar mensajes de éxito
function mostrarMensajeExito(mensaje) {
    const mensajeExito = document.getElementById('mensajeExito'); // Asegúrate de que este ID existe en tu HTML
    mensajeExito.classList.remove('d-none');
    mensajeExito.innerHTML = mensaje;
}
// Función principal para validar el formulario de registro
async function validarFormRegistro(e) {
    e.preventDefault(); // Evitamos el envío del formulario por defecto
    const formulario = document.getElementById('formulario-registro');
    const userName = document.getElementById('userName');
    const correo = document.getElementById('correo');
    const registerPSW = document.getElementById('registerPSW');
    const confirmarContraseña = document.getElementById('confirmarContraseña');
    const fechaRegistro = document.getElementById('fecha_Registro');
    const errorUserName = document.getElementById('errorUserName');
    const errorCorreoRegistro = document.getElementById('errorCorreoRegistro');
    const errorRegisterPSW = document.getElementById('errorRegisterPSW');
    const errorConfirmarPSW = document.getElementById('errorConfirmarPSW');
    const errorFechaNacimiento = document.getElementById('errorFechaNacimiento');

    // Limpiar clases y mensajes de error previos
    limpiarErrores(userName, errorUserName);
    limpiarErrores(correo, errorCorreoRegistro);
    limpiarErrores(registerPSW, errorRegisterPSW);
    limpiarErrores(confirmarContraseña, errorConfirmarPSW);
    limpiarErrores(fechaRegistro, errorFechaNacimiento);

    let esValido = true;

    // Validar que los campos no estén vacíos
    const expresionRegular = /^[a-zA-Z0-9áéíóúÁÉÍÓÚ ]+$/;
    if (userName.value.trim() === '') {
        mostrarError(userName, errorUserName, 'El nombre de usuario no puede estar vacío.');
        esValido = false;
    } else if (!expresionRegular.test(userName.value.trim())) {
        mostrarError(userName, errorUserName, 'No se permiten caracteres especiales.');
        esValido = false;
    }

    if (correo.value.trim() === '') {
        mostrarError(correo, errorCorreoRegistro, 'El correo no puede estar vacío.');
        esValido = false;
    } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(correo.value.trim())) {
        mostrarError(correo, errorCorreoRegistro, 'Por favor, ingrese un correo electrónico válido.');
        esValido = false;
    }

    if (registerPSW.value.trim() === '') {
        mostrarError(registerPSW, errorRegisterPSW, 'La contraseña no puede estar vacía.');
        esValido = false;
    } else if (registerPSW.value.length < 8) {
        mostrarError(registerPSW, errorRegisterPSW, 'La contraseña debe tener al menos 8 caracteres.');
        esValido = false;
    }

    if (confirmarContraseña.value.trim() === '') {
        mostrarError(confirmarContraseña, errorConfirmarPSW, 'Debes confirmar tu contraseña.');
        esValido = false;
    } else if (registerPSW.value !== confirmarContraseña.value) {
        mostrarError(confirmarContraseña, errorConfirmarPSW, 'Las contraseñas no coinciden.');
        esValido = false;
    }

    if (fechaRegistro.value.trim() === '') {
        mostrarError(fechaRegistro, errorFechaNacimiento, 'La fecha de nacimiento no puede estar vacía.');
        esValido = false;
    } else {
        const fechaNacimiento = new Date(fechaRegistro.value);
        const fechaActual = new Date();
        let edad = fechaActual.getFullYear() - fechaNacimiento.getFullYear();
        const mes = fechaActual.getMonth() - fechaNacimiento.getMonth();
        if (mes < 0 || (mes === 0 && fechaActual.getDate() < fechaNacimiento.getDate())) {
            edad--;
        }
        if (edad < 16) {
            mostrarError(fechaRegistro, errorFechaNacimiento, 'Debes tener al menos 16 años.');
            esValido = false;
        }
    }

    // Si es válido, procedemos con la petición AJAX
    if (esValido) {
        try {
            const datos = new FormData(formulario);
            const response = await fetch('../Controlador/CON_RegistroUsuario.php', {
                method: 'POST',
                body: datos,
            });

            const resultado = await response.json(); // Procesamos la respuesta como JSON
            console.log(resultado); // Verifica la respuesta del servidor

            if (resultado.status === 'success') {
                // Mostrar el modal de éxito
                const modalExito = new bootstrap.Modal(document.getElementById('modalExito'));
                modalExito.show();
                // Redirigir en caso de éxito después de un breve retraso
                setTimeout(() => {
                    window.location.href = '../Vistas/index.php'; // Redirigir a la página de éxito
                }, 2500); // 2.5 segundos de retraso
            } else if (resultado.status === 'error') {
                mostrarErrorReg(resultado.messages); // Mostrar los errores en caso de fallo
            }
        } catch (error) {
            mostrarErrorReg([`Ocurrió un problema al registrarse. Detalles: ${error.message}`]);
            //mostrarErrorReg('Ocurrió un problema al registrarse. Detalles: ' + error.message);
        }
    }
}
// Función para mostrar errores en el formulario de registro
function mostrarErrorReg(mensajes) {
    const errorRegistro = document.getElementById('errorRegistro'); // Accedemos directamente al id 'errorRegistro'
    errorRegistro.classList.remove('d-none'); // Muestra el contenedor de error

    // Construimos el HTML con viñetas
    const listaErrores = mensajes.map(error => `<li>${error}</li>`).join('');
    errorRegistro.innerHTML = `<ul>${listaErrores}</ul>`; // Establece el mensaje de error como una lista
}

// Función de inicio que agrega el evento 'submit' al formulario
function inicio() {
    document.getElementById('formulario-login').addEventListener('submit', validarFormLogin);
    document.getElementById('formulario-registro').addEventListener('submit', validarFormRegistro);
}

// Llamamos a la función 'inicio' cuando el documento esté completamente cargado
window.onload = inicio;

