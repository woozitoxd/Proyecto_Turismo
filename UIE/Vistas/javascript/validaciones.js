// Función principal para validar el formulario
function validarFormLogin(e) {
    e.preventDefault(); // Evitamos el envío del formulario por defecto

    // Seleccionamos los elementos necesarios
    const formulario = document.getElementById('formulario-login');
    const campoCorreo = document.getElementById('correo_login');
    const campoContraseña = document.getElementById('contraseña_login');
    const errorCorreo = document.getElementById('errorCorreo');

    // Limpiar clases y mensajes de error previos
    campoCorreo.classList.remove('is-invalid');
    campoContraseña.classList.remove('is-invalid');
    errorCorreo.innerHTML = '';
    errorClave.innerHTML = '';  // Limpiamos el mensaje de error de contraseña

    let esValido = true;

    // Validar el campo de correo
    if (campoCorreo.value.trim() === '') {
        campoCorreo.classList.add('is-invalid');
        errorCorreo.innerHTML = 'El campo correo no puede estar vacío.'; // Mensaje de error
        esValido = false;
    } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(campoCorreo.value.trim())) {
        campoCorreo.classList.add('is-invalid');
        errorCorreo.innerHTML = 'Por favor, ingrese un correo electrónico válido.'; // Mensaje de error
        esValido = false;
    }

    // Validar el campo de contraseña
    if (campoContraseña.value.trim() === '') {
        campoContraseña.classList.add('is-invalid');
        errorClave.innerHTML = 'La contraseña no puede estar vacía.'; // Mensaje de error
        esValido = false;
    }

    // Si es válido, se envia el formulario
    if (esValido) {
        formulario.submit();
    }
}

// Función principal para validar el formulario de registro
function validarFormRegistro(e) {
    e.preventDefault(); // Evitamos el envío del formulario por defecto
    // Seleccionamos los elementos necesarios
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
    userName.classList.remove('is-invalid');
    correo.classList.remove('is-invalid');
    registerPSW.classList.remove('is-invalid');
    confirmarContraseña.classList.remove('is-invalid');
    fechaRegistro.classList.remove('is-invalid');
    errorUserName.innerHTML = '';
    errorCorreoRegistro.innerHTML = '';
    errorRegisterPSW.innerHTML = '';
    errorConfirmarPSW.innerHTML = '';
    errorFechaNacimiento.innerHTML = '';

    let esValido = true;

    // Validar que los campos no estén vacíos
    let expresionRegular = /^[a-zA-Z0-9áéíóúÁÉÍÓÚ ]+$/;
    if (userName.value.trim() === '') {
        userName.classList.add('is-invalid');
        errorUserName.innerHTML = 'El nombre de usuario no puede estar vacío.';
        esValido = false;
    } else if (!expresionRegular.test(userName.value.trim())) {  //validar que no puedan existir caracteres especiales
        userName.classList.add('is-invalid');
        errorUserName.innerHTML = 'No se permiten caracteres especiales.';
        esValido = false;
    }

    if (correo.value.trim() === '') {
        correo.classList.add('is-invalid');
        errorCorreoRegistro.innerHTML = 'El correo no puede estar vacío.';
        esValido = false;
    } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(correo.value.trim())) {
        correo.classList.add('is-invalid');
        errorCorreoRegistro.innerHTML = 'Por favor, ingrese un correo electrónico válido.';
        esValido = false;
    }

    if (registerPSW.value.trim() === '') {
        registerPSW.classList.add('is-invalid');
        errorRegisterPSW.innerHTML = 'La contraseña no puede estar vacía.';
        esValido = false;
    } else if (registerPSW.value.length < 8) {     // Validar la contraseña (mínimo 8 caracteres)
        registerPSW.classList.add('is-invalid');
        errorRegisterPSW.innerHTML = 'La contraseña debe tener al menos 8 caracteres.';
        esValido = false;
    }

    if (confirmarContraseña.value.trim() === '') {
        confirmarContraseña.classList.add('is-invalid');
        errorConfirmarPSW.innerHTML = 'Debes confirmar tu contraseña.';
        esValido = false;
    }
    // Validar que las contraseñas coincidan
    if (registerPSW.value !== confirmarContraseña.value) {
        confirmarContraseña.classList.add('is-invalid');
        errorConfirmarPSW.innerHTML = 'Las contraseñas no coinciden.';
        esValido = false;
    }

    if (fechaRegistro.value.trim() === '') {
        fechaRegistro.classList.add('is-invalid');
        errorFechaNacimiento.innerHTML = 'La fecha de nacimiento no puede estar vacía.';
        esValido = false;
    }

    // Validar la fecha de nacimiento (mínimo 16 años)
    const fechaNacimiento = new Date(fechaRegistro.value);
    const fechaActual = new Date();
    let edad = fechaActual.getFullYear() - fechaNacimiento.getFullYear();
    const mes = fechaActual.getMonth() - fechaNacimiento.getMonth();
    if (mes < 0 || (mes === 0 && fechaActual.getDate() < fechaNacimiento.getDate())) {
        edad--;
    }
    if (edad < 16) {
        fechaRegistro.classList.add('is-invalid');
        errorFechaNacimiento.innerHTML = 'Debes tener al menos 16 años.';
        esValido = false;
    }

    // Si es válido, se envía el formulario
    if (esValido) {
        // Mostrar el modal de éxito
        const modalExito = new bootstrap.Modal(document.getElementById('modalExito'));
        modalExito.show();
        // Enviar el formulario después de un breve retraso
        setTimeout(() => {
            formulario.submit();
        }, 2500); // 1.5 segundos de retraso
    }
}

// Función de inicio que agrega el evento 'submit' al formulario
function inicio() {
    document.getElementById('formulario-login').addEventListener('submit', validarFormLogin);
    document.getElementById('formulario-registro').addEventListener('submit', validarFormRegistro);
}

// Llamamos a la función 'inicio' cuando el documento esté completamente cargado
window.onload = inicio;

