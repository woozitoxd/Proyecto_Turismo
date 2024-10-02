<?php
require_once '../../vendor/autoload.php'; // Autoload de Composer
require_once '../Modelo/MOD_ClaseUsuario.php';
require_once '../Modelo/MOD_perfil.php';

//session_start();

// Obtener el esquema (http o https)
$scheme = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ? "https" : "http";

// Obtener el host (dominio)
$host = $_SERVER['HTTP_HOST'];

// Obtener la ruta actual
$requestUri = $_SERVER['REQUEST_URI'];

// Construir la URL completa
$currentUrl = $scheme . "://" . $host . $requestUri;

// Encontrar la posición de 'index.php' en la URL
$indexPosition = strpos($currentUrl, 'index.php');

// Verificar si 'index.php' está presente y recortar la URL
if ($indexPosition !== false) {
    $urlVariable = substr($currentUrl, 0, $indexPosition + strlen('index.php'));
} else {
    // Si 'index.php' no está en la URL, usar la URL completa
    $urlVariable = $currentUrl;
}

// Configurar las credenciales de Google
$clientID = '49729066974-97qa5fd317ka00n89ue41hr8843at580.apps.googleusercontent.com';
$clientSecret = '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';
//$redirectUri = '';

$client = new Google_Client();
$client->setClientId($clientID);
$client->setClientSecret($clientSecret);
$client->setRedirectUri($urlVariable);
$client->addScope("email");
$client->addScope("profile");

// Manejar el código de autenticación de Google
if (isset($_GET['code'])) {

    $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
    $_SESSION['access_token'] = $token;
    
    // Obtener información del usuario
    $client->setAccessToken($token);
    $oauth2 = new Google_Service_Oauth2($client);
    $userInfo = $oauth2->userinfo->get();

    // Verificar si el usuario ya está registrado en la base de datos

    $usuario = new perfilUser("", "", "", "");

    $resultado = $usuario->consultarGoogleAuth($userInfo['id'], $userInfo['email']);

    
    if ($resultado == null){
        $usuarioNuevo = new Usuario($userInfo['id'], $userInfo['email'], "", "", $userInfo['name']);
        $usuarioNuevo->registrarConGoogle();
    }

    $_SESSION['id'] = $resultado['id'];
    $_SESSION['usuario'] = $userInfo['email'];
    $_SESSION['nombre'] = $userInfo['name'];
    $_SESSION['usuario_foto'] = $userInfo['picture'];
    
    header('Location: ../Vistas/index.php'); //vuelvo al index
    exit;
}

// Establecer el token de sesión si ya existe
if (isset($_SESSION['access_token']) && $_SESSION['access_token']) {
    $client->setAccessToken($_SESSION['access_token']);
} else {
    $authUrl = $client->createAuthUrl(); // URL para iniciar sesión con Google
}

?>