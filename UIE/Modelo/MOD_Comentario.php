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

    public function AgregarComentario($UsuarioID, $descripcion, $IdSitio, $fechaYHora)
    {
        try {
            $sql = "INSERT INTO comentario (id_usuario, id_sitio, comentario, fechaPublicacion) VALUES (:UsuarioID, :IdSitio, :Descripcion, :FechaYHoraActual)";
            $stmt = $this->conexion->prepare($sql);
    
            if ($stmt) {
                $stmt->bindParam(':UsuarioID', $UsuarioID, PDO::PARAM_INT);
                $stmt->bindParam(':IdSitio', $IdSitio, PDO::PARAM_INT); // Vincula el id_sitio
                $stmt->bindParam(':Descripcion', $descripcion, PDO::PARAM_STR);
                $stmt->bindParam(':FechaYHoraActual', $fechaYHora, PDO::PARAM_STR);

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


    public function ReportarComentario($idDenuncia, $idComentario, $usuarioID, $observacion)
    {
        try {
            $sql = "INSERT INTO reporte_comentario (id_comentario, id_usuarioreporta, id_razon, fecha_reporte, observacion) 
                    VALUES (:idComentario, :usuarioID, :idDenuncia, CURRENT_TIMESTAMP, :observacion)";
            $stmt = $this->conexion->prepare($sql);
    
            // Vincular todos los parámetros correctamente
            $stmt->bindParam(':idComentario', $idComentario, PDO::PARAM_INT);
            $stmt->bindParam(':usuarioID', $usuarioID, PDO::PARAM_INT);
            $stmt->bindParam(':idDenuncia', $idDenuncia, PDO::PARAM_INT);
            $stmt->bindParam(':observacion', $observacion, PDO::PARAM_STR);
    
            if ($stmt->execute()) {
                return true; // La inserción fue exitosa
            } else {
                return "Error al insertar el reporte: " . implode(", ", $stmt->errorInfo()); // Mensaje de error detallado
            }
        } catch (PDOException $e) {
            return "Error de base de datos: " . $e->getMessage(); // Mensaje de error para depuración
        }
    }
    
    
    
}
?>