<?php

namespace Classes;
use PHPMailer\PHPMailer\PHPMailer;

class Email {

    public $email;
    public $nombre;
    public $token;

    //El constructor tomara el $email al cual vamos a enviarle el email de confirmacion, su $nombre y $token
    public function __construct($email, $nombre, $token) 
    {
        $this->email = $email;
        $this->nombre = $nombre;
        $this->token = $token;
    }

    public function enviarConfirmacion() {

        //Crear el objeto de email
        //Debemos ir a mail a https://mailtrap.io/inboxes para buscar nuestras credenciales (en este caso son estas)
        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->Host = $_ENV['EMAIL_HOST'];
        $mail->SMTPAuth = true;
        $mail->Port = $_ENV['EMAIL_PORT'];
        $mail->Username = $_ENV['EMAIL_USER'];
        $mail->Password = $_ENV['EMAIL_PASS'];
        //$mail->SMTPSecure = 'tls';

        $mail->setFrom('cuentas@appsalon.com');//Quien envia el correo (Si hacemos deployment del proyecto, aqui debe ir el dominio)
        $mail->addAddress('cuentas@appsalon.com', 'Appsalon.com');
        $mail->Subject = ('Confirma tu cuenta');

        //Set html
        $mail->isHTML(TRUE);
        $mail->CharSet = 'UTF-8';

        $contenido = "<html>";
        $contenido .= "<p><strong>Hola " . $this->nombre . "</strong> Has creado tu cuenta de App Salon, solo 
        debes confirmarla presionando el siguiente enlace</p>";
        //Ponemos el ?token para poder saber quien fue la persona que entro a "confirmar-cuenta"
        $contenido .= "<p>Presiona aqui: <a href='" . $_ENV['APP_URL'] . "/confirmar-cuenta?token=" 
        . $this->token . "'>Confirmar cuenta</a></p>";
        $contenido .= "<p>Si no reconoces esta cuenta, puedes ignorar el mensaje</p>";
        $contenido .= "</html>";

        $mail->Body = $contenido;

        //Enviar el email
        $mail->send();
    }

    //No le pasamos nada, ya que cuando se esta instanciando en otro archivo, se pasan las variables de el
    //constructor de esta clase (email, nombre, token)
    public function enviarInstrucciones() {
        //Crear el objeto de email
        //Debemos ir a mail a https://mailtrap.io/inboxes para buscar nuestras credenciales (en este caso son estas)
        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->Host = $_ENV['EMAIL_HOST'];
        $mail->SMTPAuth = true;
        $mail->Port = $_ENV['EMAIL_PORT'];
        $mail->Username = $_ENV['EMAIL_USER'];
        $mail->Password = $_ENV['EMAIL_PASS'];
        //$mail->SMTPSecure = 'tls';

        $mail->setFrom('cuentas@appsalon.com');//Quien envia el correo (Si hacemos deployment del proyecto, aqui debe ir el dominio)
        $mail->addAddress('cuentas@appsalon.com', 'Appsalon.com');
        $mail->Subject = ('Reestablece tu password');

        //Set html
        $mail->isHTML(TRUE);
        $mail->CharSet = 'UTF-8';

        $contenido = "<html>";
        $contenido .= "<p><strong>Hola " . $this->nombre . "</strong> Has solicitado reestablecer tu password,
        sigue el siguiente enlace para hacerlo</p>";
        //Ponemos el ?token para poder saber quien fue la persona que entro a "confirmar-cuenta"
        $contenido .= "<p>Presiona aqui: <a href='" . $_ENV['APP_URL'] . "/recuperar?token=" 
        . $this->token . "'>Reestablecer password</a></p>";
        $contenido .= "<p>Si no reconoces esta cuenta, puedes ignorar el mensaje</p>";
        $contenido .= "</html>";

        $mail->Body = $contenido;

        //Enviar el email
        $mail->send();
    }
}