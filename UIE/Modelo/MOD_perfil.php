<?php

require_once('conexion_bbdd.php');

class perfilUser //clase perfil usuario que trabaja con las consultas que me inicia la sesion y el nombre existente durante el registro de usuario
{
    private $nombre;
    private $correo;
    private $password;
    private $fechaNacimiento;
    private $conexion;

    /*public function __construct($correo, $password, $fechaNacimiento, $nombre)
    {
        $this->nombre = $nombre;
        $this->correo = $correo;
        $this->password = $password;
        $this->fechaNacimiento = $fechaNacimiento;
        
        $this->conexion = $GLOBALS['conn'];

    }*/
    public function __construct($correo = null, $password = null, $fechaNacimiento = null, $nombre = null) 
    {
        $this->conexion = $GLOBALS['conn'];
        
        // Solo inicializar propiedades si se pasan valores
        if ($correo !== null && $password !== null && $fechaNacimiento !== null && $nombre !== null) {
            $this->correo = $correo;
            $this->password = $password;
            $this->fechaNacimiento = $fechaNacimiento;
            $this->nombre = $nombre;
        }
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
        try{
            $sql = "CALL SP_ConsultarUsuarioLOGIN(:correo)"; //uso el stored procedure que tengo en mi base de datos para traer los datos
            $stmt = $this->conexion->prepare($sql);  //traigo los datos tales como; nombre, correo, contraseña, id del rol, nombre del rol
            $stmt->bindParam(':correo', $correo, PDO::PARAM_STR);
            $stmt->execute();
            $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
            
            
            if($usuario['estado'] == 0){ //verificamos que el estado no sea 0, que en nuestra logica, significa bloqueado.
                return 0; //retorno 0 porque es la validacion que usaré
            }else if ($usuario && password_verify($password, $usuario['password'])) {
                unset($usuario['password']); // Unset de la contraseña para no almacenarla en la sesión
                return $usuario; // Retornar el usuario con el id_rol y el nombre del rol
            } else {
                return null; // Usuario no encontrado o contraseña incorrecta
            }

        }catch (PDOException $e ){
            return $e->getMessage();
        }
    }
    
    

    public function consultarGoogleAuth($googleID, $googleEmail) //Consulta para trabajar con el inicio de sesion en funcion de los registros existentes en la base
    {
        try{

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
        }catch (PDOException $e ){
            return $e->getMessage();
        }
    }
    public function ActualizarPerfilUsuario($idUsuario, $nombre, $email) {
        try {
            $sql = "CALL SP_ActualizarPerfilUsuario(:idUsuario, :nombre, :email)";
            $stmt = $this->conexion->prepare($sql);
            
            $stmt->bindParam(':idUsuario', $idUsuario, PDO::PARAM_INT);
            $stmt->bindParam(':nombre', $nombre, PDO::PARAM_STR);
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    
            $stmt->execute();
            
            return true; // Indica éxito si no hay errores
        } catch (PDOException $e) {
            // Obtener el mensaje de error original
            $errorMessage = $e->getMessage();

            // Usar una expresión regular para extraer solo el mensaje de error limpio
            if (preg_match('/:\s\d+\s(.+)/', $errorMessage, $matches)) {
                $cleanMessage = $matches[1];
            } else {
                $cleanMessage = 'Error desconocido';
            }
    
            return $cleanMessage;
        }
    }

    public function ActualizarContraseñaUsuario($idUsuario, $ContraseñaActual, $ContraseñaNueva) {
        try {
            $sql = 'SELECT password FROM usuario WHERE id = :idusuario';
            $stmt = $this->conexion->prepare($sql);
            $stmt->bindParam(':idusuario', $idUsuario, PDO::PARAM_INT);
            $stmt->execute();
            $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($usuario && password_verify($ContraseñaActual, $usuario['password'])) {
                unset($usuario['password']);

                if($ContraseñaActual == $ContraseñaNueva){
                    return 'La contraseña ingresada ya esta en actual uso para esta cuenta.';
                }
                else{
                    
                    try {
                        $hashNuevaContraseña = password_hash($ContraseñaNueva, PASSWORD_DEFAULT);
            
                        // Actualizamos la contraseña en la base de datos
                        $updateSql = 'UPDATE usuario SET password = :passwordNueva WHERE id = :idusuario';
                        $updateStmt = $this->conexion->prepare($updateSql);
                        $updateStmt->bindParam(':passwordNueva', $hashNuevaContraseña, PDO::PARAM_STR);
                        $updateStmt->bindParam(':idusuario', $idUsuario, PDO::PARAM_INT);
                        $updateStmt->execute();
                    return true; 
                    } catch (PDOException $e) {    
                        return $e->getMessage();
                    }
                }
            }
            else {
                return false; // Usuario no encontrado o contraseña incorrecta
            }
        }
        catch (PDOException $e) {
            return $e->getMessage();
        }
    }

    public function BorradoLogicoCuenta($idUsuario){
        try{
            $updatesql = 'UPDATE usuario SET estado = 0 WHERE id = :idUsuario';
            $updateStmt = $this->conexion->prepare($updatesql);
            $updateStmt->bindParam(':idUsuario', $idUsuario, PDO::PARAM_INT);
            $updateStmt->execute();
            return true;
        } catch(PDOException $e) {
            return $e->getMessage();
        }
    }

    public function BloquearCuenta($idUsuario){
        try{
            $updatesql = 'UPDATE usuario SET estado = 0 WHERE id = :idUsuario';
            $updateStmt = $this->conexion->prepare($updatesql);
            $updateStmt->bindParam(':idUsuario', $idUsuario, PDO::PARAM_INT);
            $updateStmt->execute();
            return true;
        } catch(PDOException $e) {
            return $e->getMessage();
        }
    }
    
    public function DesbloquearCuenta($idUsuario){
        try{
            $updatesql = 'UPDATE usuario SET estado = 1 WHERE id = :idUsuario';
            $updateStmt = $this->conexion->prepare($updatesql);
            $updateStmt->bindParam(':idUsuario', $idUsuario, PDO::PARAM_INT);
            $updateStmt->execute();
            return true;
        } catch(PDOException $e) {
            return $e->getMessage();
        }
    }


    public function CambiarRolUsuario($idUsuario, $idRol) {
        try {
            $updateSql = 'UPDATE usuario SET id_rol = :idRol WHERE id = :idUsuario';
            $updateStmt = $this->conexion->prepare($updateSql);
            $updateStmt->bindParam(':idRol', $idRol, PDO::PARAM_INT);
            $updateStmt->bindParam(':idUsuario', $idUsuario, PDO::PARAM_INT);
            $updateStmt->execute();
            return true;
        } catch (PDOException $e) {
            return $e->getMessage();
        }
    }
    
    
    


}
?>