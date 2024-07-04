<?php
namespace Model;
// Define la clase User
class user {
    // Propiedades de la clase
    public $created_at;
    public $user_id;
    public $name_user;
    public $email_user;
    public $active_user;
    public $status_user;
    public $cod_tel_user;
    public $tel_user;
    public $country_user;
    public $lastname_user;
    public $tel_ful_user;

    public function __construct($data) {
        $this->created_at = $data['created_at'];
        $this->user_id = $data['user_id'];
        $this->name_user = $data['name_user'];
        $this->email_user = $data['email_user'];
        $this->active_user = $data['active_user'];
        $this->status_user = $data['status_user'];
        $this->cod_tel_user = $data['cod_tel_user'];
        $this->tel_user = $data['tel_user'];
        $this->country_user = $data['country_user'];
        $this->lastname_user = $data['lastname_user'];
        $this->tel_ful_user = $data['tel_ful_user'];
    }
}