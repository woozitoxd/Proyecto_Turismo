<?php
require_once("../modelo/conexion_bbdd.php");
class Denuncias {
    private $conexion;

    public function __construct() {
        $this->conexion = $GLOBALS['conn']; // Utiliza la conexión global existente
    }
    public function obtenerDenuncias()
    {
        try {
            $sql = "CALL SP_ObtenerDenunciasComentarios()";
            $stmt = $this->conexion->prepare($sql);
            $stmt->execute();
            
            // Recuperar todas las filas
            $denuncias = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $denuncias;
        } catch (PDOException $e) {
            return "Error de base de datos: " . $e->getMessage();
        }
    }
}
?>
