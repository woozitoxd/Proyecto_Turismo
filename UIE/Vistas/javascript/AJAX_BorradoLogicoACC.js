document.getElementById("IDbtnEliminarCuenta").addEventListener("click", function () {
        // Obtener la URL base
        let urlActual = window.location.href;
        let palabraClave = "UIE/";
        let indice = urlActual.indexOf(palabraClave);
        let urlCortada = "";

        if (indice !== -1) {
            urlCortada = urlActual.substring(0, indice + palabraClave.length);
        }

        const idUsuario = document.getElementById("formEliminarCuenta").getAttribute("data-IDUsuario");

        fetch(urlCortada + "Controlador/CON_BorradoLogicoCuenta.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify({ IDUsuario: idUsuario })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert("Cuenta eliminada correctamente.");
                window.location.href = urlCortada + "Vistas/index.php"; // Redirigir al inicio
            } else {
                alert("Error al eliminar la cuenta: " + data.error);
            }
        })
        .catch(error => {
            console.error("Error al eliminar la cuenta:", error);
            alert("Ocurrió un error al procesar la solicitud.");
        });
    
});
