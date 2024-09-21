<?php
//consulta para conectarme a mi base de datos
function conectar_db()
{
    $servername = "127.0.0.1"; //establezco los campos del servidor, el usuario, y demás, no cambiar. son lo que trabaja con xampp
    $username = "root";
    $password = "";
    $port = 3306;
    $db = "basededatos_turismo_ps";  //nombre de nuestra base

    try {
        $newConnection = new \PDO(dsn: "mysql:host=$servername;port=$port;dbname=" . $db . ";charset=utf8", username: $username, password: $password);
        // set the PDO error mode to exception
        $newConnection->setAttribute(attribute: \PDO::ATTR_ERRMODE, value: \PDO::ERRMODE_EXCEPTION);
        // echo "Connected successfully";
    } catch (Exception $e) {
        echo "Connection failed: " . $e->getMessage();
        die(); // exit;
    }
    return $newConnection;
}

if (!isset($GLOBALS['conn'])) {
    $GLOBALS['conn'] = conectar_db();  //Puedo conectarme a la base de datos usando un unico archivo
}
