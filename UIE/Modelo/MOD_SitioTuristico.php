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
        $queryStr = "CALL SP_TraerSitiosTuristicos()";
        $consulta = $conn->prepare($queryStr);
        $consulta->execute();

        $sitios = $consulta->fetchAll(\PDO::FETCH_ASSOC);

        return $sitios;
    }

    public static function ObtenerSitiosAprobar()
    {
        if (!isset($GLOBALS['conn'])) {
            require_once 'conexion_bbdd.php';
        }

        /** @var \PDO $conn */
        $conn = $GLOBALS['conn'];
        $queryStr = "CALL SP_TraerSitiosParaAprobar()";
        $consulta = $conn->prepare($queryStr);
        $consulta->execute();

        $sitios = $consulta->fetchAll(\PDO::FETCH_ASSOC);

        return $sitios;
    }
    public static function AprobarSitioTuristico($id_sitio)
    {
        if (!isset($GLOBALS['conn'])) {
            require_once 'conexion_bbdd.php';
        }

        /** @var \PDO $conn */
        $conn = $GLOBALS['conn'];
        $queryStr = "CALL SP_ActualizarEstadoSitioTuristico(:id_sitio,:estadositio)";
        $consulta = $conn->prepare($queryStr);
        $consulta->bindParam(':id_sitio', $id_sitio, PDO::PARAM_INT);
        $estado = 1;  // Aprobado, establecer el estado a 1
        $consulta->bindParam(':estadositio', $estado, \PDO::PARAM_INT);
        try {
            $consulta->execute();
            return true;  // Indica éxito si el procedimiento se ejecuta correctamente
        } catch (Exception $e) {
            error_log("Error al aprobar sitio turístico: " . $e->getMessage());
            return false;
        }
    }
    public static function RechazarSitioTuristico($id_sitio)
    {
        if (!isset($GLOBALS['conn'])) {
            require_once 'conexion_bbdd.php';
        }

        /** @var \PDO $conn */
        $conn = $GLOBALS['conn'];
        $queryStr = "CALL SP_ActualizarEstadoSitioTuristico(:id_sitio,:estadositio)";
        $consulta = $conn->prepare($queryStr);
        $consulta->bindParam(':id_sitio', $id_sitio, PDO::PARAM_INT);
        $estado = 2;  // Rechazado, establecer el estado a 2
        $consulta->bindParam(':estadositio', $estado, \PDO::PARAM_INT);
        try {
            $consulta->execute();
            return true;  // Indica éxito si el procedimiento se ejecuta correctamente
        } catch (Exception $e) {
            error_log("Error al aprobar sitio turístico: " . $e->getMessage());
            return false;
        }
    }
    public static function obtenerTodasLasCategorias()
    {
        if (!isset($GLOBALS['conn'])) {
            require_once 'conexion_bbdd.php';
        }

        /** @var \PDO $conn */
        $conn = $GLOBALS['conn'];
        $queryStr = "CALL SP_ObtenerTodasLasCategorias()";
        $consulta = $conn->prepare($queryStr);
        $consulta->execute();
        $categorias = $consulta->fetchAll(\PDO::FETCH_ASSOC);
        return $categorias;
    }
    public static function ContarSitiosPendientesAprobar()
    {
        if (!isset($GLOBALS['conn'])) {
            require_once 'conexion_bbdd.php';
        }

        /** @var \PDO $conn */
        $conn = $GLOBALS['conn'];
        $queryStr = "CALL SP_ContarSitiosPendientesAprobacion(@cantidad)";

        // Ejecutar el procedimiento
        $consulta = $conn->prepare($queryStr);
        $consulta->execute();

        // Obtener el valor OUT usando una consulta adicional
        $resultado = $conn->query("SELECT @cantidad AS cantidad")->fetch(PDO::FETCH_ASSOC);

        return $resultado['cantidad'];
    }


    public static function obtenerTodasLasLocalidades()
    {
        if (!isset($GLOBALS['conn'])) {
            require_once 'conexion_bbdd.php';
        }

        /** @var \PDO $conn */
        $conn = $GLOBALS['conn'];

        // Consulta para obtener todas las localidades
        $queryStr = "CALL SP_ObtenerTodasLasLocalidades()";
        $consulta = $conn->prepare($queryStr);
        $consulta->execute();

        // Obtenemos todas las localidades
        $localidades = $consulta->fetchAll(\PDO::FETCH_ASSOC);

        return $localidades;
    }
    public static function obtenerTodasLasEtiquetas()
    {
        if (!isset($GLOBALS['conn'])) {
            require_once 'conexion_bbdd.php';
        }

        /** @var \PDO $conn */
        $conn = $GLOBALS['conn'];

        // Consulta para obtener todas las localidades
        $queryStr = "CALL SP_ObtenerTodasLasEtiquetas()";
        $consulta = $conn->prepare($queryStr);
        $consulta->execute();

        // Obtenemos todas las localidades
        $etiquetas = $consulta->fetchAll(\PDO::FETCH_ASSOC);

        return $etiquetas;
    }
    
    public static function ObtenerSitiosPropios($ID_Usuario)
    {
        if (!isset($GLOBALS['conn'])) {
            require_once 'conexion_bbdd.php';
        }

        /** @var \PDO $conn */
        $conn = $GLOBALS['conn'];

        $queryStr = "
            SELECT st.*, c.titulo AS titulo_categoria, img.imagen AS bin_imagen, et.etiquetas, l.nombre AS localidad 
            FROM sitio_turistico st 
            JOIN categoria c 
            ON st.id_categoria = c.id_categoria 
            LEFT JOIN ( SELECT id_sitio, MIN(bin_imagen) AS imagen FROM imagen GROUP BY id_sitio ) AS img 
            ON st.id_sitio = img.id_sitio 
            LEFT JOIN ( SELECT se.id_sitio, GROUP_CONCAT(e.titulo SEPARATOR ', ') AS etiquetas 
            FROM sitio_etiqueta se 
            JOIN etiqueta e 
            ON se.id_etiqueta = e.id_etiqueta GROUP BY se.id_sitio ) AS et 
            ON st.id_sitio = et.id_sitio 
            LEFT JOIN localidad l 
            ON st.id_localidad = l.id_localidad 
            WHERE st.id_usuario = :ID_Usuario";

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
            SELECT st.*, c.titulo AS titulo_categoria, img.imagen AS bin_imagen, et.etiquetas, l.nombre AS localidad, favorito.id_favorito 
            FROM sitio_turistico st 
            JOIN categoria c 
            ON st.id_categoria = c.id_categoria 
                LEFT JOIN ( SELECT id_sitio, MIN(bin_imagen) AS imagen 
                FROM imagen 
                GROUP BY id_sitio ) AS img 
            ON st.id_sitio = img.id_sitio 
            LEFT JOIN ( SELECT se.id_sitio, GROUP_CONCAT(e.titulo SEPARATOR ', ') AS etiquetas 
            FROM sitio_etiqueta se 
            JOIN etiqueta e 
            ON se.id_etiqueta = e.id_etiqueta 
            GROUP BY se.id_sitio ) AS et 
            ON st.id_sitio = et.id_sitio 
            LEFT JOIN localidad l 
            ON st.id_localidad = l.id_localidad 
            JOIN favorito 
            ON favorito.id_sitio = st.id_sitio 
            WHERE favorito.id_usuario = :ID_Usuario";

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

    public static function ObtenerValoracionPromedioSitio($ID_Sitio)
    {

        if (!isset($GLOBALS['conn'])) {
            require_once 'conexion_bbdd.php';
        }

        /** @var \PDO $conn */
        $conn = $GLOBALS['conn'];
        $queryStr = "
            SELECT 
            COUNT(valoracion) AS cant_valoraciones, 
            AVG(valoracion) AS valoracion_promedio 
            FROM comentario WHERE id_sitio = :ID_Sitio";

        $consulta = $conn->prepare($queryStr);
        $consulta->bindParam(':ID_Sitio', $ID_Sitio);
        $consulta->execute();

        $valoracion = $consulta->fetch(PDO::FETCH_ASSOC);

        return $valoracion;

    }

    public static function VerificarSitioFavorito($ID_Sitio, $ID_Usuario)
    {

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

    public static function GuardarFavorito($ID_Sitio, $ID_Usuario)
    {
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
        } else {
            return false;
        }
    }

    public static function EliminarFavorito($ID_Sitio, $ID_Usuario)
    {

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
        } else {
            return false;
        }
    }

    
    public function PublicarSitio( $categoriaID, $localidadID, $usuarioID, $nombre, $descripcion, $fechaPublicacion,
        $arancelamiento, $latitud, $longitud, $estado, $horarios, $etiquetas, $imagenes) {
        if (!isset($GLOBALS['conn'])) {
            require_once 'conexion_bbdd.php';
        }

        try {
        // Convertir arrays a JSON para pasarlos al SP
        //$jsonEtiquetas = json_encode($etiquetas);
        // Convertir arrays a JSON para pasarlos al SP
        $jsonEtiquetas = json_encode(array_map(function($etiqueta) {
            return $etiqueta['id_etiqueta']; // Solo incluir el campo id_etiqueta
        }, $etiquetas));

        $jsonImagenes = json_encode(array_map(function($img) {
            return base64_encode($img['data']); // Codificar las imágenes en base64
        }, $imagenes));

        /** @var \PDO $conn */
        $conn = $GLOBALS['conn'];
        
        $SPCrearSitio = $conn->prepare("CALL InsertarSitioCompleto(
            :id_usuario, :id_categoria, :id_localidad, :nombre, :descripcion, :fecha_publicacion,:horarios, :arancelamiento, 
            :latitud, :longitud, :estado, :etiquetas, :imagenes)");
        
        $SPCrearSitio->bindParam(':nombre', $nombre, PDO::PARAM_STR);
        $SPCrearSitio->bindParam(':descripcion', $descripcion, PDO::PARAM_STR);
        $SPCrearSitio->bindParam(':fecha_publicacion', $fechaPublicacion, PDO::PARAM_STR);
        $SPCrearSitio->bindParam(':horarios', $horarios, PDO::PARAM_STR);
        $SPCrearSitio->bindParam(':arancelamiento', $arancelamiento, PDO::PARAM_INT);
        $SPCrearSitio->bindParam(':latitud', $latitud, PDO::PARAM_STR);
        $SPCrearSitio->bindParam(':longitud', $longitud, PDO::PARAM_STR);
        $SPCrearSitio->bindParam(':estado', $estado, PDO::PARAM_INT);
        $SPCrearSitio->bindParam(':id_usuario', $usuarioID, PDO::PARAM_INT);
        $SPCrearSitio->bindParam(':id_categoria', $categoriaID, PDO::PARAM_INT);
        $SPCrearSitio->bindParam(':id_localidad', $localidadID, PDO::PARAM_INT);
        $SPCrearSitio->bindParam(':etiquetas', $jsonEtiquetas, PDO::PARAM_STR);
        $SPCrearSitio->bindParam(':imagenes', $jsonImagenes, PDO::PARAM_STR);
        
        //print_r( $categoriaID);

            $SPCrearSitio->execute();
            return true;  // Indica éxito si el procedimiento se ejecuta correctamente
        } catch (Exception $e) {
//            error_log("Error al aprobar sitio turístico: " . $e->getMessage());
            return $e->getMessage();
        }
    }
    public static function EditarSitio($id_sitio, $nombre_sitio,$descripcion, $categoria) {
        if (!isset($GLOBALS['conn'])) {
            require_once 'conexion_bbdd.php';
        }
    
        try {
            // Registro para depuración: mostrar los datos procesados
            error_log("ID del Sitio: $id_sitio");
            error_log("Nombre: $nombre_sitio");
            error_log("Descripcion: $descripcion");
            error_log("Categoria: $categoria");
    
            /** @var \PDO $conn */
            $conn = $GLOBALS['conn'];
    
            // Preparar la llamada al procedimiento de edición
            $SPEditarSitio = $conn->prepare(query: "CALL EditarSitioTuristico(
                :id_sitio, :nombre, :descripcion, :categoria)");
    
            // Asignar los parámetros para la llamada al SP
            $SPEditarSitio->bindParam(':id_sitio', $id_sitio, PDO::PARAM_INT);
            $SPEditarSitio->bindParam(':nombre', $nombre_sitio, PDO::PARAM_STR);
            $SPEditarSitio->bindParam(':descripcion', $descripcion, PDO::PARAM_STR);
            $SPEditarSitio->bindParam(':categoria', $categoria, PDO::PARAM_STR);

            // Ejecutar el SP
            $SPEditarSitio->execute();
            error_log("El procedimiento almacenado se ejecutó correctamente."); // Log de éxito
            return true;  // Indica éxito si el procedimiento se ejecuta correctamente
        } catch (Exception $e) {
            // Error en la ejecución
            error_log("Error al ejecutar el procedimiento almacenado: " . $e->getMessage());
            return $e->getMessage();
        }
    }
    
    
    

}
?>