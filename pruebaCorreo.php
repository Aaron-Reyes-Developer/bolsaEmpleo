<?php



$to = 'reyescarvajala@gmail.com';
$subject = 'Asunto del Email';
$message = 'Este es el contenido del email.';
$headers = 'From: soporte@trabajounesum.com' . "\r\n" .
    'Reply-To: soporte@trabajounesum.com' . "\r\n" .
    'X-Mailer: PHP/' . phpversion();

if (mail($to, $subject, $message, $headers)) {
    echo 'El mensaje ha sido enviado';
} else {
    echo 'El mensaje no pudo ser enviado';
}
