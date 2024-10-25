<?php
// Incluir el archivo de conexión a la base de datos
require_once('../Modelo/conexion_bbdd.php');
require_once('../Modelo/MOD_ClaseUsuario.php');
require_once('../Modelo/MOD_perfil.php');


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Capturamos los datos del formulario
    $nombre = isset($_POST["userName"]) ? trim($_POST["userName"]) : null;
    $correo = isset($_POST["correo"]) ? trim($_POST["correo"]) : null;
    $password = isset($_POST["registerPSW"]) ? trim($_POST["registerPSW"]) : null;
    $confirmarPassword = isset($_POST["confirmarContraseña"]) ? trim($_POST["confirmarContraseña"]) : null; // Capturamos la contraseña de confirmación
    $fecha_nacimiento = isset($_POST["fecha_Registro"]) ? trim($_POST["fecha_Registro"]) : null;
    
    // Instanciar la clase de usuario
    $usuario = new Usuario(null, $correo, $password, $fecha_nacimiento, $nombre);

    // Inicializamos un array para los errores
    $errores = [];

// Verificar si el nombre ya existe
$queryUsuario = "SELECT COUNT(*) FROM usuario WHERE nombre = :nombre"; // Cambiado a 'nombre'
$stmUsuario = $conn->prepare($queryUsuario);
$stmUsuario->bindParam(':nombre', $nombre); // Cambiado a 'nombre'
$stmUsuario->execute();
$existeUsuario = $stmUsuario->fetchColumn();

if ($existeUsuario > 0) {
    $errores[] = "El nombre de usuario ya está en uso.";
}

    
// Verificar si el correo electrónico ya existe
$queryCorreo = "SELECT COUNT(*) FROM usuario WHERE email = :correo"; // Cambiado a 'usuario' y 'email'
$stmCorreo = $conn->prepare($queryCorreo);
$stmCorreo->bindParam(':correo', $correo);
$stmCorreo->execute();
$existeCorreo = $stmCorreo->fetchColumn();

if ($existeCorreo > 0) {
    $errores[] = "El correo electrónico ya está en uso.";
}

    // Validaciones de nombre de usuario
    if (empty($nombre)) {
        $errores[] = "El nombre de usuario no puede estar vacío.";
    } elseif (!preg_match('/^[a-zA-Z0-9áéíóúÁÉÍÓÚ ]+$/', $nombre)) {
        $errores[] = "No se permiten caracteres especiales en el nombre de usuario.";
    }

    // Validaciones de correo
    if (empty($correo)) {
        $errores[] = "El correo no puede estar vacío.";
    } elseif (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        $errores[] = "Por favor, ingrese un correo electrónico válido.";
    }

    // Validaciones de contraseña
    if (empty($password)) {
        $errores[] = "La contraseña no puede estar vacía.";
    } elseif (strlen($password) < 8) {
        $errores[] = "La contraseña debe tener al menos 8 caracteres.";
    } elseif ($password !== $confirmarPassword) { // Comparar contraseñas
        $errores[] = "Las contraseñas no coinciden.";
    }

    // Validaciones de fecha de nacimiento
    if (empty($fecha_nacimiento)) {
        $errores[] = "La fecha de nacimiento no puede estar vacía.";
    } else {
        // Validar la fecha de nacimiento (mínimo 16 años)
        $fechaNacimiento = new DateTime($fecha_nacimiento);
        $fechaActual = new DateTime();
        $edad = $fechaActual->diff($fechaNacimiento)->y;
        if ($edad < 16) {
            $errores[] = "Debes tener al menos 16 años.";
        }
    }
    
     // Manejo de errores
     if (!empty($errores)) {
        header('Content-Type: application/json');
        echo json_encode(['status' => 'error', 'messages' => $errores]);
        exit; // Detener la ejecución si hay errores
    }

    // Si no hay errores, proceder a registrar
    $resultado = $usuario->registrar();
    
    if ($resultado) {
        header('Content-Type: application/json');
        echo json_encode(['status' => 'success', 'message' => 'Registro exitoso.']);
    } else {
        echo json_encode(['status' => 'error', 'messages' => ['Ocurrió un error al registrar.']]);
    }
}
?>
