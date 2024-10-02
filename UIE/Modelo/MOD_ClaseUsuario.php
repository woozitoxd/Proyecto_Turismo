<?php
require_once('conexion_bbdd.php');
require_once('MOD_perfil.php');

    class Usuario  //CLASE USUARIO
    //nota a futuro: agregar los demás campos faltates para que esté congruente con la clase del diagrama de CLASES
    {
        private $id;
        private $nombre;
        private $correo;
        private $password;
        private $fechaNacimiento;
        private $conexion;

        public function __construct($id,$correo, $password, $fechaNacimiento, $nombre)
        {
            $this->id = $id;
            $this->nombre = $nombre;
            $this->correo = $correo;
            $this->password = $password;
            $this->fechaNacimiento = $fechaNacimiento;

            try {
                // Utiliza la conexión centralizada
                $this->conexion = $GLOBALS['conn'];
            } catch (PDOException $e) {
                die("Error en la conexión de base de datos: " . $e->getMessage());
            }
        }
        public function obtenerIdRolUsuario()  //Funcion para obtener el rol usuario y guardarlo en id rol, esto para almacenarlo mas tarde en la tabla usuario
        {
            $rolUsuario = "usuario";
            $sql = "SELECT id FROM rol WHERE nombre = :rolUsuario"; //selecciono el id que contenga el nombre de "usuario" esto lo hago para guardarlo
            $stmt = $this->conexion->prepare(query: $sql);  // y despues utilizarlo para registrarlo en la tabla usuario
            $stmt->bindParam(':rolUsuario', $rolUsuario);
            $stmt->execute();
            return $stmt->fetchColumn();
        }
        public function registrar()
        {
    
            $verificarNombre = new perfilUser('','','',''); //Instancio un objeto de mi clase perfil (que traje del modelo perfil.php)
            // Validar que el correo no esté en uso
            if ($this->verificarCorreoExistente($this->correo)) {
                return "El correo ya está en uso."; 
            }
    
            if($verificarNombre->verificarNombreExistente($this->nombre, $this->id)){ //Invoco al metodo para verificar si el nombre existe.
                return "El nombre ya está en uso."; //Si existe, devuelvo un mensaje de error en la pagina.
            }
            // Registro del usuario, acá inserto a mi tabla usuario los datos que se llenaron en el registro.
            $sqlUsuario = "INSERT INTO usuario(fecha_nacimiento, nombre, email, password) VALUES (:fechaNacimiento, :nombre, :correo, :password)";
            $stmtUsuario = $this->conexion->prepare($sqlUsuario); //Preparo la consulta que me inserta un usuario
    
            if (!$stmtUsuario) {
                return "Error en la consulta SQL de usuario";
            }
    
            $stmtUsuario->bindParam(':fechaNacimiento', $this->fechaNacimiento, PDO::PARAM_STR); //con esto nada mas establezco los valores que obtuve a la consulta
            $stmtUsuario->bindParam(':nombre', $this->nombre, PDO::PARAM_STR);
            $stmtUsuario->bindParam(':correo', $this->correo, PDO::PARAM_STR);
            $stmtUsuario->bindParam(':password', $this->password, PDO::PARAM_STR);
    
            try {
                $this->conexion->beginTransaction();
    
                if ($stmtUsuario->execute()) {
                    // aca obtengo el ID del usuario recién registrado
                    $idUsuario = $this->conexion->lastInsertId();
    
                    // aca obtengo el ID del rol "usuario"
                    $idRolUsuario = $this->obtenerIdRolUsuario();
    
                    // asigno automáticamente el rol "usuario" al usuario registrado
                    $sqlAsignarRol = "UPDATE usuario SET id_rol = :idRolUsuario WHERE id = :idUsuario ";
                    //UPDATE usuario SET id_rol = :idRolUsuario WHERE id = :idUsuario;
                    $stmtAsignarRol = $this->conexion->prepare($sqlAsignarRol);
                    $stmtAsignarRol->bindParam(':idUsuario', $idUsuario, PDO::PARAM_INT);
                    $stmtAsignarRol->bindParam(':idRolUsuario', $idRolUsuario, PDO::PARAM_INT);
    
                    if ($stmtAsignarRol->execute()) {
                        $this->conexion->commit();  //Confirmo los cambios en la base de datos
                        return true;
                    } else {
                        $this->conexion->rollBack();  //Si hay error, deshago los cambios que realicé en la consulta para evitar que se actualice la bbdd
                        return "Error al asignar el rol al usuario";
                    }
                } else {
                    $this->conexion->rollBack(); //Si hay error en el registro, deshago los cambios igualmente. esto para que no se guarden valores errores en la bbdd
                    return "Error al registrar el usuario";
                }
            } catch (PDOException $e) {
                $this->conexion->rollBack();
                return "Error en la transacción: " . $e->getMessage();
            }
        }

        public function registrarConGoogle()
        {
    
            $verificarNombre = new perfilUser('','','',''); //Instancio un objeto de mi clase perfil (que traje del modelo perfil.php)
            // Validar que el correo no esté en uso
            if ($this->verificarCorreoExistente($this->correo)) {
                return "El correo ya está en uso."; 
            }

            // Registro del usuario, acá inserto a mi tabla usuario los datos que se llenaron en el registro.
            $sqlUsuario = "INSERT INTO usuario(nombre, google_id, google_email) VALUES (:nombre, :googleID, :googleEMAIL)";
            $stmtUsuario = $this->conexion->prepare($sqlUsuario); //Preparo la consulta que me inserta un usuario
    
            if (!$stmtUsuario) {
                return "Error en la consulta SQL de usuario";
            }

            $stmtUsuario->bindParam(':nombre', $this->nombre, PDO::PARAM_STR);
            $stmtUsuario->bindParam(':googleID', $this->id, PDO::PARAM_STR);
            $stmtUsuario->bindParam(':googleEMAIL', $this->correo, PDO::PARAM_STR);
    
            try {
                $this->conexion->beginTransaction();
    
                if ($stmtUsuario->execute()) {
                    // aca obtengo el ID del usuario recién registrado
                    $idUsuario = $this->conexion->lastInsertId();
    
                    // aca obtengo el ID del rol "usuario"
                    $idRolUsuario = $this->obtenerIdRolUsuario();
    
                    // asigno automáticamente el rol "usuario" al usuario registrado
                    $sqlAsignarRol = "UPDATE usuario SET id_rol = :idRolUsuario WHERE id = :idUsuario ";
                    //UPDATE usuario SET id_rol = :idRolUsuario WHERE id = :idUsuario;
                    $stmtAsignarRol = $this->conexion->prepare($sqlAsignarRol);
                    $stmtAsignarRol->bindParam(':idUsuario', $idUsuario, PDO::PARAM_INT);
                    $stmtAsignarRol->bindParam(':idRolUsuario', $idRolUsuario, PDO::PARAM_INT);
    
                    if ($stmtAsignarRol->execute()) {
                        $this->conexion->commit();  //Confirmo los cambios en la base de datos
                        return true;
                    } else {
                        $this->conexion->rollBack();  //Si hay error, deshago los cambios que realicé en la consulta para evitar que se actualice la bbdd
                        return "Error al asignar el rol al usuario";
                    }
                } else {
                    $this->conexion->rollBack(); //Si hay error en el registro, deshago los cambios igualmente. esto para que no se guarden valores errores en la bbdd
                    return "Error al registrar el usuario";
                }
            } catch (PDOException $e) {
                $this->conexion->rollBack();
                return "Error en la transacción: " . $e->getMessage();
            }
        }

        public function verificarCorreoExistente($correo)  //nomas verifico que el correo existe o no en mi base
        {
            $sql = "SELECT COUNT(*) as total FROM usuario WHERE email = :correo"; //Cuento el numero de registros donde la columna email sea igual a correo
            $stmt = $this->conexion->prepare($sql);

            if ($stmt) {
                $stmt->bindParam(':correo', $correo, PDO::PARAM_STR);
                $stmt->execute();            
                $total = $stmt->fetchColumn(); //Con fetchColumn (metodo de PDO) verifico si ya existe el correo en la tabla

                return $total > 0; //devuelve true si el num total de filas es mayor que 0, quiere decir que ya existe el correo en la tabla. Si no, devuelve false
            } else {
                return false;
            }
        }
        public function validaRequerido($nombre, $correo, $fecha_nacimiento) //esta consulta me verifica que la edad minima aceptada para registrarse es 16 años
        {

            $edadMinima = 16;   //Minimo 16 años para registrarse
            $edadMaxima = 100;  //Maximo 100 años para registrarse
            $fechaActual = new DateTime();
            $fechaNacimiento = new DateTime($fecha_nacimiento);
            $diferencia = $fechaNacimiento->diff($fechaActual);
            $edad = $diferencia->y;

            if ($edad < $edadMinima || $edad > $edadMaxima) {
                return 'Debes ser mayor de 16 años y menor de 100 años para registrarte.';
            }

            // valido que el campo no este vacio
            if (trim($nombre) === '') {
                return 'El campo nombre no puede estar vacío.';
            }

            // divido la cadena del input por cada espacio que encuentre, y lo guardo en la variable palabras
            $palabras = explode(' ', $nombre);

            // Valido que palabras sea distinto de 2, es decir, que no exista mas ni menos de 2 palabras
            if (count($palabras) != 1) {
                return 'El campo nombre debe contener al menos dos palabras. ';
            }

            foreach ($palabras as $palabra) {  //Valido el campo nombre para que las palabras no tengan numeros ni caracteres fuera del abecedario
                if (!preg_match('/^[a-zA-ZáéíóúüÁÉÍÓÚÜ]+$/', $palabra)) {
                    return 'Debe contener solo letras y tildes.';
                }

                $longitud = mb_strlen($nombre, 'UTF-8');
                if ($longitud < 4 || $longitud > 60) {  //Valido el rango de caracteres de las palabras, uso entre 4 y 60, es decir, el nombre debe tener almenos 4 caracteres
                    return 'Cada palabra debe tener entre 4 y 60 caracteres.';
                }
            }
            // Todas las validaciones pasaron
            return true; //Retorno verdadero porque el formato del campo nombre cumple las validaciones
        }
    }
?>