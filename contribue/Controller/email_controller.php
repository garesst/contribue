<?php
namespace contribue\Controller;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'libs/PHPMailer/src/Exception.php';
require 'libs/PHPMailer/src/PHPMailer.php';
require 'libs/PHPMailer/src/SMTP.php';
class email_controller
{
    public function __construct()
    {
    }

    public function send_email_createUser($user_email, $user_name, $user_lastname, $tenant){
        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host = SMTP_URL; // Servidor SMTP
            $mail->SMTPAuth = true;
            $mail->Username = USER_SMTP; // Usuario SMTP
            $mail->Password = PSW_SMPT; // Contraseña SMTP
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = PORT_SMTP;

            // Configuración del remitente y destinatario
            $mail->setFrom(USER_SMTP, REM_EMAIL);
            $mail->addAddress($user_email, $user_name.' '.$user_lastname);

            // Contenido del correo
            $mail->isHTML(true);
            $mail->Subject = 'Registro de cuenta en contribue';
            $urlConnect = CONNECT_TYPE.'://'.$tenant.'.'.DOMAIN;
            $mail->Body    = 'Esta es la confirmacion de tu registro en plataforma <br/>
                      Tus datos de ingreso son los siguiente: <br/>
                      <b>Link de ingreso: </b> <a target="_blank" href="'.$urlConnect.'">'.$urlConnect.'</a> <br/>
                      <b>Usuario: </b> '.$user_email.' <br/><br/>
                      Te agradecemos por tu registro.';
            $mail->AltBody = 'Esta es la confirmación de tu registro en plataforma
                      Tus datos de ingreso son los siguientes:
                      Link de ingreso: '.$urlConnect.'
                      Usuario: '.$user_email.'

                      Te agradecemos por tu registro.';
            $mail->send();
        } catch (Exception $e) {
            throw new Exception("Ocurrio un error al enviar el email");
        }
    }

}