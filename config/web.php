<?php

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';

$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
        '@webroot' => dirname(__DIR__) . '/web',
        '@web' => '' 
    ],
    'components' => [
        'request' => [
            //'baseUrl' => '',
            'enableCsrfValidation' => true,
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'gK9Lyve5Pw7HIYtofJH4MSTtESq92c5U',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => false,
        ],
        'errorHandler' => [
            'class' => 'app\components\CustomErrorHandler',
            'smtpConfig' => [
            'dsn' => 'smtp://23300785@uttt.edu.mx:OPA4912O@smtp.gmail.com:587',
            'from' => '23300785@uttt.edu.mx',
            'to' => 'picazoaranzoloomar@gmail.com',
            'timeout' => 30

        ],
            // 'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => yii\symfonymailer\Mailer::class,
            'viewPath' => '@app/mail',
            'useFileTransport' => false, // Cambia a true si no deseas enviar correos reales.
            'transport' => [
                'dsn' => 'smtp://23300785@uttt.edu.mx:OPA4912O@smtp.gmail.com:587',
                'scheme' => 'smtp',
                'host' => 'smtp.gmail.com', // Configura según tu servidor.
                'port' => 587,
                'username' => '23300785@uttt.edu.mx',
                'password' => 'OPA4912O',
                'encryption' => 'tls',
                
            ],
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'db' => $db,
        /*
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
            ],
        ],
        */
    ],
    'params' => $params,
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];
}

return $config;
