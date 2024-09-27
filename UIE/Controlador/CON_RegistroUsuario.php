<?php
    require_once('../Modelo/MOD_ClaseUsuario.php');

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $nombre = isset($_POST["userName"]) ? $_POST["userName"] : NULL;
        $correo = isset($_POST["correo"]) ? $_POST["correo"] : NULL;
        $password = isset($_POST["registerPSW"]) ? $_POST["registerPSW"] : NULL;
        $fecha_nacimiento = isset($_POST["fecha_Registro"]) ? $_POST["fecha_Registro"] : NULL;

        
        if (empty($_POST["name_registro"]) || empty($_POST["email_registro"]) || empty($_POST["passwordRegistro"]) || empty($_POST["fecha_Registro"])) {
            echo "Por favor, completa todos los campos para registrar el nuevo usuario de forma exitosa.";
        }//verifico que todos los campos estén completos.
        $hashedPassword = password_hash(password: $password, algo: PASSWORD_DEFAULT);

        $usuario = new Usuario(id: "",correo: $correo, password: $hashedPassword, fechaNacimiento: $fecha_nacimiento, nombre: $nombre);
        $resultado = $usuario->validaRequerido(nombre: $nombre, correo: $correo, fecha_nacimiento: $fecha_nacimiento); //primero se validan los campos y se almacena en resultado

        if ($resultado !== true) {
            echo 'Error: ' . $resultado; //Nota a futuro: imprimir el error en pantalla y no en consola.
            return false;

        } else {
            $resultado = $usuario->registrar(); // Intenta registrar el usuario

            if ($resultado === true) {

                header('Location: ../Vistas/index.php'); //Devuelve al index.
                exit();
            } else {
                echo $resultado;
            }
        }
    }
?>