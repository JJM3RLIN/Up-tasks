<?php
namespace Classes;

use PHPMailer\PHPMailer\PHPMailer;

class Email{
    protected $email;
    protected $nombre;
    protected $token;

    public function __construct($email, $nombre, $token)
    {
        $this->email = $email;
        $this->nombre = $nombre;
        $this->token = $token;
        
    }
    public static function ActivarConfig(){
        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->SMTPAuth = true;
        $mail->Host = 'smtp.gmail.com';
        $mail->Port = 587;
       
        $mail->Username   = 'pruebaenviodecorreos1713@gmail.com';
        $mail->Password   = 'rendfhzlpjqsmctf';
        $mail->SMTPSecure = 'tls';
        $mail->CharSet = 'UTF-8';
        $mail->isHTML();  
        $mail->setFrom('pruebaEnvioDeCorreos1713@gmail.com', 'UpTask');
        return $mail;
    }
    public function enviarConfirmacion(){

        $mail = Email::ActivarConfig();
        $mail->addAddress($this->email);
        $mail->Subject = 'Confirma tu cuenta';
        $contenido = '<html>';
        $contenido .= "<p><strong>Hola {$this->nombre}</strong> Has creado una cuenta en UpTask, solo debes confirmarla en el siguiente enlace</p>";
        $contenido .= "<p>Presiona aquí:<a href='http://localhost:3000/confirmar?token={$this->token}' >Confirmar cuenta</a></p>";
        $contenido .= '<p>Si tú no creaste esta cuenta, puedes ignorar este mensaje</p>';
        $contenido .= '</html>';

        $mail->Body = $contenido;

        $mail->send();

    }
    public function enviarInstrucciones(){
        
        $mail = Email::ActivarConfig();
        $mail->addAddress($this->email);
        $mail->Subject = 'Reestablece tu password';
        $contenido = '<html>';
        $contenido .= "<p><strong>Hola {$this->nombre}</strong>Parece que has olvidado tu password, sigue el siguiente enlace para recuperarlo</p>";
        $contenido .= "<p>Presiona aquí:<a href='http://localhost:3000/reestablecer?token={$this->token}' >Reestablecer cuenta</a></p>";
        $contenido .= '<p>Si tú no solicitaste esta acción, puedes ignorar este mensaje</p>';
        $contenido .= '</html>';

        $mail->Body = $contenido;

        $mail->send();
    }  
}