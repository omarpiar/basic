<?php
namespace app\components;

use Yii;
use yii\base\ErrorHandler;
use yii\helpers\VarDumper;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mime\Email;

class CustomErrorHandler extends \yii\web\ErrorHandler
{
    public $smtpConfig = [
        'dsn' => 'smtp://23300785@uttt.edu.mx:OPA4912O@smtp.gmail.com:587',
        'from' => '23300785@uttt.edu.mx',
        'to' => '23300785@uttt.edu.mx',
        'timeout' => 30
    ];

    public function handleException($exception)
    {
        Yii::info("Iniciando manejo de excepción", 'errorHandler');
        parent::handleException($exception);
        $this->sendErrorEmail($exception);
    }

    public function sendErrorEmail($exception)
    {
        
        try {

            $dsn = 'smtp://23300785@uttt.edu.mx:OPA4912O@smtp.gmail.com:587';

            // Validación de configuración
            if (empty($this->smtpConfig['dsn'])) {
                throw new \Exception("Configuración SMTP no proporcionada");
            }

            Yii::info("Creando transporte SMTP", 'errorHandler');
            $transport = Transport::fromDsn($dsn);
            
            // // Configuración adicional para XAMPP/localhost
            // $transport->setStreamOptions([
            //     'ssl' => [
            //         'verify_peer' => false,
            //         'verify_peer_name' => false,
            //         'allow_self_signed' => true
            //     ]
            // ]);

            Yii::info("Creando instancia de Mailer", 'errorHandler');
            $mailer = new Mailer($transport);

            $subject = "Error en " . Yii::$app->name;
            $subject .= " - " . substr(str_replace(["\r", "\n"], ' ', $exception->getMessage()), 0, 50);
            
            Yii::info("Construyendo mensaje de error", 'errorHandler');
            $email = (new Email())
                ->from($this->smtpConfig['from'])
                ->to($this->smtpConfig['to'])
                ->subject($subject)
                ->html($this->buildErrorMessage($exception));

            Yii::info("Enviando correo...", 'errorHandler');
            $mailer->send($email);
            
            Yii::info("Correo de error enviado exitosamente a {$this->smtpConfig['to']}", 'errorHandler');

        } catch (\Exception $e) {
            Yii::error("FALLO CRÍTICO al enviar correo de error: " . $e->getMessage(), 'errorHandler');
            Yii::error("Trace: " . $e->getTraceAsString(), 'errorHandler');
            
            // Registrar configuración SMTP (sin contraseña)
            $loggedDsn = preg_replace('/:([^@]+)@/', ':********@', $this->smtpConfig['dsn']);
            Yii::error("Configuración SMTP usada: " . VarDumper::dumpAsString([
                'dsn' => $loggedDsn,
                'from' => $this->smtpConfig['from'],
                'to' => $this->smtpConfig['to'],
                'time' => date('Y-m-d H:i:s')
            ]), 'errorHandler');
        }
    }
    
    /**
     * Construye el mensaje de error con formato HTML
     */
    protected function buildErrorMessage($exception)
    {
        $message = "<h1>Error en la aplicación " . Yii::$app->name . "</h1>";
        $message .= "<p><strong>Fecha:</strong> " . date('Y-m-d H:i:s') . "</p>";
        
        if (Yii::$app->has('request')) {
            $message .= "<p><strong>URL:</strong> " . Yii::$app->request->absoluteUrl . "</p>";
            $message .= "<p><strong>Método:</strong> " . Yii::$app->request->method . "</p>";
            $message .= "<p><strong>IP:</strong> " . Yii::$app->request->userIP . "</p>";
        }
        
        $message .= "<p><strong>Usuario:</strong> " . (Yii::$app->user->isGuest ? 'Guest' : Yii::$app->user->identity->username) . "</p>";
        $message .= "<p><strong>Tipo:</strong> " . get_class($exception) . "</p>";
        $message .= "<p><strong>Mensaje:</strong> " . nl2br($exception->getMessage()) . "</p>";
        $message .= "<p><strong>Archivo:</strong> " . $exception->getFile() . " (línea " . $exception->getLine() . ")</p>";
        
        $message .= "<h2>Stack Trace:</h2>";
        $message .= "<pre>" . $exception->getTraceAsString() . "</pre>";
        
        if (Yii::$app->has('request')) {
            $message .= "<h2>Datos de la solicitud:</h2>";
            $message .= "<h3>GET:</h3>";
            $message .= "<pre>" . VarDumper::dumpAsString(Yii::$app->request->getQueryParams()) . "</pre>";
            $message .= "<h3>POST:</h3>";
            $message .= "<pre>" . VarDumper::dumpAsString(Yii::$app->request->post()) . "</pre>";
            $message .= "<h3>Headers:</h3>";
            $message .= "<pre>" . VarDumper::dumpAsString(Yii::$app->request->headers->toArray()) . "</pre>";
        }
        
        return $message;
    }
}