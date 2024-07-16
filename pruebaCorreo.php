<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'path/to/PHPMailer/src/Exception.php';
require 'path/to/PHPMailer/src/PHPMailer.php';
require 'path/to/PHPMailer/src/SMTP.php';

// Instancia PHPMailer
$mail = new PHPMailer(true);

try {
    // Configuración del servidor SMTP
    $mail->isSMTP();
    $mail->Host       = 'smtp.hostinger.com'; // Cambia según tu servidor SMTP
    $mail->SMTPAuth   = true;
    $mail->Username   = 'soporte@trabajounesum.com'; // Cambia a tu email
    $mail->Password   = '1314025733Alulu-'; // Cambia a tu contraseña
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Habilitar encriptación TLS
    $mail->Port       = 587; // Puerto SMTP

    // Destinatarios
    $mail->setFrom('soporte@trabajounesum.com', 'Tu Nombre');
    $mail->addAddress('reyescarvajala@gmail.com', 'Nombre Destinatario');

    // Contenido del email
    $mail->isHTML(true);
    $mail->Subject = 'Asunto del Email';
    $mail->Body    = 'Este es el contenido del email en formato HTML';
    $mail->AltBody = 'Este es el contenido del email en texto plano para clientes que no soportan HTML';

    $mail->send();
    echo 'El mensaje ha sido enviado';
} catch (Exception $e) {
    echo "El mensaje no pudo ser enviado. Mailer Error: {$mail->ErrorInfo}";
}
