<?php

require_once("../modelo/conexion_bbdd.php");


class ModeloComentarios {
    // Suponiendo que ya tienes tu conexión a la base de datos aquí
    private $conexion;
    
    public function __construct() {
        $this->conexion = $GLOBALS['conn']; // Utiliza la conexión global existente
    }

    public function EliminarComentario($comentarioID)
    {
        try {
            $this->conexion->beginTransaction();
        
            // elimino los registros de la tabla 'reportescomentarios'
            $stmt1 = $this->conexion->prepare("DELETE FROM reporte_comentario WHERE id_comentario = :comentarioId");
            $stmt1->bindParam(':comentarioId', $comentarioID, PDO::PARAM_INT);
            $stmt1->execute();
        
            // luego, elimino los registros de la tabla 'comentarios'
            $stmt2 = $this->conexion->prepare("DELETE FROM comentario WHERE id_comentario = :comentarioId");
            $stmt2->bindParam(':comentarioId', $comentarioID, PDO::PARAM_INT);
            $stmt2->execute();
        
            // realizo un commit para guardar los cambios
            $this->conexion->commit();
        

            return true; 
        } catch (PDOException $e) {
            // Si ocurre una excepcion, deshago los cambios con rollback
            $this->conexion->rollBack();
            echo "Error: " . $e->getMessage();
            return false;
        }
    }

    public function BorrarDenuncia($idDenuncia)
    {
        try {
            $this->conexion->beginTransaction();

            $stmt1 = $this->conexion->prepare("DELETE FROM reporte_comentario WHERE id_reporte = :idDenuncia");
            $stmt1->bindParam(':idDenuncia', $idDenuncia, PDO::PARAM_INT);
            $stmt1->execute();
            
            $this->conexion->commit();

            return true;
        }catch(PDOException $e){
            $this->conexion->rollBack();
            echo "Error: " . $e->getMessage();
            return false;
        }
    }
}
