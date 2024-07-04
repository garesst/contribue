<?php

namespace Service;

global $uri;
$pathSegments = explode('/', $uri);
include("Service/$pathSegments[3]/$pathSegments[3]_rest_api.php");

use Commons\Messenger;
use ReflectionClass;

$masterApi = new master_controller_api();

$masterApi->llamarAPI($pathSegments[3],$masterApi->verMetodo(),$pathSegments[4],$pathSegments[5]);
class master_controller_api
{

    function verMetodo(){
        return $_SERVER['REQUEST_METHOD'];
    }

    function llamarAPI($endpoint,$metodo,$vFuncion,$funcion)
    {
        $msg = new Messenger();
        $nombreClase = 'Service\\'.$endpoint.'\\'.$endpoint.'_rest_api';

        if (class_exists($nombreClase)) {
            $reflection = new ReflectionClass($nombreClase);

            if ($reflection->isInstantiable()) {
                if($metodo==='POST'){
                    global $requestPost;
                    $json = file_get_contents('php://input');
                    $requestPost = json_decode($json, true);
                }
                $objeto = $reflection->newInstance($vFuncion,version_api_verification);

                $nombreMetodo = $metodo.'_'.$funcion;
                if ($reflection->hasMethod($nombreMetodo)) {
                    $objeto->$nombreMetodo();
                } else {
                    $msg->notFunction($funcion);
                }
            } else {
                $msg->notEndpoint($endpoint);
            }
        } else {
            echo $nombreClase;
            echo $nombreClase;
            $msg->notEndpoint($endpoint);
        }
    }

}