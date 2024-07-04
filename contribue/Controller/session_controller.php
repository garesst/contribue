<?php

namespace contribue\Controller;
include_once('Controller/subscription_controller.php');

class session_controller
{
    public string $workspace = '';
    public $sub = null;
    public bool $isActive = false;
    public bool $isNew = false;

    public function __construct()
    {
        try {
            if (!isset($_SESSION['isActive'])){
                $this->verificationSession();
            } elseif(!$_SESSION['isActive']){
                $this->verificationSession();
            }else{
                $this->workspace = $_SESSION['workspace'];
                $this->isActive = $_SESSION['isActive'];
                $this->sub = $_SESSION['sub'];
            }
        }catch (Exception $e){
            throw new Exception("Ocurri un error al verificar la cuenta");
        }
    }

    function verificationSubDomain(): string
    {
        $paths = explode('.', $_SERVER['HTTP_HOST']);
        if(sizeof($paths)>2){
            return $paths[0];
        }else{
            throw new Exception("No se ha encontrado un subdominio");
        }
    }

    /**
     * @return void
     */
    public function verificationSession(): void
    {
        $this->workspace = $this->verificationSubDomain();
        $_SESSION['workspace'] = $this->workspace;
        $_SESSION['isActive'] = false;
        $sub = new subscription_controller();
        $this->sub = $sub->checkActiveSubscription($this->workspace);
        if (!empty($this->sub)) {
            $current_date = new DateTime();
            foreach ($this->sub as $subscription) {
                $end_date = new DateTime($subscription['end_date']);
                if ($current_date <= $end_date) {
                    $this->isActive = true;
                    $_SESSION['isActive'] = true;
                    $_SESSION['sub'] = $subscription;
                    break;
                }
            }
        } else {
            $this->isNew = true;
        }
    }

}