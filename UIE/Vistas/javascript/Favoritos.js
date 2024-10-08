let urlActual = window.location.href;
let palabraClave = "UIE/";

// Encuentra el índice de la palabra "UIE/" en la URL
let indice = urlActual.indexOf(palabraClave);

document.addEventListener("DOMContentLoaded", function (){

    document.addEventListener("submit", function (e){

        if(e.target.classList.contains("fav-form")){
    
            e.preventDefault();

            // Si la palabra "UIE/" se encuentra en la URL
            if (indice !== -1) {

                // Guarda la URL desde el inicio hasta la palabra "UIE/"
                let urlCortada = urlActual.substring(0, indice + palabraClave.length);

                fetch(urlCortada + "Controlador/CON_Favorito.php", {
            
                    method: "POST",
                    body: new FormData(e.target)
                })
                .then(res => res.json())
                .then(data => {
        
                    //A partir de la verificación de un sitio guardado o no como favorito, modifico la interfaz.
        
                    const BtnFavorito = document.querySelector(`[data-fav-btn${e.target.dataset.postid}]`);
        
                    if (data.favoritoestado == "guardado") {
        
                        BtnFavorito.innerHTML = "Eliminar de favoritos <i class='bi bi-heart-fill'></i>";
                        BtnFavorito.classList.add("favorito-activo");

                    }else{

                        BtnFavorito.innerHTML = "Guardar en favoritos <i class='bi bi-heart-fill'></i>";
                        BtnFavorito.classList.remove("favorito-activo");

                    }
        
                });

            } else {
                console.log("La palabra 'UIE/' no se encontró en la URL.");
            }
        }
    });
});