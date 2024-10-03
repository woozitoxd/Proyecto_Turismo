<?php
require_once('conexion_bbdd.php');

class Comentarios
{
    
    private $IDusuario;
    private $IDcomentaro;
    private $description;
    private $NombreUSuario;
    private $conexion;

    public function __construct($IDusuario, $IDcomentaro, $description, $NombreUSuario)
    {
        $this->IDusuario = $IDusuario;
        $this->IDcomentaro = $IDcomentaro;
        $this->description = $description;
        $this->NombreUSuario = $NombreUSuario;
        
        try {
            // Utiliza la conexión centralizada
            $this->conexion = $GLOBALS['conn'];
        } catch (PDOException $e) {
            die("Error en la conexión de base de datos: " . $e->getMessage());
        }
    }

    public function AgregarComentario($UsuarioID, $descripcion, $IdSitio)
    {
        try {
            $sql = "INSERT INTO comentario (id_usuario, id_sitio, comentario, fechaPublicacion) VALUES (:UsuarioID, :IdSitio, :Descripcion, CURRENT_TIMESTAMP)";
            $stmt = $this->conexion->prepare($sql);
    
            if ($stmt) {
                $stmt->bindParam(':UsuarioID', $UsuarioID, PDO::PARAM_INT);
                $stmt->bindParam(':IdSitio', $IdSitio, PDO::PARAM_INT); // Vincula el id_sitio
                $stmt->bindParam(':Descripcion', $descripcion, PDO::PARAM_STR);
                if ($stmt->execute()) {
                    return true;
                } else {
                    return false;
                }
            } else {
                return false;
            }
        } catch (PDOException $e) {
            return "Error: " . $e->getMessage();
        }
    }
}
?>