<?php

// namespace clases;

require_once '../Modelo/conexion_bbdd.php';

class Permisos
{

    public static function tienePermiso($permiso, $idUsuario)
    {
        if (is_null($permiso) || is_null($idUsuario)) {
            return false;
        }
        if (!is_array($permiso)) {
            $permisos = [$permiso];
        } else {
            $permisos = $permiso;
        }
        return self::tieneAlgunPermiso($permisos, $idUsuario);
    }

    public static function tieneAlgunPermiso($permisos, $idUsuario)
    {
        /** @var \PDO $conn */
        global $conn;
        
        if (is_null($permisos) || !is_array($permisos) || empty($permisos) || is_null($idUsuario)) {
            return false;
        }
        
        // Obtener el id_rol del usuario
        $sqlRol = "SELECT id_rol FROM usuario WHERE id = :idUsuario";
        $stmtRol = $conn->prepare($sqlRol);
        $stmtRol->bindValue(':idUsuario', $idUsuario);
        $stmtRol->execute();
        $usuario = $stmtRol->fetch(\PDO::FETCH_ASSOC);

        if (!$usuario) {
            return false;
        }

        $idRol = $usuario['id_rol'];

        // Preparar los permisos
        $bindPermisos = implode(',', array_map(function ($p, $k) {
            return ":permiso$k";
        }, $permisos, array_keys($permisos)));

        // Consulta para verificar si el rol del usuario tiene alguno de los permisos
        $sql = "
            SELECT 
                1 
            FROM 
                permiso
            INNER JOIN
                rol_permiso
                    ON
                        rol_permiso.id_permiso = permiso.id
            WHERE 
                rol_permiso.id_rol = :idRol 
                AND permiso.nombre IN (" . $bindPermisos . ")
            LIMIT 1;
        ";

        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':idRol', $idRol);
        array_walk($permisos, function ($p, $k) use ($stmt) {
            $stmt->bindValue(":permiso$k", $p);
        });
        $stmt->execute();
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);

        return !empty($result);
    }

    public static function esRol($rol, $idUsuario)
    {
        if (is_null($rol) || is_null($idUsuario)) {
            return false;
        }
        if (!is_array($rol)) {
            $roles = [$rol];
        } else {
            $roles = $rol;
        }
        return self::esAlgunRol($roles, $idUsuario);
    }

    public static function esAlgunRol($roles, $idUsuario)
    {
        /** @var \PDO $conn */
        global $conn;

        if (is_null($roles) || !is_array($roles) || empty($roles) || is_null($idUsuario)) {
            return false;
        }

        // Obtener el id_rol del usuario
        $sqlRol = "SELECT id_rol FROM usuario WHERE id = :idUsuario";
        $stmtRol = $conn->prepare($sqlRol);
        $stmtRol->bindValue(':idUsuario', $idUsuario);
        $stmtRol->execute();
        $usuario = $stmtRol->fetch(\PDO::FETCH_ASSOC);

        if (!$usuario) {
            return false;
        }

        $idRol = $usuario['id_rol'];

        // Preparar los roles
        $bindRoles = implode(',', array_map(function ($p, $k) {
            return ":rol$k";
        }, $roles, array_keys($roles)));

        // Consulta para verificar si el rol del usuario coincide
        $sql = "
            SELECT 
                1 
            FROM 
                rol
            WHERE 
                rol.id = :idRol
                AND rol.nombre IN (" . $bindRoles . ")
            LIMIT 1;
        ";

        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':idRol', $idRol);
        array_walk($roles, function ($p, $k) use ($stmt) {
            $stmt->bindValue(":rol$k", $p);
        });
        $stmt->execute();
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);

        return !empty($result);
    }
}
