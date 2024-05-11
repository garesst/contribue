<?php
namespace Commons;
include_once('Commons/JWT.php');
use Firebase\JWT\JWT;

class verification
{

    private $clave_secreta = 'Al3x4ndr@0$t3n';
    function version_validation($versionRequest,$versionApi,$verificar): void
    {
        if($verificar){
            if($versionRequest!=$versionApi){
                header('Content-Type: text/html; charset=utf-8');
                http_response_code(400);
                echo json_encode(array("error" => "Versión de API incorrecta"));
                exit();
            }
        }
    }

    function fileExist($archivo): bool
    {
        if (file_exists($archivo)) {
            return true;
        } else {
            return false;
        }
    }
    function pwsCreate($password): string
    {
        return hash('sha512', $password);
    }
    function pswVerify($password, $hashedPassword): bool
    {
        return ($this->pwsCreate($password) === $hashedPassword);
    }

    function generarToken($usuario): string
    {

        $tiempo_expiracion = time() + (8 * 3600);

        $token = array(
            "iss" => URL_PROJECT, // Emisor del token
            "aud" => URL_PROJECT, // Audiencia del token
            "iat" => time(), // Tiempo de emisión del token
            "exp" => $tiempo_expiracion, // Tiempo de expiración del token
            "usuario" => $usuario // Datos del usuario que deseas incluir en el token
        );

        $jwt = JWT::encode($token, $this->clave_secreta, 'HS256');
        return $jwt;
    }

    function validarToken($token): bool
    {

        try {
            $datos_usuario = JWT::decode($token, $this->clave_secreta, array('HS256'));
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

}