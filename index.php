<?php
include_once('Config/config_global.php');
include_once('Commons/verification.php');
include_once('Commons/Messenger.php');


use Commons\Messenger;
use Commons\verification;

$uri = $_SERVER['REQUEST_URI'];
$pattern_api = '/\/'.name_project.'\/api\/.*\/.*\/*/';

// Manejar las rutas
if ($uri === '/'.name_project.'/' || $uri === '/'.name_project.'/index.php') {
    // Mostrar el contenido de la página principal
    include 'index_content.php'; // Contenido de la página principal
} elseif ($uri === '/'.name_project.'/main') {
    include 'main.php';
} elseif ($uri === '/'.name_project.'/signup') {
    include 'View/Sign/SignUp.html';
} elseif ($uri === '/'.name_project.'/signin') {
    include 'View/Sign/SignIn.html';
} elseif (preg_match($pattern_api, $uri)){
    $pathSegments = explode('/', $uri);
    $validation = new verification();
    $msg = new Messenger();
    if($validation->fileExist(__DIR__."/Service/$pathSegments[3]/$pathSegments[3]_rest_api.php")){
        include('Service/master_controller_api.php');
    }elseif($validation->fileExist(__DIR__."/View/Pages/$pathSegments[2]/$pathSegments[2].php")){
        include('main.php');
    }else{
        $msg->notEndpoint($pathSegments[3]);
    }
}else {
    // Mostrar una página de error 404
    //http_response_code(404);
    echo 'Página no encontrada'.$uri;
}
