<?php

namespace Service\subscription;

include_once('Controller/subscription_controller.php');
include_once('Dto/ResponseDTO.php');

use Commons\Messenger;
use Commons\verification;
use contribue\Controller\subscription_controller;
use Dto\ResponseDTO;

class subscription_rest_api
{
    private string $version = '0.0.1';
    private verification $verication;

    public function __construct($version, $verificar)
    {
        $this->verication = new verification();
        $this->verication->version_validation($version, $this->version, $verificar);
    }

    public function POST_subscriptionTrialService(){
        global $requestPost;
        $msg = new Messenger();
        $response='';
        try {
            if(isset($_COOKIE['jwt']) && $this->verication->validarToken($_COOKIE['jwt'])){
                $subsController = new subscription_controller();
                $subsController->redeemTrialTime($_SESSION['workspace'],$requestPost['payment_plan_id'],'MES',$requestPost['amount']);
                $response = new ResponseDTO(true, "Compra realizada",null,'MSG',
                    $msg->msgHtmlWithIconAndTimer('Proceso realizado','Tu subscripción ha sido correcatmente asignado','success'));

            }else{
                echo 'no ok';
            }
        }catch (Exception $e) {
            $response = new ResponseDTO(false, "Ocurrio un error en el proceso vuelve a intentar",null,'MSG',
                $msg->msgHtmlWithIcon('Error','Ocurrio un error en el proceso vuelve a intentar'.$e,'error'));
        }
        echo json_encode($response);
    }

}