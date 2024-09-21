<?php
//require_once('../Modelo/conexion_bbdd.php');
require_once('../Modelo/MOD_perfil.php'); //incluyo la clase perfil porque existen metodos que utilizaré (consultar)
session_start(); // Inicia la sesión

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verificar que se enviaron datos y que no estén vacíos
    if (isset($_POST["correo"]) && isset($_POST["userName"]) && isset($_POST["contraseña"]) && !empty($_POST["correo"]) && !empty($_POST["contraseña"])) {
        $nombre = $_POST["userName"];
        $correo = $_POST["correo"];
        $password = $_POST["contraseña"];
        
        
        // consulta para verificar si el usuario y la contraseña coinciden
        $usuario = new perfilUser("", "", "", "");
        $resultado = $usuario->consultar($nombre, $correo, $password); //almaceno en resultado lo devuelto por la funcion consultar

        
        if ($resultado != null) { //si obtuve algo en el valor devuelto, establezco los campos en la sesion para que se inicie 
            $_SESSION['id'] = $resultado['id'];
            $_SESSION['usuario'] = $correo;
            $_SESSION['password'] = $password;

            header('Location: ../Vistas/index.php'); //vuelvo al index
        
        }else{
            echo "NO REGISTRADO";
            header('Location: ../Vistas/index.php'); 
        exit();
        }
    }
}
?>