<?php

namespace Dto;

class LoginDataDTO
{
    public $email_user;
    public $password;

    public function __construct($postData) {
        $this->email_user = isset($postData['email_user']) ? $postData['email_user'] : null;
        $this->password = isset($postData['password']) ? $postData['password'] : null;
    }

    public function isValid() {
        $errors = [];

        if (empty($this->email_user)) {
            $errors['email_user'] = 'El correo electrónico es requerido';
        }

        if (!filter_var($this->email_user, FILTER_VALIDATE_EMAIL)) {
            $errors['email_user'] = 'El correo electrónico no es valido';
        }

        if (empty($this->password)) {
            $errors['password'] = 'La contraseña es requerida';
        }

        if (!empty($errors)) {
            http_response_code(400);
            echo json_encode(['error' => $errors]);
            exit;
        }

        return true;
    }
}