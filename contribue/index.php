<?php
session_start();
include_once('Config/config_global.php');
include_once('Commons/verification.php');
include_once('Commons/Messenger.php');


use Commons\Messenger;
use Commons\verification;

$uri = $_SERVER['REQUEST_URI'];
$url = $_SERVER['HTTP_HOST'];
$pattern_api = '/\/'.name_project.'\/api\/.*\/.*\/*/';
$pattern_app = '/\/'.name_project.'\/.*/';
$subdomain = '';
$isSubdomain = false;

if($uri === '/' && $url != DOMAIN){
    $hostParts = explode('.', $url);
    if (count($hostParts) > 2) {
        array_pop($hostParts); // TLD
        array_pop($hostParts); // Dominio

        $subdomain = implode('.', $hostParts);
        $isSubdomain = true;
    }
}

// Manejar las rutas
if ($uri === '/'.name_project.'/' || $uri === '/'.name_project.'/index.php') {
    // Mostrar el contenido de la página principal
    include 'index_content.php'; // Contenido de la página principal
} elseif ($uri === '/'.name_project.'/signup') {
    include 'View/Sign/SignUp.html';
} elseif ($uri === '/'.name_project.'/signin XXX') {
    include 'View/Sign/SignIn.html';
} elseif ($uri === '/signin' || $uri === '/' && $isSubdomain) {
    include 'View/Sign/SignIn.html';
} elseif (preg_match($pattern_api, $uri)){
    $pathSegments = explode('/', $uri);
    $validation = new verification();
    $msg = new Messenger();
    if($validation->fileExist(__DIR__."/Service/$pathSegments[3]/$pathSegments[3]_rest_api.php")){
        include('Service/master_controller_api.php');
    }else{
        $msg->notEndpoint($pathSegments[3]);
    }
}elseif (preg_match($pattern_app, $uri)){
    $pathSegments = explode('/', $uri);
    $validation = new verification();
    $msg = new Messenger();
    if($validation->fileExist(__DIR__."/View/Pages/$pathSegments[2].php")){
        if(isset($_COOKIE['jwt']) && $validation->validarToken($_COOKIE['jwt'])){
            include('main.php');
        }else{
            echo 'No tienes permisos de estar aqui';
        }

    }else{
        echo __DIR__."/View/Pages/$pathSegments[2]/$pathSegments[2].php";
    }
}else {
    // Mostrar una página de error 404
    //http_response_code(404);
    echo 'Página no encontrada uri: '.$uri.' host: '.$_SERVER['HTTP_HOST'];
}
