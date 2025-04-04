<?php
namespace app\controllers;

use Yii;
use yii\web\Controller;
use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mime\Email;

class MailServiceController extends Controller
{
    /**
     * Envía un correo electrónico
     * @param string $to Destinatario
     * @param string $subject Asunto
     * @param string $body Cuerpo del mensaje (HTML o texto)
     * @param string $from Remitente (opcional)
     * @return bool True si se envió correctamente
     */
    public static function sendEmail($to, $subject, $body, $from = null)
    {
        try {
            // Configuración SMTP desde params.php
            $smtpConfig = Yii::$app->params['smtp'];
            
            // Crear transporte
            $transport = Transport::fromDsn($smtpConfig['dsn']);
            
            // Crear mailer
            $mailer = new Mailer($transport);
            
            // Configurar remitente (usa el default si no se especifica)
            $from = $from ?: $smtpConfig['from'];
            
            // Crear y enviar email
            $email = (new Email())
                ->from($from)
                ->to($to)
                ->subject($subject)
                ->html($body);
            
            $mailer->send($email);
            
            Yii::info("Correo enviado a $to", 'mailService');
            return true;
            
        } catch (\Exception $e) {
            Yii::error("Error enviando correo a $to: " . $e->getMessage(), 'mailService');
            return false;
        }
    }

    /**
     * Envía un correo de error del sistema
     * @param \Exception $exception La excepción ocurrida
     * @return bool True si se envió correctamente
     */
    public static function sendErrorEmail(\Exception $exception)
    {
        $smtpConfig = Yii::$app->params['smtp'];
        $subject = "Error en " . Yii::$app->name . ": " . substr($exception->getMessage(), 0, 50);
        $body = self::buildErrorEmailBody($exception);
        
        return self::sendEmail($smtpConfig['adminEmail'], $subject, $body);
    }

    /**
     * Construye el cuerpo del correo de error
     */
    protected static function buildErrorEmailBody(\Exception $exception)
    {
        $body = "<h1>Error en " . Yii::$app->name . "</h1>";
        $body .= "<p><strong>Fecha:</strong> " . date('Y-m-d H:i:s') . "</p>";
        $body .= "<p><strong>Tipo:</strong> " . get_class($exception) . "</p>";
        $body .= "<p><strong>Mensaje:</strong> " . nl2br($exception->getMessage()) . "</p>";
        $body .= "<p><strong>Archivo:</strong> " . $exception->getFile() . " (línea " . $exception->getLine() . ")</p>";
        $body .= "<h2>Stack Trace:</h2>";
        $body .= "<pre>" . $exception->getTraceAsString() . "</pre>";
        
        return $body;
    }
}