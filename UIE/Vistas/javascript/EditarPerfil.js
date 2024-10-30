
function showModal(message, isSuccess) {
    const modalContent = document.getElementById('modalContent');
    const modalMessage = document.getElementById('modalMessage');
    const modalIcon = document.getElementById('modalIcon');

    // Limpiar clases previas
    modalContent.classList.remove('success', 'error');
    modalIcon.classList.remove('success-icon', 'error-icon');

    if (isSuccess) {
        modalContent.classList.add('success');
        modalIcon.classList.add('success-icon');
    } else {
        modalContent.classList.add('error');
        modalIcon.classList.add('error-icon');
    }

    modalMessage.textContent = message;
    
    // Configurar el modal principal
    const resultModal = new bootstrap.Modal(document.getElementById('resultModal'));
    
    // Cambia la opacidad del modal anterior y añade fondo oscuro
    document.querySelectorAll('.modal.show').forEach(modal => {
        modal.style.opacity = '0.76'; // Aplica opacidad al modal anterior
    });

    // Mostrar el modal con la opacidad del fondo
    resultModal.show();

    // Restablece la opacidad cuando se cierra el modal principal
    document.getElementById('resultModal').addEventListener('hidden.bs.modal', () => {
        document.querySelectorAll('.modal').forEach(modal => {
            modal.style.opacity = ''; // Restaurar opacidad original
        });
    });
}


function EditarPerfil(urlVariable, NombreUsuario, Email, IDUsuario){
    //console.log('estoy en la funcion' + NombreUsuario + Email);

        // Crear un objeto con los datos que deseas enviar
        const data = {
            NombreUsuario: NombreUsuario,
            Email: Email,
            IDUsuario: IDUsuario
        };
        let url = urlVariable + '/../Controlador/CON_EditarPerfil.php';//console.log(url);
        // Realizar la solicitud POST usando fetch
        fetch(url, {
            method: 'POST', 
            headers: {
                'Content-Type': 'application/json' // Configura el tipo de contenido como JSON
            },
            body: JSON.stringify(data) // Convierte los datos a JSON y los envía.
        })
        .then(response => response.json()) // Parsear la respuesta como JSON
        .then(result => {
            // Manejar la respuesta del servidor
            if (result.success) {
                console.log('Perfil actualizado exitosamente');

            showModal('Perfil actualizado exitosamente', true);

            // Actualizar el contenido en el index y en el modal.
            document.getElementById('NombreEnMenu').textContent = NombreUsuario;
            document.getElementById('NombreUsuario').textContent = NombreUsuario;
            document.getElementById('Email').textContent = Email;

            } else {
            console.log(result.error);
            showModal(`Error: ${result.error}`, false);
            }
        })
        .catch(error => {
            console.log('Error en la solicitud (catch) ', error);
            showModal(`Error: Disculpe las molestias ocasiondas, error en la solicitud al servidor.`, false);
        });
}

function CambiarContraseñaUsuario(urlVariable, ContraseñaActual, NuevaContraseña, ConfirmaciónNuevaContraseña, IDUsuario){

    //console.log('estoy en la funcion ' + ContraseñaActual + NuevaContraseña + ConfirmaciónNuevaContraseña + IDUsuario);

    const data = {
        ContraseñaActual: ContraseñaActual,
        NuevaContraseña: NuevaContraseña,
        ConfirmaciónNuevaContraseña: ConfirmaciónNuevaContraseña,
        IDUsuario: IDUsuario
    };
    let url = urlVariable + '/../Controlador/CON_CambiarContraseña.php';
    fetch(url, {
        method: 'POST', // Especifica el método HTTP
        headers: {
            'Content-Type': 'application/json' // Configura el tipo de contenido como JSON
        },
        body: JSON.stringify(data) // Convierte los datos a JSON y los envía en el cuerpo de la solicitud
    })
    .then(response => response.json()) // Parsear la respuesta como JSON
    .then(result => {
        // Manejar la respuesta del servidor
        if (result.success) {
            console.log('Contraseña cambiada exitosamente');
            showModal('Contraseña actualizado exitosamente', true);
        } else {
            console.log('error en el fetch al cambiar la contraseña ',result.error);
            if(result.error==false){
                showModal(`Contraseña incorrecta`, false);
            }
            else{
            showModal(`${result.error} `, false);
            //showModal(`Error: ${result.error}`, false);
            }
        }
    })
    .catch(error => {
        console.log('Error en la solicitud de cambiar contraseña', error);
        showModal(`Error: Disculpe las molestias ocasiondas, error en la solicitud al servidor.`, false);
    });

}

//Funcion para validar mail
function isValidEmail(email) {
    // Expresion regular simple para validar correos electronicos
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
}
                    /*
                    document.getElementById("FormPerifl").addEventListener("submit", function(e) {

                        //alert('HOL312A');

                        //me traigo los data- del form con los datos de la session, si es el mismo que el que se quiere enviar en el input, muestro que se debe cambiar
                        const formPerfil = document.getElementById('FormPerifl');
                        const urlVariable = formPerfil.getAttribute('data-url-base');
                        const IDUsuario = formPerfil.getAttribute('data-IDUsuario');
                        const CorreoSesion = formPerfil.getAttribute('data-CorreoUsuario');
                        const NombreUsuarioSession = formPerfil.getAttribute('data-NombreUsuario');
                        
                        //console.log('id de usuario es ' + IDUsuario);

                        e.preventDefault(); //Se anula el envio del formulario

                        //Capturar los elementos del formulario:
                        const NombreUsuario = document.getElementById("NombreUsuario").value;
                        const Email = document.getElementById("Email").value;

                        const NombreUsuarioError = document.getElementById("NombreCompletoError");
                        const EmailError = document.getElementById("EmailError");
                        NombreUsuarioError.textContent = "";
                        EmailError.textContent = "";

                        let bandera = 0;
                        if (NombreUsuario.length <= 0) {
                            NombreCompletoError.textContent = "Ingrese un nombre.";
                            bandera += 1;
                        }

                        if (!isValidEmail(Email)) {
                            EmailError.textContent = "Ingrese un correo electrónico válido.";
                            bandera += 1;
                        }

                        if(bandera==0){

                            EditarPerfil(urlVariable, NombreUsuario, Email, IDUsuario) 
                            //this.submit();
                        }
                        if(bandera>0){
                            //alert('ta mal');
                        }
                    });*/

function ButtonEditarPerfil(IDUsuario, CorreoSesion, NombreUsuarioSession){
    //alert('HOL312A');

    //me traigo los data- del form con los datos de la session, si es el mismo que el que se quiere enviar en el input, muestro que se debe cambiar
    const formPerfil = document.getElementById('FormPerifl');
    const urlVariable = formPerfil.getAttribute('data-url-base');
    console.log("me traje " + IDUsuario+ " " +CorreoSesion+" "+NombreUsuarioSession);
    /*const IDUsuario = formPerfil.getAttribute('data-IDUsuario');
    const CorreoSesion = formPerfil.getAttribute('data-CorreoUsuario');
    const NombreUsuarioSession = formPerfil.getAttribute('data-NombreUsuario');*/
    
    //console.log('id de usuario es ' + IDUsuario);

    //e.preventDefault(); //Se anula el envio del formulario

    //Capturar los elementos del formulario:
    const NombreUsuario = document.getElementById("NombreUsuario").value;
    const Email = document.getElementById("Email").value;

    const NombreUsuarioError = document.getElementById("NombreCompletoError");
    const EmailError = document.getElementById("EmailError");
    NombreUsuarioError.textContent = "";
    EmailError.textContent = "";

    if(NombreUsuario == NombreUsuarioSession && Email == CorreoSesion){
        showModal(`Debe cambiar aunque sea un campo!`, false);
    }
    else{
        let bandera = 0;
        if (NombreUsuario.length <= 0) {
            NombreCompletoError.textContent = "Ingrese un nombre.";
            bandera += 1;
        }

        if (!isValidEmail(Email)) {
            EmailError.textContent = "Ingrese un correo electrónico válido.";
            bandera += 1;
        }

        if(bandera==0){

            EditarPerfil(urlVariable, NombreUsuario, Email, IDUsuario) 
            //this.submit();
        }
        if(bandera>0){
            //alert('ta mal');
        }
    }
}

document.getElementById("FormCambiarContraseña").addEventListener("submit", function(e) {

    //alert('HOL312A');
    const FormCambiarContraseña = document.getElementById('FormCambiarContraseña');
    const urlVariable = FormCambiarContraseña.getAttribute('data-url-base');
    const IDUsuario = FormCambiarContraseña.getAttribute('data-IDUsuario');
    console.log('id de usuario es ' + IDUsuario);

    e.preventDefault(); //Se anula el envio del formulario

    //Capturar los elementos del formulario:
    const ContraseñaActual = document.getElementById("ContraseñaActual").value;
    const NuevaContraseña = document.getElementById("NuevaContraseña").value;
    const ConfirmaciónNuevaContraseña = document.getElementById("ConfirmaciónNuevaContraseña").value;

    const ContraseñaActualError = document.getElementById("ContraseñaActualError");
    const NuevaContraseñaError = document.getElementById("NuevaContraseñaError");
    const ConfirmaciónNuevaContraseñaError = document.getElementById("ConfirmaciónNuevaContraseñaError");
    
    ContraseñaActualError.textContent = "";
    NuevaContraseñaError.textContent = "";
    ConfirmaciónNuevaContraseñaError.textContent = "";

    let bandera = 0;
    if (ContraseñaActual.length <= 7) {
        ContraseñaActualError.textContent = "La contraseña debe tener al menos 8 caracteres.";
        bandera += 1;
    }

    if (NuevaContraseña.length <= 7) {
        NuevaContraseñaError.textContent = "La nueva contraseña debe tener al menos 8 caracteres.";
        bandera += 1;
    }

    if (ConfirmaciónNuevaContraseña != NuevaContraseña) {
        ConfirmaciónNuevaContraseñaError.textContent = "Las contraseña no coinciden.";
        bandera += 1;
    }


    if(bandera==0){
        if(ContraseñaActual == ConfirmaciónNuevaContraseña && ContraseñaActual == NuevaContraseña){
            showModal('Debe cambiar la contraseña.',false);
            console.log("esaadas");
        }
        else{
        CambiarContraseñaUsuario(urlVariable, ContraseñaActual, NuevaContraseña, ConfirmaciónNuevaContraseña, IDUsuario);
        //this.submit();
        }

    }
    if(bandera>0){
        //alert('ta mal');
    }
});