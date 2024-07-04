<?php

namespace Service\users;
include_once('Model/user.php');
include_once('Commons/DB.php');
include_once('Dto/UserDataDTO.php');
include_once('Dto/ResponseDTO.php');
include_once('Dto/LoginDataDTO.php');
include_once ('Controller/email_controller.php');

use Commons\DB;
use Commons\Messenger;
use Commons\verification;
use contribue\Controller\email_controller;
use Dto\LoginDataDTO;
use Dto\ResponseDTO;
use Dto\UserDataDTO;
use Exception;
use Model\user;
use PDO;

class users_rest_api
{
    private string $version = '0.0.1';
    private DB $db;

    public function __construct($version, $verificar)
    {
        $verication = new verification();
        $verication->version_validation($version, $this->version, $verificar);
    }

    public function GET_prueba()
    {
        $newUser = new user('', '', 'Hola mundo', '', '', '');
        http_response_code(200);
        echo $newUser->toJson();
        exit();
    }

    private function buscarUsuario($email){
        $this->db = new DB();
        try {
            $this->db->connect();
            $sql = "SELECT count(*) FROM users WHERE email_user = :email_user";
            $params = [':email_user' => $email];
            $countUser = $this->db->query($sql, $params);
            return $countUser->fetchColumn();
        }catch (Exception $e){
            throw new Exception("No se puede validar el correo");
        } finally {
            $this->db->disconnect();
        }
    }

    private function buscarTenant($workspace){
        $this->db = new DB();
        try {
            $this->db->connect();
            $sql = "SELECT count(*) FROM tenant WHERE workspace = :workspace";
            $params = [':workspace' => $workspace];
            $countUser = $this->db->query($sql, $params);
            return $countUser->fetchColumn();
        }catch (Exception $e){
            throw new Exception("No se puede validar el workspace");
        } finally {
            $this->db->disconnect();
        }
    }

    private function buscarSessionActiva($user_id){
        $this->db = new DB();
        try {
            $this->db->connect();
            $sql = "SELECT count(*) FROM users_validation WHERE user_id = :user_id AND status_validation='ACT'";
            $params = [':user_id' => $user_id];
            $countUser = $this->db->query($sql, $params);
            return $countUser->fetchColumn();
        }catch (Exception $e){
            throw new Exception("No se puede validar el correo");
        } finally {
            $this->db->disconnect();
        }
    }

    private function createUserValidation($user_id,$type,$status,$token=NULL){
        $dataVal = array('user_id' => $user_id, 'status_validation' => $status,
            'type_validation' => $type, 'token' => $token);
        return $this->db->insertReturn('users_validation', $dataVal,'id');
    }

    public function POST_login()
    {
        $this->db = new DB();
        global $requestPost;
        $loginDto = new LoginDataDTO($requestPost);
        $loginDto->isValid();
        $response = null;
        $verification = new verification();
        $msg = new Messenger();
        try {
            if($this->buscarUsuario($loginDto->email_user) == 0){
                $response = new ResponseDTO(false,
                    "El correo electronico no se encuentra registrado",null,'MSG',
                    $msg->msgHtmlWithIcon('Correo electronico no registrado','Asegurate que sea tu correo electronico sino puedes registrarte','warning'));
            }else{
                $this->db->connect();
                $sqlPws = "select password from authentication where user_id=(SELECT user_id FROM users WHERE email_user=:email_user) and active_authentication=true;";
                $paramsPws = [':email_user' => $loginDto->email_user];
                $pwsUser = $this->db->query($sqlPws, $paramsPws);

                $validPws = $verification->pswVerify($loginDto->password,$pwsUser->fetchColumn());
                if(!$validPws){
                    $response = new ResponseDTO(false,
                        "Credenciales no válidas",null,'MSG',
                        $msg->msgHtmlWithIcon('Credenciales no válidas','Asegurate que sea tu contraseña sea correcta','warning'));
                }else{
                    $user = $this->getUserByEmail($loginDto->email_user);
                    if($this->buscarSessionActiva($user->user_id) == 0){
                        $this->db->connect();
                        $tokenNew=$verification->generarToken($user);
                        $this->createUserValidation($user->user_id,'LOGIN','ACT',$tokenNew);
                        $response = new ResponseDTO(true, "Usuario login ok",($user),'MSG',
                            "guardarTkUsr('$tokenNew', data.data);".'formOrigin.reset();'.'window.location.replace("/'.name_project.'/main")');
                    }else{
                        $response = new ResponseDTO(true, "Usuario login no ok",null,'MSG',
                            $msg->msgWithAction('Advertencia','Ya hay una session abierta','warning', 'Permanecer aqui?','Salir','console.log("proceso cancelado")','cerrarTodasSesiones("'.$user->user_id.'")'));
                    }
                }
            }

        }catch (Exception $e){
            $response = new ResponseDTO(false, "Ocurrio un error en el proceso vuelve a intentar",null,'MSG',
                $msg->msgHtmlWithIcon('Aviso','En este momento presentamos inconvenientes, vuelve a intentar','warning'));
        }
        $this->db->disconnect();
        header('Content-Type: application/json');
        echo json_encode($response);
    }

    public function POST_register()
    {
        global $requestPost;
        $userDto = new UserDataDTO($requestPost);
        $userDto->isValid();
        $response = null;
        $msg = new Messenger();
        try {

            if($this->buscarTenant($userDto->workspace_user) == 1){
                $response = new ResponseDTO(false,
                    "El workspace ya se encuentra registrado",null,'MSG',
                    $msg->msgHtmlWithIcon('workspace ya registrado','Intenta con otro nombre de workspace, recuerda cada uno es unico.','warning'));
            }else{
                if ($this->buscarUsuario($userDto->email_user) == 1) {
                    $response = new ResponseDTO(false,
                        "El correo electronico ya se encuentra registrado",null,'MSG',
                        $msg->msgHtmlWithIcon('Correo electronico ya registrado','Intenta recuperar tu contraseña o registrate con otro correo electronico','warning'));
                } else {
                    $this->db = new DB();
                    $this->db->connect();
                    $dataTenant = array('estado' => 'AGE','workspace' => $userDto->workspace_user);
                    $tenant_id = $this->db->insertReturn('tenant', $dataTenant, 'id_tenant');
                    $dataUser = array('name_user' => $userDto->name_user, 'email_user' => $userDto->email_user,
                        'cod_tel_user' => $userDto->cod_tel_user, 'tel_user' => $userDto->tel_user,
                        'country_user' => $userDto->country_user, 'lastname_user' => $userDto->lastname_user,
                        'tel_ful_user' => $userDto->tel_ful_user, 'active_user' => true, 'status_user' => 'AGE',
                        'tenant' => $tenant_id);
                    $user_id = $this->db->insertReturn('users', $dataUser, 'user_id');
                    $verification = new verification();
                    $dataAuth = array('user_id' => $user_id, 'active_authentication' => 1,
                        'password' => $verification->pwsCreate($userDto->password));
                    $auth_id = $this->db->insert('authentication', $dataAuth);
                    $val_id = $this->createUserValidation($user_id,'REGISTER','ACT');
                    $emailControl = new email_controller();
                    $emailControl->send_email_createUser($userDto->email_user,$userDto->name_user,$userDto->lastname_user,$userDto->workspace_user);
                    $response = new ResponseDTO(true, "Usuario correctamente registrado",null,'MSG',
                        'formOrigin.reset();'.$msg->msgHtmlWithIcon('Usuario registrado','Usuario correctamente creado, recibiras un email para la validación del correo electrónico, tendras 30 dias para valdiarlo antes de la desactivacion de la cuenta','success'));

                }
            }
        } catch (Exception $e) {
            $response = new ResponseDTO(false, "Ocurrio un error en el proceso vuelve a intentar",null,'MSG',
                $msg->msgHtmlWithIcon('Error','Ocurrio un error en el proceso vuelve a intentar'.$e,'error'));
        }
        $this->db->disconnect();
        echo json_encode($response);
    }

    function GET_logout(){
        $useId = $_GET['userId'];
        $this->db = new DB();
        $msg = new Messenger();
        $response = null;
        try {
            $this->db->connect();
            $conditions = array('user_id' => $useId);
            $result = $this->db->delete('users_validation', $conditions);
            $response = new ResponseDTO(true, "Se han cerrado las demas sesiones",null,'MSG',
                'const formulario = document.getElementById("login");formulario.click();');
        }catch (Exception $e){
            error_log($e->getMessage());
            $response = new ResponseDTO(false, "Ocurrio un error en el proceso vuelve a intentar",null,'MSG',
                $msg->msgHtmlWithIcon('Aviso','En este momento presentamos inconvenientes, vuelve a intentar','warning'));
        } finally {
            $this->db->disconnect();
        }
        header('Content-Type: application/json');
        echo json_encode($response);
    }

    /**
     * @param string $email
     * @return user
     */
    private function getUserByEmail(string $email): user
    {
        $sqlUser = "SELECT * FROM users WHERE email_user = :email_user";
        $paramsUser = [':email_user' => $email];
        return new user($this->db->query($sqlUser, $paramsUser)->fetch(PDO::FETCH_ASSOC));
    }


}