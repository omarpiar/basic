<?php
namespace app\controllers;

use Yii;
use yii\web\Controller;
use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mime\Email;

class TestMailController extends Controller
{
    public function actionTest()
    {
        try {
            // 1. Configuración SMTP (usa tus credenciales reales)
            $dsn = 'smtp://23300785@uttt.edu.mx:OPA4912O@smtp.gmail.com:587';
            $from = '23300785@uttt.edu.mx';
            $to = 'picazoaranzoloomar@gmail.com';
            
            // 3. Crear transporte con opciones SSL
            $transport = Transport::fromDsn($dsn);
            
            // 4. Crear mailer y enviar correo
            $mailer = new Mailer($transport);
            $email = (new Email())
                ->from($from)
                ->to($to)
                ->subject('Prueba de correo desde Yii2')
                ->text('¡Funciona! Correo enviado correctamente desde tu aplicación.');
            
            $mailer->send($email);
            
            Yii::$app->session->setFlash('success', 'Correo enviado exitosamente a '.$to);
            return $this->render('test-result', ['success' => true]);
            
        } catch (\Exception $e) {
            Yii::error("Error en prueba de correo: " . $e->getMessage(), 'mailTest');
            Yii::$app->session->setFlash('error', 'Error al enviar: '.$e->getMessage());
            return $this->render('test-result', [
                'success' => false, 
                'error' => $e->getMessage()
            ]);
        }
    }
}