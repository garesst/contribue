<?php

namespace Dto;

class UserDataDTO {
    public $name_user;
    public $lastname_user;
    public $email_user;
    public $tel_user;
    public $country_user;
    public $password;
    public $repeat_password;
    public $tel_ful_user;
    public $cod_tel_user;

    public function __construct($postData) {
        $this->name_user = isset($postData['name_user']) ? $postData['name_user'] : null;
        $this->lastname_user = isset($postData['lastname_user']) ? $postData['lastname_user'] : null;
        $this->email_user = isset($postData['email_user']) ? $postData['email_user'] : null;
        $this->tel_user = isset($postData['tel_user']) ? $postData['tel_user'] : null;
        $this->country_user = isset($postData['country_user']) ? $postData['country_user'] : null;
        $this->password = isset($postData['password']) ? $postData['password'] : null;
        $this->repeat_password = isset($postData['repeat_password']) ? $postData['repeat_password'] : null;
        $this->tel_ful_user = isset($postData['tel_ful_user']) ? $postData['tel_ful_user'] : null;
        $this->cod_tel_user = isset($postData['cod_tel_user']) ? $postData['cod_tel_user'] : null;
    }

    public function isValid() {
        $errors = [];

        if (empty($this->name_user)) {
            $errors['name_user'] = 'El nombre de usuario es requerido';
        }

        if (empty($this->lastname_user)) {
            $errors['lastname_user'] = 'El apellido de usuario es requerido';
        }

        if (empty($this->email_user)) {
            $errors['email_user'] = 'El correo electrónico es requerido';
        }

        if (!filter_var($this->email_user, FILTER_VALIDATE_EMAIL)) {
            $errors['email_user'] = 'El correo electrónico no es valido';
        }

        if (empty($this->tel_user)) {
            $errors['tel_user'] = 'El número de teléfono es requerido';
        }

        if (empty($this->tel_ful_user)) {
            $errors['tel_ful_user'] = 'El número de teléfono es requerido';
        }

        if (empty($this->cod_tel_user)) {
            $errors['cod_tel_user'] = 'El número de teléfono es requerido';
        }

        if (empty($this->country_user)) {
            $errors['country_user'] = 'El país es requerido';
        }

        if (empty($this->password)) {
            $errors['password'] = 'La contraseña es requerida';
        }

        if (empty($this->repeat_password)) {
            $errors['repeat_password'] = 'La confirmación de la contraseña es requerida';
        }

        if ($this->password !== $this->repeat_password) {
            $errors['repeat_password'] = 'Las contraseñas no coinciden';
        }

        if (!empty($errors)) {
            http_response_code(400);
            echo json_encode(['error' => $errors]);
            exit;
        }

        return true;
    }
}