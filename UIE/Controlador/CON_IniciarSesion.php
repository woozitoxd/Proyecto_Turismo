<?php
//require_once('../Modelo/conexion_bbdd.php');
require_once('../Modelo/MOD_perfil.php'); //incluyo la clase perfil porque existen metodos que utilizaré (consultar)
session_start(); // Inicia la sesión

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verificar que se enviaron datos y que no estén vacíos
    if (isset($_POST["correo_login"]) && isset($_POST["contraseña_login"]) && !empty($_POST["correo_login"]) && !empty($_POST["contraseña_login"])) {
        $correo = $_POST["correo_login"];
        $password = $_POST["contraseña_login"];
        
        // Consulta para verificar si el usuario y la contraseña coinciden
        $usuario = new perfilUser("", "", "", "");
        $resultado = $usuario->consultar($correo, $password); // Almaceno en resultado lo devuelto por la función consultar

        if ($resultado != null) { // Si obtuve algo en el valor devuelto
            $_SESSION['id'] = $resultado['id'];
            $_SESSION['usuario'] = $correo;
            $_SESSION['nombre'] = $resultado['nombre']; // Almacenar el nombre
            $_SESSION['id_rol'] = $resultado['id_rol']; // Almacenar el id_rol
            $_SESSION['nombre_rol'] = $resultado['nombre_rol']; // Almacenar el nombre del rol
            $_SESSION['fecha_nacimiento'] = $resultado['fecha_nacimiento'];
            
            // Retornar una respuesta JSON indicando éxito
            echo json_encode(['success' => true, 'message' => 'Inicio de sesión exitoso']);
        }else if($resultado === 0){
            echo json_encode(['success' => false, 'message' => 'Usted ha bloqueado su cuenta recientemente, para desbloquearla deberá contactar con "soporteTurismo@upe.edu.ar". ']);
        }else if ($resultado === null){
            // Retornar una respuesta JSON indicando error
            echo json_encode(['success' => false, 'message' => '¡El usuario o la contraseña son incorrectos!']);
        }
    } else {
        // Retornar una respuesta JSON indicando error por falta de datos
        echo json_encode(['success' => false, 'message' => 'Por favor, completa todos los campos.']);
    }
}
?>
