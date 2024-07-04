<?php

namespace Dto;

class ResponseDTO {
    public $success;
    public $message;
    public $data;
    public $action;
    public $execute;

    public function __construct($success, $message, $data = null, $action= NULL, $execute = NULL) {
        $this->success = $success?'success':'warning';
        $this->message = $message;
        $this->action = $action;
        $this->data = $data;
        $this->execute = $execute;
    }
}