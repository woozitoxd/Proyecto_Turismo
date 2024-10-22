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

    public function AgregarComentario($UsuarioID, $descripcion, $valoracion, $IdSitio, $fechaYHora)
    {
        try {
            $sql = "INSERT INTO comentario (id_usuario, id_sitio, comentario, valoracion, fechaPublicacion) VALUES (:UsuarioID, :IdSitio, :Descripcion, :Valoracion, :FechaYHoraActual)";
            $stmt = $this->conexion->prepare($sql);
    
            if ($stmt) {
                $stmt->bindParam(':UsuarioID', $UsuarioID, PDO::PARAM_INT);
                $stmt->bindParam(':IdSitio', $IdSitio, PDO::PARAM_INT); // Vincula el id_sitio
                $stmt->bindParam(':Descripcion', $descripcion, PDO::PARAM_STR);
                $stmt->bindParam(':Valoracion', $valoracion, PDO::PARAM_STR);
                $stmt->bindParam(':FechaYHoraActual', $fechaYHora, PDO::PARAM_STR);

                if ($stmt->execute()) {
                    $idComentario = $this->conexion->lastInsertId();
                    return $idComentario;
                } else {
                    return null;
                }
            } else {
                return null;
            }
        } catch (PDOException $e) {
            return "Error: " . $e->getMessage();
        }
    }

    public function VerificarComentarioExistente($ID_Sitio, $ID_Usuario){

        try {
            $sql = "SELECT 
                comentario.id_comentario 
            FROM 
                comentario 
            WHERE 
                comentario.id_sitio = :ID_Sitio
            AND
                comentario.id_usuario = :ID_Usuario";
                
            $stmt = $this->conexion->prepare($sql);
    
            if ($stmt) {
                $stmt->bindParam(':ID_Sitio', $ID_Sitio, PDO::PARAM_INT);
                $stmt->bindParam(':ID_Usuario', $ID_Usuario, PDO::PARAM_INT);

                if ($stmt->execute()) {

                    $campos = $stmt->fetchAll(\PDO::FETCH_ASSOC);

                    if (count($campos) > 0) {
                        return true;
                    } else {
                        return false;
                    }
                }

            } else {
                
                return null;

            }
        } catch (PDOException $e) {
            return "Error: " . $e->getMessage();
        }

        if (!isset($GLOBALS['conn'])) {
            require_once 'conexion_bbdd.php';
        }
        
        /** @var \PDO $conn */
        $conn = $GLOBALS['conn'];
        $queryStr = "
            SELECT 
                favorito.id_favorito 
            FROM 
                favorito 
            JOIN 
                sitio_turistico 
            ON 
                favorito.id_sitio = sitio_turistico.id_sitio 
            WHERE 
                favorito.id_sitio = :ID_Sitio
            AND
                favorito.id_usuario = :ID_Usuario";

        $consulta = $conn->prepare($queryStr);
        $consulta->bindParam(':ID_Sitio', $ID_Sitio);
        $consulta->bindParam(':ID_Usuario', $ID_Usuario);
        $consulta->execute();

        $campos = $consulta->fetchAll(\PDO::FETCH_ASSOC);

        if (count($campos) > 0) {
            return true;
        } else {
            return false;
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

    public function eliminarComentario($idComentario, $usuarioID) {
        try {
            $sql = "DELETE FROM comentario WHERE id_comentario = :id_comentario AND id_usuario = :usuarioID";
            $stmt = $this->conexion->prepare($sql);
            $stmt->bindParam(':id_comentario', $idComentario);
            $stmt->bindParam(':usuarioID', $usuarioID);
            return $stmt->execute();
        } catch (PDOException $e) {
            return false;
        }
    }

    public function actualizarComentario($idComentario, $nuevoComentario, $usuarioID) {
        try {
            // Actualizar el comentario en la base de datos
            $stmt = $this->conexion->prepare("UPDATE comentario SET comentario = :nuevoComentario WHERE id_comentario = :idComentario AND id_usuario = :id");
            $stmt->bindParam(':nuevoComentario', $nuevoComentario);
            $stmt->bindParam(':idComentario', $idComentario);
            $stmt->bindParam(':id', $usuarioID);
    
            if ($stmt->execute()) {
                return ['success' => true];
            } else {
                return ['success' => false, 'message' => 'Error al actualizar el comentario.'];
            }
        } catch (PDOException $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }
    
    
    
    
}
?>