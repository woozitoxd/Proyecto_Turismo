<?php

require_once('conexion_bbdd.php');

class perfilUser //clase perfil usuario que trabaja con las consultas que me inicia la sesion y el nombre existente durante el registro de usuario
{
    private $nombre;
    private $correo;
    private $password;
    private $fechaNacimiento;
    private $conexion;

    public function __construct($correo, $password, $fechaNacimiento, $nombre)
    {
        $this->nombre = $nombre;
        $this->correo = $correo;
        $this->password = $password;
        $this->fechaNacimiento = $fechaNacimiento;
        
        $this->conexion = $GLOBALS['conn'];

    }

    public function verificarNombreExistente($nombre, $idUser)
    {   //Funcion creada para la verificacion del nombre existente, se utiliza para evitar que se repitan registros en la base
        // Consulta para verificar si el nombre ya existe, excluyendo el nombre actual del usuario
        $sql = "SELECT COUNT(*) as total FROM usuario WHERE nombre = :nombre AND ID != :idUser"; 
        $stmt = $this->conexion->prepare($sql);
        if($stmt){
            $stmt->bindParam(':nombre', $nombre, PDO::PARAM_STR);
            $stmt->bindParam(':idUser', $idUser, PDO::PARAM_INT);
            $stmt->execute();
            
            $total = $stmt->fetchColumn();
    
            // Si el total es mayor a 0, significa que el nombre ingresado ya existe
            return $total > 0;
        }else{
            return false;
        }
    }

    public function consultar($nombre, $correo, $password) //Consulta para trabajar con el inicio de sesion en funcion de los registros existentes en la base
    {
        $sql = "SELECT id, nombre, email, fecha_nacimiento, password FROM usuario WHERE nombre = :nombre AND email = :correo";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bindParam(':nombre', $nombre, PDO::PARAM_STR);
        $stmt->bindParam(':correo', $correo, PDO::PARAM_STR);
        $stmt->execute();
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
    
        if ($usuario && password_verify($password, $usuario['password'])) { //Funcion password_verify para verificar si la contraseña coincide con la almacenada en la BBDD
            unset($usuario['password']); // Unset de la contraseña para no almacenarla en la sesion
            return $usuario;
        } else {
            return null;
        }
    }
}
?>