<?php
require 'vendor/autoload.php';


use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mime\Email;

$transport = Transport::fromDsn('smtp://23300785@uttt.edu.mx:OPA4912O@smtp.gmail.com:587');
$mailer = new Mailer($transport);

$email = (new Email())
    ->from('23300785@uttt.edu.mx')
    ->to('picazoaranzoloomar@gmail.com')
    ->subject('Prueba directa')
    ->text('Si esto funciona, el problema está en Yii');

$mailer->send($email);
echo "Correo enviado!";