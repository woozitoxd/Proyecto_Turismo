<?php
require_once("../Controlador/CON_IniciarSesion.php");

$idComentario = $_GET['idComentario'] ?? null;

// Aquí podrías agregar lógica para verificar si el usuario está autenticado, etc.
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Denunciar Comentario</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css"> <!-- Iconos -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="../Vistas/javascript/Ajax_CargarRazones.js"></script> <!-- Cambia a la ruta correcta -->
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center">Denunciar Comentario</h1>
        <div class="card">
            <div class="card-body">
                <form id="denuncia-form" action="../Controlador/CON_ProcesarDenuncia.php" method="POST">
                    <input type="hidden" name="comentarioId" value="<?= htmlspecialchars($idComentario) ?>">
                    <div class="form-group">
                        <label for="razon">Razón de la denuncia:</label>
                        <select id="razon" name="listaRazones" class="form-control" required>
                            <option value="">Selecciona una razón</option>
                            <!-- Las razones se cargarán dinámicamente mediante AJAX -->
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="observacion">Observación:</label>
                        <textarea id="observacion" name="observacion" class="form-control" rows="3" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-danger">Denunciar</button>
                    <button type="button" class="btn btn-secondary" onclick="window.location.href='index.php';">Cancelar</button>
                </form>

            </div>
        </div>
    </div>
</body>
</html>

