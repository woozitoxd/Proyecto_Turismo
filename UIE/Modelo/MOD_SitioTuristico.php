<?php

class SitioTuristico
{
    protected $nombre;
    protected $descripcion;
    protected $fecha_publicacion;
    protected $horarios;
    protected $tarifa; // Boolean: true = tiene tarifa, false = gratuito
    protected $latitud;
    protected $longitud;

    public function __construct($nombre, $descripcion, $fecha_publicacion, $horarios, $tarifa, $latitud, $longitud)
    {
        $this->nombre = $nombre;
        $this->descripcion = $descripcion;
        $this->fecha_publicacion = $fecha_publicacion;
        $this->horarios = $horarios;
        $this->tarifa = $tarifa;
        $this->latitud = $latitud;
        $this->longitud = $longitud;
    }

    // Getters
    public function getNombre()
    {
        return $this->nombre;
    }

    public function getDescripcion()
    {
        return $this->descripcion;
    }

    public function getFechaPublicacion()
    {
        return $this->fecha_publicacion;
    }

    public function getHorarios()
    {
        return $this->horarios;
    }

    public function getTarifa()
    {
        return $this->tarifa;
    }

    public function getLatitud()
    {
        return $this->latitud;
    }

    public function getLongitud()
    {
        return $this->longitud;
    }

    public function setNombre($nombre)
    {
        $this->nombre = $nombre;
    }

    public function setDescripcion($descripcion)
    {
        $this->descripcion = $descripcion;
    }

    public function setFechaPublicacion($fecha_publicacion)
    {
        $this->fecha_publicacion = $fecha_publicacion;
    }

    public function setHorarios($horarios)
    {
        $this->horarios = $horarios;
    }

    public function setTarifa($tarifa)
    {
        $this->tarifa = $tarifa;
    }

    public function setLatitud($latitud)
    {
        $this->latitud = $latitud;
    }

    public function setLongitud($longitud)
    {
        $this->longitud = $longitud;
    }
    
    public static function ObtenerSitios()
{
    if (!isset($GLOBALS['conn'])) {
        require_once 'conexion_bbdd.php';
    }
    
    /** @var \PDO $conn */
    $conn = $GLOBALS['conn'];
    $queryStr = "
        SELECT st.*, c.titulo AS titulo, i.bin_imagen 
        FROM sitio_turistico st
        JOIN categoria c ON st.id_categoria = c.id_categoria
        LEFT JOIN imagen i ON st.id_sitio = i.id_sitio"; // Cambia 'sitio_turistico' a 'sitios_turistico' si es necesario
    $consulta = $conn->prepare($queryStr);
    $consulta->execute();

    $sitios = $consulta->fetchAll(\PDO::FETCH_ASSOC);

    return $sitios;
}


    public static function obtenerCategoriaSitio($idSitio)
    {
        if (!isset($GLOBALS['conn'])) {
            require_once 'conexion_bbdd.php';
        }
        
        /** @var \PDO $conn */
        $conn = $GLOBALS['conn'];
        $queryStr = "
        SELECT 
            sitios_turistico.*, 
            categoria.nombre_categoria 
        FROM 
            sitios_turistico 
        JOIN 
            categoria 
        ON 
            sitio_turistico.id_categoria = categoria.id_categoria 
        WHERE 
            sitio_turistico.id_sitio = :idSitio";
        $consulta = $conn->prepare($queryStr);
        $consulta->bindParam(':idSitio', $idSitio);
        $consulta->execute();
        $sitio = $consulta->fetch(\PDO::FETCH_ASSOC);
        return $sitio;
    }

    public static function ObtenerSitiosPropios($ID_Usuario)
    {
        if (!isset($GLOBALS['conn'])) {
            require_once 'conexion_bbdd.php';
        }
        
        /** @var \PDO $conn */
        $conn = $GLOBALS['conn'];

        $queryStr = "
            SELECT DISTINCT sitio_turistico.*, categoria.*, imagen.*
            FROM sitio_turistico
            JOIN favorito ON favorito.id_sitio = sitio_turistico.id_sitio
            JOIN imagen ON imagen.id_sitio = sitio_turistico.id_sitio
            JOIN categoria ON categoria.id_categoria = sitio_turistico.id_categoria
            WHERE sitio_turistico.id_usuario = :ID_Usuario";

        $consulta = $conn->prepare($queryStr);
        $consulta->bindParam(':ID_Usuario', $ID_Usuario);
        $consulta->execute();

        $listaDeSitios = [];

        while ($sitio = $consulta->fetch(PDO::FETCH_ASSOC)) {
            // Convertir la imagen en base64
            if (isset($sitio['bin_imagen'])) {
                $sitio['bin_imagen'] = base64_encode($sitio['bin_imagen']);
            }
            // Añadir la publicación al array de publicaciones
            $listaDeSitios[] = $sitio;
        }

        return $listaDeSitios;
    }

    public static function ObtenerSitiosFavoritos($ID_Usuario)
    {
        if (!isset($GLOBALS['conn'])) {
            require_once 'conexion_bbdd.php';
        }
        
        /** @var \PDO $conn */
        $conn = $GLOBALS['conn'];

        $queryStr = "
            SELECT DISTINCT 
                sitio_turistico.*, categoria.*, imagen.* 
            FROM 
                sitio_turistico 
            JOIN 
                favorito 
            ON 
                favorito.id_sitio = sitio_turistico.id_sitio 
            JOIN 
                imagen 
            ON 
                imagen.id_sitio = sitio_turistico.id_sitio 
            JOIN 
                categoria 
            ON 
                categoria.id_categoria = sitio_turistico.id_categoria 
            WHERE 
                favorito.id_usuario = :ID_Usuario";

        $consulta = $conn->prepare($queryStr);
        $consulta->bindParam(':ID_Usuario', $ID_Usuario);
        $consulta->execute();

        $listaDeSitios = [];

        while ($sitio = $consulta->fetch(PDO::FETCH_ASSOC)) {
            // Convertir la imagen en base64
            if (isset($sitio['bin_imagen'])) {
                $sitio['bin_imagen'] = base64_encode($sitio['bin_imagen']);
            }
            // Añadir la publicación al array de publicaciones
            $listaDeSitios[] = $sitio;
        }

        return $listaDeSitios;
    }

    public static function VerificarSitioFavorito($ID_Sitio, $ID_Usuario){

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

    public static function GuardarFavorito($ID_Sitio, $ID_Usuario){
        if (!isset($GLOBALS['conn'])) {
            require_once 'conexion_bbdd.php';
        }
        
        /** @var \PDO $conn */
        $conn = $GLOBALS['conn'];

        $queryStr = "INSERT INTO favorito(id_sitio, id_usuario) VALUES (:ID_Sitio, :ID_Usuario)";

        $consulta = $conn->prepare($queryStr);
        $consulta->bindParam(':ID_Sitio', $ID_Sitio);
        $consulta->bindParam(':ID_Usuario', $ID_Usuario);

        if ($consulta->execute()) {
            return true;
        }else{
            return false;
        }
    }

    public static function EliminarFavorito($ID_Sitio, $ID_Usuario){

        if (!isset($GLOBALS['conn'])) {
            require_once 'conexion_bbdd.php';
        }
        
        /** @var \PDO $conn */
        $conn = $GLOBALS['conn'];

        $queryStr = "DELETE FROM favorito WHERE id_sitio = :ID_Sitio AND id_usuario = :ID_Usuario";

        $consulta = $conn->prepare($queryStr);
        $consulta->bindParam(':ID_Sitio', $ID_Sitio);
        $consulta->bindParam(':ID_Usuario', $ID_Usuario);
        
        if ($consulta->execute()) {
            return true;
        }else{
            return false;
        }
    }
}
?>