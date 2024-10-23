<?php

require '../../vendor/autoload.php';

//consulta para conectarme a mi base de datos
function conectar_db()
{

    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../');
    $dotenv->load();

    $servername = $_ENV['servername'];
    $username = $_ENV['username'];
    $password = $_ENV['password'];
    $port = $_ENV['port'];
    $db = $_ENV['db'];

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
