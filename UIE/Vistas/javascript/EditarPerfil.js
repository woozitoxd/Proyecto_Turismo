

function EditarPerfil(urlVariable, NombreUsuario, Email, IDUsuario){
    //console.log('estoy en la funcion' + NombreUsuario + Email);

        // Crear un objeto con los datos que deseas enviar
        const data = {
            NombreUsuario: NombreUsuario,
            Email: Email,
            IDUsuario: IDUsuario
        };
        let url = urlVariable + '/../Controlador/CON_EditarPerfil.php';
        //console.log('dasdasdasd '+url);
        // Realizar la solicitud POST usando fetch
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
                console.log('Perfil actualizado exitosamente');
                
                const NombreEnMenu = document.getElementById('NombreEnMenu');
                NombreEnMenu.textContent = NombreUsuario;
                
                const NombreUsuarioEnform = document.getElementById('NombreUsuario');
                NombreUsuarioEnform.textContent = NombreUsuario;

                const EmailEnform = document.getElementById('Email');
                EmailEnform.textContent = Email;

            } else {
                console.log('estoy aca ',result.error);
            }
        })
        .catch(error => {
            console.log('Error en la solicitud', error);
            console.error('Error en la solicitud:', error);
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
    /*console.log(ContraseñaActual);
    console.log(NuevaContraseña);
    console.log(ConfirmaciónNuevaContraseña);
    console.log(IDUsuario);*/
    // Realizar la solicitud POST usando fetch
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

        } else {
            console.log('error en el fetch al cambiar la contraseña ',result.error);
        }
    })
    .catch(error => {
        console.log('Error en la solicitud de cambiar contraseña', error);
        console.error('Error en la solicitud de cambiar contraseña:', error);
    });

}

//Funcion para validar mail
function isValidEmail(email) {
    // Expresion regular simple para validar correos electronicos
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
}

document.getElementById("FormPerifl").addEventListener("submit", function(e) {

    //alert('HOL312A');
    const formPerfil = document.getElementById('FormPerifl');
    const urlVariable = formPerfil.getAttribute('data-url-base');
    const IDUsuario = formPerfil.getAttribute('data-IDUsuario');
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
        EditarPerfil(urlVariable, NombreUsuario, Email, IDUsuario);
        //this.submit();
    }
    if(bandera>0){
        alert('ta mal');
    }
});


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
        CambiarContraseñaUsuario(urlVariable, ContraseñaActual, NuevaContraseña, ConfirmaciónNuevaContraseña, IDUsuario);
        //this.submit();
    }
    if(bandera>0){
        alert('ta mal');
    }
});