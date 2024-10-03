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

    public function consultar($correo, $password) {
        $sql = "CALL SP_ConsultarUsuarioLOGIN(:correo)"; //uso el stored procedure que tengo en mi base de datos para traer los datos
        $stmt = $this->conexion->prepare($sql);  //traigo los datos tales como; nombre, correo, contraseña, id del rol, nombre del rol
        $stmt->bindParam(':correo', $correo, PDO::PARAM_STR);
        $stmt->execute();
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
    
        if ($usuario && password_verify($password, $usuario['password'])) {
            unset($usuario['password']); // Unset de la contraseña para no almacenarla en la sesión
            return $usuario; // Retornar el usuario con el id_rol y el nombre del rol
        } else {
            return null; // Usuario no encontrado o contraseña incorrecta
        }
    }
    
    

    public function consultarGoogleAuth($googleID, $googleEmail) //Consulta para trabajar con el inicio de sesion en funcion de los registros existentes en la base
    {
        $sql = "SELECT id, nombre, google_id, google_email FROM usuario WHERE google_id = :googleID AND google_email = :googleEMAIL";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bindParam(':googleID', $googleID, PDO::PARAM_STR);
        $stmt->bindParam(':googleEMAIL', $googleEmail, PDO::PARAM_STR);
        $stmt->execute();
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($usuario) {
            return $usuario;
        } else {
            return null;
        }
    }
}
?>