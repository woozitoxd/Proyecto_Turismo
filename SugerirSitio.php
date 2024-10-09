<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/Proyecto_Turismo/UIE/Vistas/estilos/SugerirSitio.css">
    <script src="/Proyecto_Turismo/UIE/Vistas/javascript/SugerirSitio.js" defer></script>
    <!-- Bootstrap JS más reciente -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Bootstrap CSS más reciente -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    
    <title>Sugerir Sitio - Turismo</title>
</head>
<body>
    <header style="background-color: aqua; height: 75px;">
        <br>
        <p class="text-center">header</p>
    </header>
<form class="needs-validation" action="" method="get" id="form-">
    <div class="container-fluid mt-3 row ">

        <div class="mb-2 col-lg-6">
            <label class="h5 form-label" for="NombreSitioTuristico">Nombre de Sitio</label>
            <input class="form-control" type="text" name="NombreSitioTuristico" placeholder="Añade el nombre del Sitio Turistico!" aria-label=".form-control" id="NombreSitioTuristico" maxlength="100" required>
            
            <label for="SelectCategoria" class="h5 form-label mt-3 ">Categoria</label>
                <div class="d-inline">
                    <p class="d-inline EstilosCategorias" id="CategoriaSeleccionada"> Selecciona una Categoria!</p><!---aca deberia traerlo del select -->
                </div>
            <select id="SelectCategoria" class="form-select mt-2" onchange="actualizarCategoria()">
                <option>Selecciona una Categoria!</option><!---aca deberia traerlo de la BDD-->
                <option value="Categoria1" name="Categoria1">Categoria 1</option><!---aca deberia traerlo de la BDD-->
                <option value="Categoria2" name="Categoria2">Categoria 2</option>
            </select>


        <label for="SelectEtiqueta" class="h5 form-label mt-3 ">Etiquetas</label> 
        <button type="button" onclick="AgregarSelect()" class="btn btn-outline-primary d-inline">+</button>
        <button type="button"  onclick="LimpiarEtiquetas()" class="btn btn-outline-danger d-inline">Limpiar Etiquetas</button>
            <div class="row" id="IDContenedorSelectEtiquetas">
            <!--<div class="col-6" id="IDContenedorSelectEtiquetas">
                </div>
                <div class="col-6" id="IDContenedorNombreEtiquetas">
                </div>-->
            </div>  
        </div>

        <div class="mb-2 col-lg-6">
            <label for="Descripcion" class="h5 form-label">Descripción</label>
            <textarea class="form-control textarea-resize" id="Descripcion" name="Descripcion" placeholder="Añade una descripción al Sitios Turistico" required ></textarea>
        </div>

        <div class="mb-2 col-lg-6">
            <div class="mb-3">
                <label for="ImagenSitio" class=" h5 form-label">Imagenes de Sitio Turistico</label>
                <input type="file" id="ImagenSitio" class="form-control" accept="image/*" multiple onchange="previewImages(event)">
            </div>
        </div>
        <div class="mb-2 col-lg-6">
            <div class="mb-3">
                <label class="h5 form-label">Imágenes seleccionadas</label>  
                <div id="imagePreviewContainer" ></div>
            </div>
        </div>
        
        <div class="mb-2 col-lg-6"><label for="Latitud" class="form-label h5">Latitud & Longitud</label>
            <div class="input-group mb-1 MenorMargen">
                <input type="number" class="form-control" ID="Latitud" placeholder="Latitud" name="Latitud" >
                <span class="input-group-text">&</span>
                <input type="number" class="form-control" ID="Longitud" placeholder="Longitud" name="Longitud">
            </div>
        </div>
        <hr>

        <div class="position-relative mt-2">
            <div class="position-absolute top-0 start-50 translate-middle">
                <button type="submit" class="btn btn-outline-primary "  id="btn-ss" >Publicar</button>
            </div>
        </div>
    </div>
</form>


</body>
</html>